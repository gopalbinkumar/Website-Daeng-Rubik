<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Mail\OrderVerifiedMail;
use App\Mail\OrderRejectedMail;

class TelegramController extends Controller
{
    public function handle(Request $request)
    {
        try {

            Log::info('TELEGRAM CALLBACK', $request->all());

            $callback = $request->callback_query;

            if (!$callback) {
                return response()->json(['ok' => true]);
            }

            $data = $callback['data'] ?? null;
            $chatId = $callback['message']['chat']['id'] ?? null;
            $messageId = $callback['message']['message_id'] ?? null;
            $callbackId = $callback['id'] ?? null;
            $originalCaption = $callback['message']['caption'] ?? '';

            if (!$data) {
                return response()->json(['ok' => true]);
            }

            [$action, $trxId] = explode('_', $data);

            $transaction = Transaction::with('user')->find($trxId);

            if (!$transaction) {
                return response()->json(['ok' => true]);
            }

            // =====================================
            // VERIFIKASI
            // =====================================
            if ($action === 'verify') {

                if ($transaction->status !== 'paid') {

                    // 🔥 PAKAI METHOD MODEL (stok otomatis berkurang)
                    $transaction->markAsPaid();

                    if ($transaction->user && $transaction->user->email_222111) {
                        Mail::to($transaction->user->email_222111)
                            ->send(new OrderVerifiedMail($transaction));
                    }
                }

                $statusText = "✅ STATUS: PAID";
                $popupText  = "Transaksi {$transaction->code} berhasil diverifikasi";
            }

            // =====================================
            // TOLAK
            // =====================================
            elseif ($action === 'reject') {

                if ($transaction->status !== 'failed') {

                    // 🔥 PAKAI METHOD MODEL (stok otomatis kembali jika perlu)
                    $transaction->markAsFailed();

                    if ($transaction->user && $transaction->user->email_222111) {
                        Mail::to($transaction->user->email_222111)
                            ->send(new OrderRejectedMail($transaction));
                    }
                }

                $statusText = "❌ STATUS: FAILED";
                $popupText  = "Transaksi {$transaction->code} berhasil ditolak";
            }

            else {
                return response()->json(['ok' => true]);
            }

            // =====================================
            // 1️⃣ Hilangkan Loading Button
            // =====================================
            if ($callbackId) {
                Http::post(
                    "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/answerCallbackQuery",
                    [
                        'callback_query_id' => $callbackId,
                        'text' => $popupText,
                        'show_alert' => false
                    ]
                );
            }

            // =====================================
            // 2️⃣ Edit Caption Lama
            // =====================================
            if ($chatId && $messageId) {
                Http::post(
                    "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/editMessageCaption",
                    [
                        'chat_id' => $chatId,
                        'message_id' => $messageId,
                        'caption' => $originalCaption . "\n\n" . $statusText,
                    ]
                );
            }

            // =====================================
            // 3️⃣ Kirim Pesan Tambahan
            // =====================================
            if ($chatId) {
                Http::post(
                    "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage",
                    [
                        'chat_id' => $chatId,
                        'text' => $popupText
                    ]
                );
            }

            return response()->json(['ok' => true]);

        } catch (\Throwable $e) {

            Log::error('TELEGRAM ERROR', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            // WAJIB 200 agar Telegram tidak retry terus
            return response()->json(['ok' => true]);
        }
    }
}