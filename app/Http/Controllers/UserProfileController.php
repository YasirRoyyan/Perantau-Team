<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show($username)
    {
        // Mencari user berdasarkan nama yang unik di database
        $user = User::where('name', $username)->firstOrFail();

        return view('pages.user-profile', compact('user'));
    }
}