<?php

namespace App\Helpers;

class DummyData
{
    public static function rupiah(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.');
    }

    public static function featuredProducts(): array
    {
        return [
            ['name' => 'Rubik 3x3 Speed Cube', 'price' => 50000, 'badge' => ['hot', 'Bestseller'], 'stars' => 5],
            ['name' => 'Rubik 4x4 Magnetic', 'price' => 85000, 'badge' => ['new', 'New'], 'stars' => 5],
            ['name' => 'Rubik 5x5 Smooth Turn', 'price' => 120000, 'badge' => ['muted', 'Favorit'], 'stars' => 4],
            ['name' => 'Megaminx Pro', 'price' => 150000, 'badge' => ['hot', 'Hot'], 'stars' => 5],
        ];
    }

    public static function products(): array
    {
        return [
            ['name' => 'Rubik 3x3 Budget', 'price' => 35000, 'stars' => 4, 'badge' => ['muted', 'Value']],
            ['name' => 'Rubik 3x3 Magnetic', 'price' => 70000, 'stars' => 5, 'badge' => ['hot', 'Bestseller']],
            ['name' => 'Rubik 4x4 Beginner', 'price' => 65000, 'stars' => 4, 'badge' => ['muted', 'Starter']],
            ['name' => 'Rubik 4x4 Pro', 'price' => 110000, 'stars' => 5, 'badge' => ['new', 'New']],
            ['name' => 'Rubik 5x5', 'price' => 125000, 'stars' => 4, 'badge' => ['muted', 'Smooth']],
            ['name' => 'Pyraminx', 'price' => 60000, 'stars' => 5, 'badge' => ['muted', 'Fun']],
            ['name' => 'Skewb', 'price' => 75000, 'stars' => 4, 'badge' => ['muted', 'Tricky']],
            ['name' => 'Megaminx', 'price' => 165000, 'stars' => 5, 'badge' => ['hot', 'Hot']],
            ['name' => 'Rubik 2x2 Speed', 'price' => 45000, 'stars' => 5, 'badge' => ['muted', 'Compact']],
            ['name' => 'Rubik 6x6', 'price' => 180000, 'stars' => 4, 'badge' => ['muted', 'Challenge']],
            ['name' => 'Square-1', 'price' => 90000, 'stars' => 4, 'badge' => ['muted', 'Unique']],
            ['name' => 'Clock Puzzle', 'price' => 55000, 'stars' => 4, 'badge' => ['muted', 'Classic']],
        ];
    }

    public static function events(): array
    {
        return [
            [
                'title' => 'Kompetisi Rubik Nasional',
                'date' => '15 Feb 2026 • 08:00 WIB',
                'location' => 'Jakarta Convention Center',
                'status' => 'Upcoming',
                'badge' => ['hot', 'Featured'],
                'desc' => 'Kompetisi rubik tingkat nasional dengan berbagai kategori lomba dan hadiah menarik.',
            ],
            [
                'title' => 'Workshop Basic 3x3',
                'date' => '20 Feb 2026 • 13:00 WIB',
                'location' => 'Bandung',
                'status' => 'Upcoming',
                'badge' => ['ok', 'Workshop'],
                'desc' => 'Belajar dasar rubik 3x3 dari nol, cocok untuk pemula.',
            ],
            [
                'title' => 'Speedcubing Meetup',
                'date' => '25 Feb 2026 • 16:00 WIB',
                'location' => 'Surabaya',
                'status' => 'Upcoming',
                'badge' => ['muted', 'Meetup'],
                'desc' => 'Meetup komunitas, sharing teknik, dan mini challenge.',
            ],
            [
                'title' => 'Rubik Fun Competition',
                'date' => '1 Mar 2026 • 09:00 WIB',
                'location' => 'Yogyakarta',
                'status' => 'Upcoming',
                'badge' => ['warn', 'Competition'],
                'desc' => 'Kompetisi santai untuk semua level, seru dan ramah pemula.',
            ],
            [
                'title' => 'Advanced F2L Workshop',
                'date' => '5 Mar 2026 • 14:00 WIB',
                'location' => 'Jakarta',
                'status' => 'Upcoming',
                'badge' => ['ok', 'Workshop'],
                'desc' => 'Workshop khusus teknik F2L untuk level intermediate ke advanced.',
            ],
            [
                'title' => 'Regional Championship',
                'date' => '12 Mar 2026 • 07:00 WIB',
                'location' => 'Bali',
                'status' => 'Upcoming',
                'badge' => ['hot', 'Championship'],
                'desc' => 'Kejuaraan regional dengan sistem ranking resmi.',
            ],
        ];
    }

    public static function tutorials(): array
    {
        return [
            'basic' => [
                ['title' => 'Pengenalan Rubik 3x3', 'duration' => '5:30', 'views' => '1.2K', 'stars' => 5],
                ['title' => 'Notasi Rubik (Singkat & Jelas)', 'duration' => '8:15', 'views' => '2.5K', 'stars' => 5],
                ['title' => 'Cross Putih', 'duration' => '12:45', 'views' => '3.8K', 'stars' => 4],
                ['title' => 'First Layer (F2L Dasar)', 'duration' => '14:10', 'views' => '1.1K', 'stars' => 4],
                ['title' => 'Second Layer Edges', 'duration' => '10:20', 'views' => '950', 'stars' => 4],
                ['title' => 'Yellow Cross (OLL Dasar)', 'duration' => '11:30', 'views' => '1.8K', 'stars' => 5],
            ],
            'intermediate' => [
                ['title' => 'F2L Intermediate', 'duration' => '18:20', 'views' => '980', 'stars' => 5],
                ['title' => 'OLL Pengenalan', 'duration' => '16:05', 'views' => '1.6K', 'stars' => 4],
                ['title' => 'PLL Dasar', 'duration' => '19:40', 'views' => '1.3K', 'stars' => 5],
                ['title' => 'Finger Tricks & TPS', 'duration' => '15:50', 'views' => '1.1K', 'stars' => 5],
                ['title' => 'Cross Optimization', 'duration' => '13:25', 'views' => '890', 'stars' => 4],
            ],
            'advanced' => [
                ['title' => 'Full OLL Strategy', 'duration' => '24:10', 'views' => '720', 'stars' => 5],
                ['title' => 'Full PLL Drills', 'duration' => '22:55', 'views' => '640', 'stars' => 5],
                ['title' => 'Lookahead & TPS', 'duration' => '20:30', 'views' => '510', 'stars' => 4],
                ['title' => 'Advanced F2L Cases', 'duration' => '26:15', 'views' => '420', 'stars' => 5],
                ['title' => 'Competition Strategies', 'duration' => '18:40', 'views' => '380', 'stars' => 5],
            ],
        ];
    }
}
