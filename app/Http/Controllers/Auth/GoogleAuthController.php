<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        $state = Str::random(40);
        $nonce = Str::random(40);

        session([
            'google_oauth_state' => $state,
            'google_oauth_nonce' => $nonce,
        ]);

        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'state' => $state,
            'nonce' => $nonce,
            'prompt' => 'select_account',
            'access_type' => 'online',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    public function callback()
    {
        try {
            if (request('error')) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Login Google dibatalkan.');
            }

            if (!request('code')) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Kode login Google tidak ditemukan.');
            }

            if (request('state') !== session('google_oauth_state')) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'State Google tidak valid.');
            }

            $nonce = session('google_oauth_nonce');

            session()->forget([
                'google_oauth_state',
                'google_oauth_nonce',
            ]);

            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => request('code'),
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => config('services.google.redirect'),
                'grant_type' => 'authorization_code',
            ]);

            if (!$tokenResponse->successful()) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Gagal mengambil token Google.');
            }

            $token = $tokenResponse->json();

            if (empty($token['id_token'])) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'ID token Google tidak ditemukan.');
            }

            $googleUser = $this->verifyGoogleIdToken($token['id_token'], $nonce);

            if (!$googleUser) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'ID token Google tidak valid.');
            }

            if (empty($googleUser['sub']) || empty($googleUser['email'])) {
                return redirect()
                    ->route('auth.login')
                    ->with('error', 'Data akun Google tidak lengkap.');
            }

            $user = User::where('google_id', $googleUser['sub'])->first();

            if (!$user) {
                $user = User::where('email', $googleUser['email'])->first();

                if ($user) {
                    $user->update([
                        'google_id' => $googleUser['sub'],
                        'avatar' => $googleUser['picture'] ?? null,
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);
                } else {
                    $user = User::create([
                        'google_id' => $googleUser['sub'],
                        'name' => $googleUser['name'] ?? $googleUser['email'],
                        'email' => $googleUser['email'],
                        'avatar' => $googleUser['picture'] ?? null,
                        'email_verified_at' => !empty($googleUser['email_verified']) ? now() : null,
                        'password' => null,
                        'whatsapp' => null,
                        'birthdate' => null,
                        'role' => 'user',
                    ]);
                }
            }

            Auth::login($user, true);

            if (empty($user->whatsapp) || empty($user->birthdate)) {
                return redirect()
                    ->route('profile')
                    ->with('success', 'Login berhasil. Silakan lengkapi profil terlebih dahulu.');
            }

            return redirect()
                ->route('home')
                ->with('success', 'Login dengan Google berhasil.');
        } catch (Throwable $e) {
            return redirect()
                ->route('auth.login')
                ->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }
    }

    private function verifyGoogleIdToken(string $idToken, ?string $nonce): ?array
    {
        $parts = explode('.', $idToken);

        if (count($parts) !== 3) {
            return null;
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;

        $header = json_decode($this->base64UrlDecode($encodedHeader), true);
        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);
        $signature = $this->base64UrlDecode($encodedSignature);

        if (!$header || !$payload || $signature === false) {
            return null;
        }

        if (($header['alg'] ?? null) !== 'RS256') {
            return null;
        }

        if (empty($header['kid'])) {
            return null;
        }

        $publicKey = $this->getGooglePublicKey($header['kid']);

        if (!$publicKey) {
            return null;
        }

        $signedContent = $encodedHeader . '.' . $encodedPayload;

        $verified = openssl_verify(
            $signedContent,
            $signature,
            $publicKey,
            OPENSSL_ALGO_SHA256
        );

        if ($verified !== 1) {
            return null;
        }

        if (($payload['aud'] ?? null) !== config('services.google.client_id')) {
            return null;
        }

        if (
            !in_array(($payload['iss'] ?? null), [
                'accounts.google.com',
                'https://accounts.google.com',
            ], true)
        ) {
            return null;
        }

        if (empty($payload['exp']) || time() >= (int) $payload['exp']) {
            return null;
        }

        if ($nonce && (($payload['nonce'] ?? null) !== $nonce)) {
            return null;
        }

        return $payload;
    }

    private function getGooglePublicKey(string $kid): ?string
    {
        $certs = Cache::remember('google_oidc_pem_certs', now()->addHours(6), function () {
            $response = Http::get('https://www.googleapis.com/oauth2/v1/certs');

            if (!$response->successful()) {
                return [];
            }

            return $response->json() ?? [];
        });

        if (!empty($certs[$kid])) {
            return $certs[$kid];
        }

        Cache::forget('google_oidc_pem_certs');

        $response = Http::get('https://www.googleapis.com/oauth2/v1/certs');

        if (!$response->successful()) {
            return null;
        }

        $freshCerts = $response->json() ?? [];

        Cache::put('google_oidc_pem_certs', $freshCerts, now()->addHours(6));

        return $freshCerts[$kid] ?? null;
    }

    private function base64UrlDecode(string $value): string|false
    {
        $remainder = strlen($value) % 4;

        if ($remainder) {
            $value .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($value, '-_', '+/'));
    }
}