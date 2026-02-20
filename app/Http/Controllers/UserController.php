<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetCodeMail;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // tampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // proses register

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'whatsapp' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        $existingUser = User::where('email', $request->email)
            ->orWhere('whatsapp', $request->whatsapp)
            ->first();

        if ($existingUser) {

            if ($existingUser->email === $request->email) {
                return back()->withInput()->with('error', 'Email sudah terdaftar');
            }

            if ($existingUser->whatsapp === $request->whatsapp) {
                return back()->withInput()->with('error', 'Nomor WhatsApp sudah terdaftar');
            }
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('auth.login')
            ->with('success', 'Registrasi berhasil, silakan login');
    }


    //login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $redirect = Auth::user()->role === 'admin'
                ? route('admin.dashboard')
                : route('home');

            return redirect($redirect)
                ->with('success', 'Login berhasil.');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email tidak terdaftar.'
        ]);

        $user = User::where('email', $request->email)->first();

        $code = (string) rand(100000, 999999);

        $user->update([
            'reset_code' => $code,
            'reset_code_expires_at' => now()->addMinutes(10)
        ]);

        Mail::to($user->email)->send(new ResetCodeMail($code));

        return redirect()->route('auth.forgot')
            ->with('step', 'otp')
            ->with('email', $user->email)
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function checkResetCode(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email',
                'code' => 'required'
            ]);

        } catch (ValidationException $e) {

            return redirect()->route('auth.forgot')
                ->with('step', 'otp')
                ->with('email', $request->email)
                ->withErrors($e->validator)
                ->withInput();
        }

        $codeInput = trim($request->code);

        $user = User::where('email', $request->email)->first();

        if (
            !$user ||
            $user->reset_code !== $codeInput ||
            now()->greaterThan($user->reset_code_expires_at)
        ) {
            return redirect()->route('auth.forgot')
                ->with('step', 'otp')
                ->with('email', $request->email)
                ->with('error', 'Kode tidak valid atau sudah kadaluarsa.');
        }

        session(['otp_verified' => true]);

        return redirect()->route('auth.forgot')
            ->with('step', 'password')
            ->with('email', $request->email)
            ->with('success', 'Kode berhasil diverifikasi.');
    }

    public function updateForgotPassword(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed'
            ]);

        } catch (ValidationException $e) {

            return redirect()->route('auth.forgot')
                ->with('step', 'password')
                ->with('email', $request->email)
                ->withErrors($e->validator)
                ->withInput();
        }

        if (!session('otp_verified')) {
            return redirect()->route('auth.forgot')
                ->with('error', 'Silakan verifikasi kode terlebih dahulu.');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('auth.forgot')
                ->with('error', 'Terjadi kesalahan.');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'reset_code' => null,
            'reset_code_expires_at' => null
        ]);

        session()->forget(['otp_verified', 'step', 'email']);

        return redirect()->route('auth.login')
            ->with('success', 'Password berhasil diperbarui. Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function index(Request $request)
    {
        $query = User::query();

        $role = $request->role ?? 'user';

        if ($role !== 'Semua') {

            // Filter role tertentu
            $query->where('role', $role)
                ->orderBy('name', 'asc');

        } else {

            // Jika pilih Semua:
            // user dulu, lalu admin
            $query->orderByRaw("
            CASE 
                WHEN role = 'user' THEN 1
                WHEN role = 'admin' THEN 2
            END
        ")
                ->orderBy('name', 'asc');
        }

        $users = $query->paginate(10)
            ->withQueryString();

        return view('admin.admins.index', compact('users'));
    }

    // =====================
// HALAMAN PROFIL
// =====================
    public function profile()
    {
        return view('pages.my-profile');
    }

    // =====================
// UPDATE PROFIL
// =====================
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'whatsapp' => 'nullable|string|max:20',
        ]);

        Auth::user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // =====================
// UPDATE PASSWORD
// =====================
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }


}
