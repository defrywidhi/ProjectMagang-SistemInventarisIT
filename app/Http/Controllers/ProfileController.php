<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Handle upload tanda tangan digital.
     */
    public function uploadTtd(Request $request)
    {
        $request->validate([
            'ttd' => 'required|image|mimes:png|max:2048', // Wajib PNG
        ]);

        $user = $request->user();

        if ($request->hasFile('ttd')) {
            // 1. Tentukan Nama File yang Rapi (ttd_userid.png)
            // Contoh hasil: tanda_tangan/ttd_user_1.png
            $filename = 'ttd_user_' . $user->id . '.png';
            
            // 2. Simpan (otomatis menimpa file lama jika namanya sama)
            // 'tanda_tangan' adalah nama foldernya
            $path = $request->file('ttd')->storeAs('tanda_tangan', $filename, 'public');

            // 3. Update database
            // Simpan path lengkapnya: tanda_tangan/ttd_user_1.png
            $user->ttd = $path;
            $user->save();

            return back()->with('status', 'ttd-updated')->with('success', 'Tanda tangan berhasil diupload!');
        }

        return back()->with('error', 'Gagal mengupload file.');
    }
}
