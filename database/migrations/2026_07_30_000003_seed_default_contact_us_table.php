<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('contact_us')->exists()) {
            return;
        }

        DB::table('contact_us')->insert([
            'address' => 'Jalan Pondok Mawa, Tombolo, Somba Opu (BTN Pao-Pao Permai Blok H2 No. 12) SOMBA OPU (UPU), KAB. GOWA, SULAWESI SELATAN, ID 92114',
            'phone' => '+62 812-3456-7890',
            'whatsapp_number' => '+62 819-1462-9111',
            'whatsapp_url' => 'https://wa.me/6281914629111',
            'email' => 'celebescubers@gmail.com',
            'instagram_url' => 'https://www.instagram.com/daengrubik',
            'facebook_url' => null,
            'youtube_url' => 'https://www.youtube.com/@daengrubik',
            'tiktok_url' => 'https://www.tiktok.com/@daeng_rubik',
            'latitude' => '-5.1234560',
            'longitude' => '119.1234560',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('contact_us')
            ->where('email', 'celebescubers@gmail.com')
            ->where('whatsapp_url', 'https://wa.me/6281914629111')
            ->delete();
    }
};
