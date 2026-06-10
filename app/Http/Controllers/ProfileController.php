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
            'name' => ['required', 'string', 'max:25', 'regex:/^[A-Za-z0-9]+$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\+?[0-9]+$/'],
            'city' => ['nullable', 'string', 'max:80', 'regex:/^[A-Za-z\s]+$/'],
            'bio' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'confirmed', Password::min(8), 'max:25'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], 
        ], [
            'name.regex' => 'Nama hanya boleh berisi huruf dan angka tanpa spasi atau simbol.',
            'name.max' => 'Nama maksimal 25 karakter.',
            'email.email' => 'Email harus menggunakan format email yang valid.',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka dan kode negara di awal, contoh +628123456789.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'city.regex' => 'Kota hanya boleh berisi huruf dan spasi.',
            'city.max' => 'Nama kota maksimal 80 karakter.',
            'bio.max' => 'Bio maksimal 50 karakter.',
            'password.max' => 'Password baru maksimal 25 karakter.',
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
