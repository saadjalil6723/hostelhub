<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', ['admin' => Auth::guard('admin')->user()]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $data = $request->only(['name', 'email']);

        if ($request->filled('new_password')) {
            $data['password'] = Hash::make($request->new_password);
        }

        $admin->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}
