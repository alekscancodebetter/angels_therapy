<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminSetupRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminSetupController extends Controller
{
    public function create(): View
    {
        abort_if(User::query()->where('is_admin', true)->exists(), 404);

        return view('admin.auth.setup');
    }

    public function store(AdminSetupRequest $request): RedirectResponse
    {
        abort_if(User::query()->where('is_admin', true)->exists(), 404);

        $admin = User::create([
            'name' => $request->string('name')->trim()->toString(),
            'email' => Str::lower($request->string('email')->trim()->toString()),
            'password' => $request->string('password')->toString(),
            'is_admin' => true,
        ]);

        Auth::login($admin);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')->with('status', 'Your secure admin account is ready.');
    }
}
