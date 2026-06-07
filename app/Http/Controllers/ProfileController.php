<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage; 

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('pages.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {

        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:80'],
            'bio' => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], 
        ]);

        if (blank($validated['password'])) {
            unset($validated['password']);
        }

        // Upload gambar
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Menyimpan file fisik ke folder storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // Masukkan nama path ke array validated agar ikut ter-update
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return back()->with('status', 'Profil berhasil diperbarui.');
    }
}