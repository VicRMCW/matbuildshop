<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        // Hanya memproses instance user yang terautentikasi untuk mencegah modifikasi profil user lain.
        $user = $request->user();

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'address'          => ['nullable', 'string'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password'         => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name    = $validated['name'];
        $user->phone   = $validated['phone'];
        $user->address = $validated['address'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Profil berhasil diperbarui.');
    }
}
