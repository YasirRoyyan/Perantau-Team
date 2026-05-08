<?php

namespace App\Http\Controllers;

use App\Models\AssessmentQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminQuestionController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        return view('admin.questions.index', [
            'questions' => AssessmentQuestion::ordered()->get(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorizeAdmin($request);

        return view('admin.questions.form', [
            'question' => new AssessmentQuestion(['options' => ['', '', '', '']]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        AssessmentQuestion::create($this->validatedData($request));

        return redirect()->route('admin.questions.index')->with('status', 'Pertanyaan baru berhasil ditambahkan.');
    }

    public function edit(Request $request, AssessmentQuestion $question): View
    {
        $this->authorizeAdmin($request);

        return view('admin.questions.form', compact('question'));
    }

    public function update(Request $request, AssessmentQuestion $question): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $question->update($this->validatedData($request, $question));

        return redirect()->route('admin.questions.index')->with('status', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(Request $request, AssessmentQuestion $question): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $question->delete();

        return back()->with('status', 'Pertanyaan berhasil dihapus.');
    }

    private function validatedData(Request $request, ?AssessmentQuestion $question = null): array
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'options' => ['required', 'array', 'min:2', 'max:6'],
            'options.*' => ['required', 'string', 'max:120'],
            'image' => ['required', 'string', 'max:255'],
            'intro_title' => ['nullable', 'string', 'max:160'],
            'sort_order' => ['required', 'integer', 'min:1', 'unique:assessment_questions,sort_order'.($question ? ','.$question->id : '')],
        ]);

        $validated['options'] = array_values($validated['options']);

        return $validated;
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'admin', 403);
    }
}
