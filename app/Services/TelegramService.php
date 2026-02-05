<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public static function sendOrder($trx)
    {
        $text =
            "📦 *TRANSAKSI BARU*\n\n" .
            "🧾 Kode: {$trx->code}\n" .
            "👤 Nama: {$trx->receiver_name}\n" .
            "📞 HP: {$trx->receiver_phone}\n" .
            "🏠 Alamat:\n{$trx->receiver_address}\n" .
            "{$trx->shipping_city}, {$trx->shipping_province}\n\n" .
            "💰 Total: Rp " . number_format($trx->total_amount, 0, ',', '.') . "\n\n" .
            "🛒 Produk:\n";

        foreach ($trx->items as $item) {
            $text .= "- {$item->product_name} ({$item->quantity}x)\n";
        }

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '✅ Verifikasi', 'callback_data' => 'verify_' . $trx->id],
                    ['text' => '❌ Tolak', 'callback_data' => 'reject_' . $trx->id],
                ]
            ]
        ];

        try {

            $photoPath = $trx->payment_proof_path
                ? public_path('storage/' . $trx->payment_proof_path)
                : null;

            // 📸 JIKA ADA BUKTI TRANSFER → KIRIM FOTO (PAKAI STREAM)
            if ($photoPath && file_exists($photoPath) && is_readable($photoPath)) {

                $response = Http::attach(
                    'photo',
                    fopen($photoPath, 'r'),
                    basename($photoPath)
                )->post(
                    "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto",
                    [
                        'chat_id' => env('TELEGRAM_CHAT_ID'),
                        'caption' => $text,
                        'parse_mode' => 'Markdown',
                        'reply_markup' => json_encode($keyboard),
                    ]
                );

            } else {

                // 📝 JIKA TIDAK ADA FOTO / TIDAK TERBACA → KIRIM TEXT SAJA
                $response = Http::post(
                    "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage",
                    [
                        'chat_id' => env('TELEGRAM_CHAT_ID'),
                        'text' => $text,
                        'parse_mode' => 'Markdown',
                        'reply_markup' => json_encode($keyboard),
                    ]
                );
            }

            // 🪵 LOG JIKA TELEGRAM GAGAL
            if (!$response->successful()) {
                Log::error('Telegram API Error', [
                    'response' => $response->body(),
                    'transaction_id' => $trx->id,
                ]);
            }

        } catch (\Throwable $e) {
            Log::error('Telegram Service Exception', [
                'message' => $e->getMessage(),
                'transaction_id' => $trx->id,
            ]);
        }
    }
}
