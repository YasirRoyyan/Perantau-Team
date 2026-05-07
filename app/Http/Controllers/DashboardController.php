<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $adminData = null;

        if ($user->role === 'admin') {
            $adminData = [
                'totalUsers' => User::count(),
                'totalAdmins' => User::where('role', 'admin')->count(),
                'totalRegularUsers' => User::where('role', 'user')->count(),
                'latestUsers' => User::latest()->take(5)->get(),
            ];
        }

        return view('pages.dashboard', [
            'adminData' => $adminData,
            'user' => $user,
        ]);
    }
}
