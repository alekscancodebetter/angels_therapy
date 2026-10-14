<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings');
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $data = Arr::only($request->validated(), ['name', 'email']);
        $data['email'] = Str::lower($request->string('email')->trim()->toString());

        if ($request->filled('password')) {
            $data['password'] = $request->string('password')->toString();
        }

        $request->user()->update($data);

        return back()->with('status', 'Admin settings updated.');
    }
}
