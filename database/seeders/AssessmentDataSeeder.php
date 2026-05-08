<?php

namespace Database\Seeders;

use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use Illuminate\Database\Seeder;

class AssessmentDataSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'question' => 'Kamu lebih suka ruangan yang bagaimana?',
                'options' => ['Simpel dan rapi', 'Hangat dan nyaman', 'Modern dan elegan', 'Berwarna dan ceria'],
                'image' => 'assets/images/img-4.png',
                'intro_title' => 'Mulailah menjawab pertanyaan...',
            ],
            [
                'question' => 'Warna apa yang paling kamu suka untuk ruangan?',
                'options' => ['Putih dan krem', 'Coklat dan kayu', 'Abu-abu dan hitam', 'Biru dan hijau'],
                'image' => 'assets/images/img-5.png',
                'intro_title' => '',
            ],
            [
                'question' => 'Furniture seperti apa yang kamu suka?',
                'options' => ['Kayu natural', 'Sofa empuk besar', 'Metal dan kaca', 'Campuran warna-warni'],
                'image' => 'assets/images/img3.png',
                'intro_title' => 'Terus jawab pertanyaannya...',
            ],
            [
                'question' => 'Bagaimana pencahayaan ideal menurutmu?',
                'options' => ['Cahaya alami dari jendela', 'Lampu hangat redup', 'Lampu terang modern', 'Lampu warna-warni'],
                'image' => 'assets/images/img-container.png',
                'intro_title' => '',
            ],
            [
                'question' => 'Dekorasi apa yang kamu suka di dinding?',
                'options' => ['Minimalis tanpa banyak hiasan', 'Foto keluarga dan kenangan', 'Lukisan abstrak', 'Poster dan seni pop'],
                'image' => 'assets/images/img-4.png',
                'intro_title' => 'Hmmm, seleramu bagus',
            ],
            [
                'question' => 'Lantai seperti apa yang kamu pilih?',
                'options' => ['Kayu natural', 'Karpet tebal', 'Marmer atau granit', 'Vinyl berwarna'],
                'image' => 'assets/images/img-5.png',
                'intro_title' => '',
            ],
            [
                'question' => 'Bagaimana suasana ruangan impianmu?',
                'options' => ['Tenang dan damai', 'Hangat seperti rumah nenek', 'Mewah dan berkelas', 'Seru dan penuh energi'],
                'image' => 'assets/images/img3.png',
                'intro_title' => 'Ayoo, sedikit lagi...',
            ],
            [
                'question' => 'Apa yang paling penting dalam sebuah ruangan?',
                'options' => ['Kerapian', 'Kenyamanan', 'Keindahan', 'Keunikan'],
                'image' => 'assets/images/img-container.png',
                'intro_title' => 'Sedikit lagi...',
            ],
            [
                'question' => 'Apakah kamu menyukai tanaman di dalam ruangan?',
                'options' => ['Ya, sedikit saja', 'Ya, banyak tanaman', 'Tidak terlalu', 'Lebih suka bunga palsu'],
                'image' => 'assets/images/img-4.png',
                'intro_title' => 'Ruanganmu hampir selesai...',
            ],
            [
                'question' => 'Gaya interior mana yang paling menarik?',
                'options' => ['Minimalis', 'Scandinavian', 'Industrial', 'Bohemian'],
                'image' => 'assets/images/img-5.png',
                'intro_title' => 'Hasilnya akan segera muncul...',
            ],
        ];

        foreach ($questions as $index => $question) {
            AssessmentQuestion::firstOrCreate(
                ['sort_order' => $index + 1],
                $question + ['sort_order' => $index + 1],
            );
        }

        $results = [
            [
                'style_key' => 'minimalis',
                'title' => 'Si Kalem Minimalis',
                'description' => 'Kamu cocok terhadap furnitur yang berbahan kayu dan berbentuk simple, dengan warna yang kalem seperti coklat, putih, dan krem. Coba merek seperti Fabelio, Scandinavian, Warjo dsb.',
                'image' => 'assets/images/img-container.png',
            ],
            [
                'style_key' => 'scandinavian',
                'title' => 'Si Hangat Scandinavian',
                'description' => 'Kamu menyukai ruangan yang hangat dan nyaman dengan sentuhan natural, warna lembut, dan furnitur kayu yang membuat ruangan terasa akrab.',
                'image' => 'assets/images/img-Scandinavian.png',
            ],
            [
                'style_key' => 'modern',
                'title' => 'Si Elegan Modern',
                'description' => 'Kamu suka ruangan yang terlihat mewah dan berkelas, dengan bentuk tegas, warna netral, dan detail yang bersih tanpa terlalu ramai.',
                'image' => 'assets/images/img-Modern.png',
            ],
            [
                'style_key' => 'bohemian',
                'title' => 'Si Ceria Bohemian',
                'description' => 'Kamu suka ruangan yang penuh warna dan karakter, dengan dekorasi ekspresif, tekstur berlapis, dan suasana yang bebas serta kreatif.',
                'image' => 'assets/images/img-Bohemian.png',
            ],
        ];

        foreach ($results as $index => $result) {
            AssessmentResult::firstOrCreate(
                ['style_key' => $result['style_key']],
                $result + ['sort_order' => $index + 1],
            );
        }
    }
}
