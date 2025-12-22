<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PasswordRequestController extends Controller
{
    // Tampilkan Form
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // Proses Kirim Email ke Admin
    public function sendResetRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Cari user yang lupa password
        $user = User::where('email', $request->email)->first();

        // Kirim Email ke ADMIN
        // (Anggap email admin utama adalah admin@rumahsakit.com atau kita ambil user role admin pertama)
        $adminEmail = 'admin@rumahsakit.com'; // Ganti atau ambil dinamis dari DB

        $data = ['user' => $user];

        Mail::send('emails.admin-reset-request', $data, function($message) use ($adminEmail) {
            $message->to($adminEmail)
                    ->subject('PERMINTAAN RESET PASSWORD DARI: ' . $adminEmail);
        });

        return back()->with('status', 'Permintaan reset password telah dikirim ke Admin. Mohon tunggu informasi selanjutnya via email.');
    }
}