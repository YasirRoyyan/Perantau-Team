<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAttempt;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use App\Models\SiteContent;
use App\Models\SocialLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    private const SESSION_ANSWERS = 'interiology.answers';

    private const SESSION_RESULT = 'interiology.result';

    private const SESSION_STEP = 'interiology.step';

    private const SESSION_ATTEMPT = 'interiology.attempt';

    private const RESULT_STYLE_ORDER = [
        'scandinavian',
        'bohemian',
        'japandi',
        'minimalist',
        'modern',
        'industrial',
    ];

    public function home(): View
    {
        $home = array_replace_recursive(
            $this->defaultHomeContent(),
            SiteContent::payload('home', []),
        );

        return view('pages.home', [
            'hero' => $home['hero'],
            'workflowTitle' => $home['workflow_title'],
            'workflowSteps' => $home['workflow_steps'],
            'showcase' => $home['showcase'],
            'customRoomCta' => $home['custom_room_cta'],
            'galleryImages' => $home['gallery_images'],
            'footer' => $home['footer'] + ['socials' => SocialLink::activeOrFallback()],
        ]);
    }

    public function prepare(Request $request): View
    {
        $this->resetAssessment($request);

        return view('pages.prepare');
    }

    public function start(Request $request): RedirectResponse
    {
        $this->initializeAssessment($request);

        return redirect()->route('assessment.show');
    }

    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has(self::SESSION_STEP)) {
            $this->initializeAssessment($request);
        }

        $questions = AssessmentQuestion::ordered()->get();

        if ($questions->isEmpty()) {
            return redirect()->route('prepare')
                ->with('error', 'Data asesmen belum tersedia. Jalankan database seeder terlebih dahulu.');
        }

        $step = (int) $request->session()->get(self::SESSION_STEP, 0);

        if ($step >= $questions->count()) {
            return redirect()->route('result');
        }

        $question = $questions->values()->get($step);

        return view('pages.assessment', [
            'image' => $question->image,
            'progress' => $step + 1,
            'question' => [
                'question' => $question->question,
                'options' => $question->options,
            ],
            'title' => $question->intro_title ?? '',
            'total' => $questions->count(),
        ]);
    }

    public function back(Request $request): RedirectResponse
    {
        if (! $request->session()->has(self::SESSION_STEP)) {
            return redirect()->route('prepare');
        }

        $step = (int) $request->session()->get(self::SESSION_STEP, 0);

        if ($step <= 0) {
            return redirect()->route('prepare');
        }

        $answers = $request->session()->get(self::SESSION_ANSWERS, []);
        array_pop($answers);

        $request->session()->put(self::SESSION_ANSWERS, $answers);
        $request->session()->put(self::SESSION_STEP, max($step - 1, 0));

        return redirect()->route('assessment.show');
    }

    public function answer(Request $request): RedirectResponse
    {
        if (! $request->session()->has(self::SESSION_STEP)) {
            return redirect()->route('prepare');
        }

        $questions = AssessmentQuestion::ordered()->get()->values();

        if ($questions->isEmpty()) {
            return redirect()->route('prepare')
                ->with('error', 'Data asesmen belum tersedia. Jalankan database seeder terlebih dahulu.');
        }

        $step = (int) $request->session()->get(self::SESSION_STEP, 0);

        if ($step >= $questions->count()) {
            return redirect()->route('result');
        }

        $question = $questions->get($step);
        $maxOption = max(count($question->options ?? []) - 1, 0);

        $validated = $request->validate([
            'option' => ['required', 'integer', 'min:0', 'max:'.$maxOption],
        ]);

        $answers = $request->session()->get(self::SESSION_ANSWERS, []);
        $answers[] = (int) $validated['option'];
        $nextStep = $step + 1;

        $request->session()->put(self::SESSION_ANSWERS, $answers);
        $request->session()->put(self::SESSION_STEP, $nextStep);

        if ($nextStep >= $questions->count()) {
            $resultKey = $this->calculateResult($answers);
            $request->session()->put(self::SESSION_RESULT, $resultKey);

            $attempt = $this->storeAttempt($request, $resultKey, $answers);
            $request->session()->put(self::SESSION_ATTEMPT, $attempt?->id);

            return redirect()->route('result');
        }

        return redirect()->route('assessment.show');
    }

    public function result(Request $request): View|RedirectResponse
    {
        $type = $this->normalizeResultKey($request->session()->get(self::SESSION_RESULT));

        if (! $type) {
            return redirect()->route('prepare');
        }

        $result = $this->resolveAssessmentResult($type)
            ?? AssessmentResult::ordered()->first();

        if (! $result) {
            return redirect()->route('prepare')
                ->with('error', 'Data hasil asesmen belum tersedia. Jalankan database seeder terlebih dahulu.');
        }

        return view('pages.result', [
            'result' => [
                'title' => $result->title,
                'description' => $result->description,
                'image' => $result->image,
            ],
            'attempt' => $request->session()->get(self::SESSION_ATTEMPT)
                ? AssessmentAttempt::find($request->session()->get(self::SESSION_ATTEMPT))
                : null,
            'type' => $result->style_key,
        ]);
    }

    private function initializeAssessment(Request $request): void
    {
        $request->session()->put(self::SESSION_ANSWERS, []);
        $request->session()->put(self::SESSION_STEP, 0);
        $request->session()->forget(self::SESSION_RESULT);
        $request->session()->forget(self::SESSION_ATTEMPT);
    }

    private function resetAssessment(Request $request): void
    {
        $request->session()->forget([
            self::SESSION_ANSWERS,
            self::SESSION_RESULT,
            self::SESSION_STEP,
            self::SESSION_ATTEMPT,
        ]);
    }

    private function storeAttempt(Request $request, string $resultKey, array $answers): ?AssessmentAttempt
    {
        $result = $this->resolveAssessmentResult($this->normalizeResultKey($resultKey))
            ?? AssessmentResult::ordered()->first();

        if (! $result || ! $request->user()) {
            return null;
        }

        return AssessmentAttempt::create([
            'user_id' => $request->user()->id,
            'assessment_result_id' => $result->id,
            'result_key' => $result->style_key,
            'result_title' => $result->title,
            'result_description' => $result->description,
            'result_image' => $result->image,
            'answers' => $answers,
        ]);
    }

    private function calculateResult(array $answers): string
    {
        $resultTypes = collect(self::RESULT_STYLE_ORDER);
        $counts = array_fill(0, $resultTypes->count(), 0);

        foreach ($answers as $answer) {
            if (array_key_exists($answer, $counts)) {
                $counts[$answer]++;
            }
        }

        $maxIndex = 0;

        foreach ($counts as $index => $count) {
            if ($count > $counts[$maxIndex]) {
                $maxIndex = $index;
            }
        }

        return $resultTypes->get($maxIndex, 'scandinavian');
    }

    private function normalizeResultKey(?string $key): ?string
    {
        if (! $key) {
            return null;
        }

        if ($key === 'minimalis') {
            return 'minimalist';
        }

        return in_array($key, self::RESULT_STYLE_ORDER, true) ? $key : null;
    }

    private function resolveAssessmentResult(?string $key): ?AssessmentResult
    {
        if (! $key) {
            return null;
        }

        $result = AssessmentResult::where('style_key', $key)->first();

        if ($result) {
            return $result;
        }

        if ($key === 'minimalist') {
            return AssessmentResult::where('style_key', 'minimalis')->first();
        }

        return null;
    }

    private function defaultHomeContent(): array
    {
        return [
            'hero' => [
                'title' => 'Kenali selera desain ruangan kamu di Interiology',
                'description' => 'Jawab beberapa pertanyaan singkat dan lihat rekomendasi ruang tamu yang sesuai dengan kepribadianmu.',
                'button' => 'Cari Selera mu!',
            ],
            'workflow_title' => 'Bagaimana cara untuk menentukan selera mu?',
            'workflow_steps' => [
                ['class' => 'step-card-1', 'icon' => 'assets/icons/icon-sofa.png', 'alt' => 'Sofa', 'label' => 'Mulai Asesmen'],
                ['class' => 'step-card-2', 'icon' => 'assets/icons/icon-table.png', 'alt' => 'Nightstand', 'label' => 'Jawab beberapa pertanyaan'],
                ['class' => 'step-card-3', 'icon' => 'assets/icons/icon-tv.png', 'alt' => 'Television', 'label' => 'Tampilan Interior Muncul Sesuai Jawaban'],
                ['class' => 'step-card-4', 'icon' => 'assets/icons/icon-verified.png', 'alt' => 'Check', 'label' => 'Selamat Seleramu berhasil ditemukan!'],
            ],
            'showcase' => ['image' => 'assets/images/img-container.png', 'alt' => 'Contoh Ruangan'],
            'custom_room_cta' => ['title' => 'Tertarik untuk membuat ruangan sendiri?', 'button' => 'Mulai Sekarang!'],
            'gallery_images' => [
                ['image' => 'assets/images/img3-bw.png', 'alt' => 'Inspirasi 1'],
                ['image' => 'assets/images/img-5-bw.png', 'alt' => 'Inspirasi 2'],
                ['image' => 'assets/images/img-4-bw.png', 'alt' => 'Inspirasi 3'],
            ],
            'footer' => [
                'title' => 'Tentang Interiology',
                'description' => 'Website ini dirancang untuk membantu kamu menemukan gaya ruang tamu yang paling sesuai dengan kepribadian dan preferensimu. Melalui asesmen interaktif dan visualisasi sederhana, kami berharap kamu bisa lebih percaya diri dalam menentukan pilihan desain interior.',
                'location' => 'Bandung, Jawa Barat, Indonesia',
            ],
        ];
    }
}
