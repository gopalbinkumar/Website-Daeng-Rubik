<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CheckoutController extends Controller
{
    // ===================== HALAMAN CHECKOUT =====================
    public function index(Request $request)
    {
        $user = Auth::user();

        // ambil cart user
        $cart = Cart::with(['items.product'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart) {
            return redirect()->route('cart');
        }

        // ambil item ID dari query ?items=6,7
        $itemIds = collect(explode(',', $request->query('items')))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->toArray();

        if (empty($itemIds)) {
            return redirect()
                ->route('cart')
                ->with('error', 'Pilih minimal 1 produk untuk checkout');
        }

        $checkoutItems = $cart->items
            ->whereIn('id', $itemIds)
            ->values();

        $subtotal = $checkoutItems->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });

        $ongkir = 15000;
        $total  = $subtotal + $ongkir;

        return view('pages.checkout', compact(
            'user',
            'cart',
            'checkoutItems',
            'subtotal',
            'ongkir',
            'total'
        ));
    }

    // ===================== PROSES CHECKOUT =====================
    public function store(Request $request)
    {
        // 🔐 VALIDASI LARAVEL SAJA (TANPA UBAH UI)
        $validated = $request->validate([
            'items' => 'required|string',

            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_address' => 'required|string',
            'receiver_postal_code' => 'required|string|max:10',

            'shipping_zone' => 'required|in:makassar,sulsel,luar_provinsi',

            'shipping_city' => 'required_if:shipping_zone,sulsel,luar_provinsi',
            'shipping_province' => 'required_if:shipping_zone,luar_provinsi',

            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'receiver_name.required' => 'Nama penerima wajib diisi',
            'receiver_phone.required' => 'Nomor WhatsApp wajib diisi',
            'receiver_address.required' => 'Alamat wajib diisi',
            'receiver_postal_code.required' => 'Kode pos wajib diisi',

            'shipping_zone.required' => 'Lokasi pengiriman wajib dipilih',
            'shipping_city.required_if' => 'Nama kota/kabupaten wajib diisi',
            'shipping_province.required_if' => 'Nama provinsi wajib diisi',

            'payment_proof.required' => 'Bukti pembayaran wajib diupload',
            'payment_proof.image' => 'Bukti pembayaran harus berupa gambar',
            'payment_proof.max' => 'Ukuran bukti pembayaran maksimal 2MB',
        ]);

        // ===================== JIKA LOLOS VALIDASI =====================
        // Simpan bukti pembayaran (contoh minimal)
        $paymentPath = $request->file('payment_proof')
            ->store('payment_proofs', 'public');

        // (di sini kamu bisa lanjut simpan transaksi, item, dll)

        return response()->json([
            'status' => 'success',
            'message' => 'Checkout berhasil',
        ], 200);
    }
}
