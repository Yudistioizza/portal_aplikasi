<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password with history and expiry validation.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        // Check last 5 password history
        $previousPasswords = PasswordHistory::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->pluck('password');

        foreach ($previousPasswords as $oldPassword) {
            if (Hash::check($validated['password'], $oldPassword)) {
                return back()->withErrors([
                    'password' => 'Anda tidak boleh memakai kembali 5 kata sandi terakhir.'
                ]);
            }
        }

        // Update password and reset password_changed_at
        $user->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(), // mark password as updated now
        ]);

        // Store password in history
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => Hash::make($validated['password']),
        ]);

        // OPTIONAL: Keep only the last 5 histories (auto-cleanup)
        PasswordHistory::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->skip(5)
            ->take(PHP_INT_MAX)
            ->delete();

        return back()->with('status', 'password-updated');
    }
}