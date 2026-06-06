<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use App\Models\SiteContent;
use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        SiteContent::firstOrCreate(['key' => 'home'], [
            'payload' => [
                'hero' => [
                    'title' => 'Kenali selera desain ruangan kamu di Interiology',
                    'description' => 'Jawab beberapa pertanyaan singkat dan lihat rekomendasi ruang tamu yang sesuai dengan kepribadianmu.',
                    'button' => 'Cari Selera mu!',
                ],
                'workflow_title' => 'Bagaimana cara untuk menentukan selera mu?',
                'workflow_steps' => [
                    ['class' => 'step-card-1', 'icon' => 'assets/icons/icon-sofa.png', 'alt' => 'Sofa', 'label' => 'Mulai Asesmen'],
                    ['class' => 'step-card-2', 'icon' => 'assets/icons/icon-table.png', 'alt' => 'Nightstand', 'label' => 'Jawab beberapa pertanyaan'],
                    ['class' => 'step-card-3', 'icon' => 'assets/icons/icon-tv.png', 'alt' => 'Television', 'label' => 'Tampilan Interior Muncul Sesuai Jawaban'],
                    ['class' => 'step-card-4', 'icon' => 'assets/icons/icon-verified.png', 'alt' => 'Check', 'label' => 'Selamat Seleramu berhasil ditemukan!'],
                ],
                'showcase' => ['image' => 'assets/images/img-container.png', 'alt' => 'Contoh Ruangan'],
                'custom_room_cta' => ['title' => 'Tertarik untuk membuat ruangan sendiri?', 'button' => 'Mulai Sekarang!'],
                'gallery_images' => [
                    ['image' => 'assets/images/img3-bw.png', 'alt' => 'Inspirasi 1'],
                    ['image' => 'assets/images/img-5-bw.png', 'alt' => 'Inspirasi 2'],
                    ['image' => 'assets/images/img-4-bw.png', 'alt' => 'Inspirasi 3'],
                ],
                'footer' => [
                    'title' => 'Tentang Interiology',
                    'description' => 'Website ini dirancang untuk membantu kamu menemukan gaya ruang tamu yang paling sesuai dengan kepribadian dan preferensimu. Melalui asesmen interaktif dan visualisasi sederhana, kami berharap kamu bisa lebih percaya diri dalam menentukan pilihan desain interior.',
                    'location' => 'Bandung, Jawa Barat, Indonesia',
                ],
            ],
        ]);

        $navigationItems = [
            ['label' => 'Beranda', 'route_name' => 'home', 'anchor' => null, 'external_url' => null, 'auth_state' => 'all', 'is_cta' => false],
            ['label' => 'Cara Kerja', 'route_name' => 'home', 'anchor' => 'cara-kerja', 'external_url' => null, 'auth_state' => 'all', 'is_cta' => false],
            ['label' => 'Kustom Ruangan', 'route_name' => 'custom-room', 'anchor' => null, 'external_url' => null, 'auth_state' => 'all', 'is_cta' => false],
            ['label' => 'Cari Selera mu!', 'route_name' => 'prepare', 'anchor' => null, 'external_url' => null, 'auth_state' => 'all', 'is_cta' => true],
            ['label' => 'Interiorgram', 'route_name' => 'home', 'anchor' => 'interiorgram', 'external_url' => null, 'auth_state' => 'all', 'is_cta' => false],
            ['label' => 'Login', 'route_name' => 'login', 'anchor' => null, 'external_url' => null, 'auth_state' => 'guest', 'is_cta' => false],
            ['label' => 'Dashboard', 'route_name' => 'dashboard', 'anchor' => null, 'external_url' => null, 'auth_state' => 'auth', 'is_cta' => false],
        ];

        foreach ($navigationItems as $index => $item) {
            NavigationItem::updateOrCreate(
                ['sort_order' => $index + 1],
                $item + ['sort_order' => $index + 1, 'is_active' => true],
            );
        }

        $socialLinks = [
            ['label' => 'WhatsApp', 'url' => '#', 'icon' => 'assets/icons/wa.png'],
            ['label' => 'Instagram', 'url' => '#', 'icon' => 'assets/icons/ig.png'],
        ];

        foreach ($socialLinks as $index => $link) {
            SocialLink::firstOrCreate(
                ['sort_order' => $index + 1],
                $link + ['sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }
}
