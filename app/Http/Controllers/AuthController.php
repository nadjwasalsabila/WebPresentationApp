<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // URL base API eksternal
    private string $apiBase = 'https://jwt-auth-eight-neon.vercel.app';

    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        // Kalau sudah login, langsung redirect ke dashboard
        if (session()->has('refreshToken')) {
            return redirect()->route('tutorials.index');
        }

        return view('auth.login');
    }

    /**
     * Proses login — kirim email + password ke API eksternal
     */
    public function login(Request $request)
    {
        // Validasi input form dulu
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:4',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        try {
            // Kirim POST request ke API eksternal
            $response = Http::timeout(10)->post("{$this->apiBase}/login", [
                'email'    => $request->email,
                'password' => $request->password,
            ]);

            // Cek apakah request berhasil (HTTP 200)
            if ($response->successful()) {
                $data = $response->json();

                // Ambil refreshToken dari response API
                // Sesuaikan key ini dengan response Postman yang kamu lihat
                $refreshToken = $data['refreshToken']
                    ?? $data['token']
                    ?? $data['data']['refreshToken']
                    ?? null;

                if (!$refreshToken) {
                    return back()
                        ->withInput()
                        ->with('error', 'Login gagal: token tidak diterima dari server.');
                }

                // Simpan token & info user di session
                session([
                    'refreshToken' => $refreshToken,
                    'user_email'   => $request->email,
                ]);

                return redirect()->route('tutorials.index')
                    ->with('success', 'Login berhasil! Selamat datang.');

            } else {
                // API mengembalikan error (401, 400, dll)
                $errorMsg = $response->json('message')
                    ?? $response->json('error')
                    ?? 'Email atau password salah.';

                return back()
                    ->withInput()
                    ->with('error', $errorMsg);
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Gagal konek ke API (timeout, network error)
            return back()
                ->withInput()
                ->with('error', 'Tidak dapat terhubung ke server autentikasi. Coba lagi.');
        }
    }

    /**
     * Proses logout — panggil API eksternal lalu hapus session
     */
    public function logout(Request $request)
    {
        $refreshToken = session('refreshToken');

        if ($refreshToken) {
            try {
                // Kirim request logout ke API eksternal (sertakan refreshToken)
                Http::timeout(10)->post("{$this->apiBase}/logout", [
                    'refreshToken' => $refreshToken,
                ]);
                // Abaikan response — kita tetap logout lokal walau API error
            } catch (\Exception $e) {
                // Tetap lanjut logout lokal walau API tidak merespons
            }
        }

        // Hapus semua data session
        session()->flush();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}