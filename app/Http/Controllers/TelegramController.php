<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderVerifiedMail;
use App\Mail\OrderRejectedMail;

class TelegramController extends Controller
{
    public function handle(Request $request)
    {
        $callback = $request->callback_query;
        if (!$callback) return response()->json(['ok' => true]);

        [$action, $orderId] = explode('_', $callback['data']);
        $order = Order::with('user')->findOrFail($orderId);

        if ($action === 'verify') {
            $order->update(['status' => 'paid']);

            Mail::to($order->user->email_222111)
                ->send(new OrderVerifiedMail($order));
        }

        if ($action === 'reject') {
            $order->update(['status' => 'rejected']);

            Mail::to($order->user->email_222111)
                ->send(new OrderRejectedMail($order));
        }

        return response()->json(['ok' => true]);
    }
}
