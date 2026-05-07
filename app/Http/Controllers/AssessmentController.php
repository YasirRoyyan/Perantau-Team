<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    private const SESSION_ANSWERS = 'interiology.answers';

    private const SESSION_RESULT = 'interiology.result';

    private const SESSION_STEP = 'interiology.step';

    private const QUESTIONS = [
        [
            'question' => 'Kamu lebih suka ruangan yang bagaimana?',
            'options' => ['Simpel dan rapi', 'Hangat dan nyaman', 'Modern dan elegan', 'Berwarna dan ceria'],
        ],
        [
            'question' => 'Warna apa yang paling kamu suka untuk ruangan?',
            'options' => ['Putih dan krem', 'Coklat dan kayu', 'Abu-abu dan hitam', 'Biru dan hijau'],
        ],
        [
            'question' => 'Furniture seperti apa yang kamu suka?',
            'options' => ['Kayu natural', 'Sofa empuk besar', 'Metal dan kaca', 'Campuran warna-warni'],
        ],
        [
            'question' => 'Bagaimana pencahayaan ideal menurutmu?',
            'options' => ['Cahaya alami dari jendela', 'Lampu hangat redup', 'Lampu terang modern', 'Lampu warna-warni'],
        ],
        [
            'question' => 'Dekorasi apa yang kamu suka di dinding?',
            'options' => ['Minimalis tanpa banyak hiasan', 'Foto keluarga dan kenangan', 'Lukisan abstrak', 'Poster dan seni pop'],
        ],
        [
            'question' => 'Lantai seperti apa yang kamu pilih?',
            'options' => ['Kayu natural', 'Karpet tebal', 'Marmer atau granit', 'Vinyl berwarna'],
        ],
        [
            'question' => 'Bagaimana suasana ruangan impianmu?',
            'options' => ['Tenang dan damai', 'Hangat seperti rumah nenek', 'Mewah dan berkelas', 'Seru dan penuh energi'],
        ],
        [
            'question' => 'Apa yang paling penting dalam sebuah ruangan?',
            'options' => ['Kerapian', 'Kenyamanan', 'Keindahan', 'Keunikan'],
        ],
        [
            'question' => 'Apakah kamu menyukai tanaman di dalam ruangan?',
            'options' => ['Ya, sedikit saja', 'Ya, banyak tanaman', 'Tidak terlalu', 'Lebih suka bunga palsu'],
        ],
        [
            'question' => 'Gaya interior mana yang paling menarik?',
            'options' => ['Minimalis', 'Scandinavian', 'Industrial', 'Bohemian'],
        ],
    ];

    private const IMAGES = [
        'assets/images/img-4.png',
        'assets/images/img-5.png',
        'assets/images/img3.png',
        'assets/images/img-container.png',
        'assets/images/img-4.png',
        'assets/images/img-5.png',
        'assets/images/img3.png',
        'assets/images/img-container.png',
        'assets/images/img-4.png',
        'assets/images/img-5.png',
    ];

    private const TITLES = [
        'Mulailah menjawab pertanyaan...',
        '',
        'Terus jawab pertanyaannya...',
        '',
        'Hmmm, seleramu bagus',
        '',
        'Ayoo, sedikit lagi...',
        'Sedikit lagi...',
        'Ruanganmu hampir selesai...',
        'Hasilnya akan segera muncul...',
    ];

    private const RESULT_TYPES = ['minimalis', 'scandinavian', 'modern', 'bohemian'];

    private const RESULTS = [
        'minimalis' => [
            'title' => 'Si Kalem Minimalis',
            'description' => 'Kamu cocok terhadap furnitur yang berbahan kayu dan berbentuk simple, dengan warna yang kalem seperti coklat, putih, dan krem. Coba merek seperti Fabelio, Scandinavian, Warjo dsb.',
            'image' => 'assets/images/img-container.png',
        ],
        'scandinavian' => [
            'title' => 'Si Hangat Scandinavian',
            'description' => 'Kamu menyukai ruangan yang hangat dan nyaman dengan sentuhan natural, warna lembut, dan furnitur kayu yang membuat ruangan terasa akrab.',
            'image' => 'assets/images/img-Scandinavian.png',
        ],
        'modern' => [
            'title' => 'Si Elegan Modern',
            'description' => 'Kamu suka ruangan yang terlihat mewah dan berkelas, dengan bentuk tegas, warna netral, dan detail yang bersih tanpa terlalu ramai.',
            'image' => 'assets/images/img-Modern.png',
        ],
        'bohemian' => [
            'title' => 'Si Ceria Bohemian',
            'description' => 'Kamu suka ruangan yang penuh warna dan karakter, dengan dekorasi ekspresif, tekstur berlapis, dan suasana yang bebas serta kreatif.',
            'image' => 'assets/images/img-Bohemian.png',
        ],
    ];

    public function home(): View
    {
        return view('pages.home');
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

        $step = (int) $request->session()->get(self::SESSION_STEP, 0);

        if ($step >= count(self::QUESTIONS)) {
            return redirect()->route('result');
        }

        return view('pages.assessment', [
            'image' => self::IMAGES[$step],
            'progress' => $step + 1,
            'question' => self::QUESTIONS[$step],
            'title' => self::TITLES[$step],
            'total' => count(self::QUESTIONS),
        ]);
    }

    public function answer(Request $request): RedirectResponse
    {
        if (! $request->session()->has(self::SESSION_STEP)) {
            return redirect()->route('prepare');
        }

        $step = (int) $request->session()->get(self::SESSION_STEP, 0);

        if ($step >= count(self::QUESTIONS)) {
            return redirect()->route('result');
        }

        $validated = $request->validate([
            'option' => ['required', 'integer', 'min:0', 'max:3'],
        ]);

        $answers = $request->session()->get(self::SESSION_ANSWERS, []);
        $answers[] = (int) $validated['option'];
        $nextStep = $step + 1;

        $request->session()->put(self::SESSION_ANSWERS, $answers);
        $request->session()->put(self::SESSION_STEP, $nextStep);

        if ($nextStep >= count(self::QUESTIONS)) {
            $request->session()->put(self::SESSION_RESULT, $this->calculateResult($answers));

            return redirect()->route('result');
        }

        return redirect()->route('assessment.show');
    }

    public function result(Request $request): View|RedirectResponse
    {
        $type = $request->session()->get(self::SESSION_RESULT);

        if (! $type) {
            return redirect()->route('prepare');
        }

        return view('pages.result', [
            'result' => self::RESULTS[$type] ?? self::RESULTS['minimalis'],
            'type' => $type,
        ]);
    }

    private function initializeAssessment(Request $request): void
    {
        $request->session()->put(self::SESSION_ANSWERS, []);
        $request->session()->put(self::SESSION_STEP, 0);
        $request->session()->forget(self::SESSION_RESULT);
    }

    private function resetAssessment(Request $request): void
    {
        $request->session()->forget([
            self::SESSION_ANSWERS,
            self::SESSION_RESULT,
            self::SESSION_STEP,
        ]);
    }

    private function calculateResult(array $answers): string
    {
        $counts = [0, 0, 0, 0];

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

        return self::RESULT_TYPES[$maxIndex];
    }
}
