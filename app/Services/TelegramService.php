<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public static function sendOrder($trx)
{
    $text =
        "📦 TRANSAKSI BARU\n\n" .
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

    $keyboard = json_encode([
        'inline_keyboard' => [
            [
                [
                    'text' => '✅ Verifikasi',
                    'callback_data' => 'verify_' . $trx->id
                ],
                [
                    'text' => '❌ Tolak',
                    'callback_data' => 'reject_' . $trx->id
                ]
            ]
        ]
    ]);

    try {

        $photoUrl = "https://daengrubik.my.id/storage/" . $trx->payment_proof_path;

        $response = Http::asForm()->post(
            "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendPhoto",
            [
                'chat_id' => env('TELEGRAM_CHAT_ID'),
                'photo' => $photoUrl,
                'caption' => $text,
                'reply_markup' => $keyboard,
            ]
        );

        if (!$response->successful()) {

            Log::error('Telegram sendPhoto failed, fallback to sendMessage', [
                'response' => $response->body()
            ]);

            // fallback ke text
            Http::asForm()->post(
                "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage",
                [
                    'chat_id' => env('TELEGRAM_CHAT_ID'),
                    'text' => $text,
                    'reply_markup' => $keyboard,
                ]
            );
        }

    } catch (\Throwable $e) {

        Log::error('Telegram Exception', [
            'message' => $e->getMessage(),
        ]);
    }
}
}