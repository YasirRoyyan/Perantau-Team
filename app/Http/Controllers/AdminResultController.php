<?php

namespace App\Http\Controllers;

use App\Models\AssessmentResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminResultController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        return view('admin.results.index', [
            'results' => AssessmentResult::ordered()->get(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorizeAdmin($request);

        return view('admin.results.form', ['result' => new AssessmentResult()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        AssessmentResult::create($this->validatedData($request));

        return redirect()->route('admin.results.index')->with('status', 'Hasil desain baru berhasil ditambahkan.');
    }

    public function edit(Request $request, AssessmentResult $result): View
    {
        $this->authorizeAdmin($request);

        return view('admin.results.form', compact('result'));
    }

    public function update(Request $request, AssessmentResult $result): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $result->update($this->validatedData($request, $result));

        return redirect()->route('admin.results.index')->with('status', 'Hasil desain berhasil diperbarui.');
    }

    public function destroy(Request $request, AssessmentResult $result): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $result->delete();

        return back()->with('status', 'Hasil desain berhasil dihapus.');
    }

    private function validatedData(Request $request, ?AssessmentResult $result = null): array
    {
        return $request->validate([
            'style_key' => ['required', 'string', 'max:80', 'alpha_dash', 'unique:assessment_results,style_key'.($result ? ','.$result->id : '')],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:600'],
            'image' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:1', 'unique:assessment_results,sort_order'.($result ? ','.$result->id : '')],
        ]);
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'admin', 403);
    }
}
