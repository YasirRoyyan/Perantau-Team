<?php

namespace Database\Seeders;

use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentDataSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'question' => 'Nuansa seperti apa yang paling kamu suka untuk ruangan impianmu?',
                'options' => [
                    'Terang, rapi, dan natural',
                    'Hangat, santai, dan bebas',
                    'Tenang, lembut, dan seimbang',
                    'Bersih, simpel, dan lapang',
                    'Tegas, modern, dan elegan',
                    'Gelap, kuat, dan berkarakter',
                ],
                'image' => 'assets/images/img-4.png',
                'intro_title' => 'Mulailah menjawab pertanyaan...',
                'sort_order' => 1,
            ],
            [
                'question' => 'Warna dominan apa yang paling kamu pilih?',
                'options' => [
                    'Putih, krem, dan kayu terang',
                    'Earth tone dan warna hangat',
                    'Beige lembut dan abu natural',
                    'Putih polos dan netral bersih',
                    'Abu, hitam, dan aksen metal',
                    'Hitam pekat dan warna industrial',
                ],
                'image' => 'assets/images/img-5.png',
                'intro_title' => '',
                'sort_order' => 2,
            ],
            [
                'question' => 'Furniture seperti apa yang paling kamu suka?',
                'options' => [
                    'Kayu ringan dengan bentuk sederhana',
                    'Anyaman, tekstur, dan bentuk unik',
                    'Kayu natural yang lembut dan tenang',
                    'Bentuk simpel tanpa banyak detail',
                    'Garis tegas dengan finishing modern',
                    'Material mentah dan tampilan kuat',
                ],
                'image' => 'assets/images/img3.png',
                'intro_title' => 'Terus jawab pertanyaannya...',
                'sort_order' => 3,
            ],
            [
                'question' => 'Pencahayaan ideal menurutmu seperti apa?',
                'options' => [
                    'Cahaya alami yang lembut',
                    'Lampu hangat yang dramatis',
                    'Cahaya hangat dan tenang',
                    'Cahaya terang yang bersih',
                    'Lampu modern yang elegan',
                    'Lampu gantung industrial yang tegas',
                ],
                'image' => 'assets/images/img-container.png',
                'intro_title' => '',
                'sort_order' => 4,
            ],
            [
                'question' => 'Dekorasi dinding yang paling menarik buatmu?',
                'options' => [
                    'Cermin simple dan artwork minimal',
                    'Makrame, tanaman, dan dekor bebas',
                    'Lukisan tenang dan dekor natural',
                    'Artwork clean tanpa terlalu ramai',
                    'Karya abstrak dan detail premium',
                    'Jam dinding besar atau aksen metal',
                ],
                'image' => 'assets/images/img-4.png',
                'intro_title' => 'Hmmm, seleramu bagus',
                'sort_order' => 5,
            ],
            [
                'question' => 'Kesan ruangan yang paling kamu cari?',
                'options' => [
                    'Rapi dan hangat',
                    'Ekspresif dan artistik',
                    'Damai dan seimbang',
                    'Lapang dan simpel',
                    'Mewah dan kontemporer',
                    'Tegas dan maskulin',
                ],
                'image' => 'assets/images/img-5.png',
                'intro_title' => '',
                'sort_order' => 6,
            ],
            [
                'question' => 'Kalau lihat ruangan, kamu paling suka yang mana?',
                'options' => [
                    'Ruangan terang dengan kayu natural',
                    'Ruangan ramai dengan karakter',
                    'Ruangan tenang dengan detail lembut',
                    'Ruangan polos dan bersih',
                    'Ruangan modern dengan bentuk tegas',
                    'Ruangan industrial yang kuat',
                ],
                'image' => 'assets/images/img3.png',
                'intro_title' => 'Ayoo, sedikit lagi...',
                'sort_order' => 7,
            ],
            [
                'question' => 'Apa yang paling penting dalam sebuah ruangan?',
                'options' => [
                    'Kenyamanan visual',
                    'Ekspresi diri',
                    'Keseimbangan suasana',
                    'Kesederhanaan',
                    'Kesan premium',
                    'Karakter yang kuat',
                ],
                'image' => 'assets/images/img-container.png',
                'intro_title' => 'Sedikit lagi...',
                'sort_order' => 8,
            ],
            [
                'question' => 'Apakah kamu menyukai tanaman di dalam ruangan?',
                'options' => [
                    'Ya, yang simpel saja',
                    'Ya, banyak dan beragam',
                    'Ya, tapi tetap lembut',
                    'Tidak terlalu banyak',
                    'Lebih suka aksen dekor modern',
                    'Lebih suka elemen metal atau kaca',
                ],
                'image' => 'assets/images/img-4.png',
                'intro_title' => 'Ruanganmu hampir selesai...',
                'sort_order' => 9,
            ],
            [
                'question' => 'Gaya interior mana yang paling menarik?',
                'options' => [
                    'Scandinavian',
                    'Bohemian',
                    'Japandi',
                    'Minimalist',
                    'Modern',
                    'Industrial',
                ],
                'image' => 'assets/images/img-5.png',
                'intro_title' => 'Hasilnya akan segera muncul...',
                'sort_order' => 10,
            ],
        ];

        foreach ($questions as $question) {
            AssessmentQuestion::updateOrCreate(
                ['sort_order' => $question['sort_order']],
                $question,
            );
        }

        $results = [
            [
                'style_key' => 'scandinavian',
                'title' => 'Si Hangat Scandinavian',
                'description' => 'Kamu menyukai ruangan terang, natural, dan terasa adem dipandang. Sentuhan kayu terang, warna lembut, dan komposisi yang rapi cocok untukmu.',
                'image' => 'assets/images/img-Scandinavian.png',
                'sort_order' => 1,
            ],
            [
                'style_key' => 'bohemian',
                'title' => 'Si Ceria Bohemian',
                'description' => 'Kamu suka ruangan yang penuh tekstur, warna, dan karakter bebas. Dekor ekspresif dan suasana artistik terasa paling cocok buatmu.',
                'image' => 'assets/images/img-Bohemian.png',
                'sort_order' => 2,
            ],
            [
                'style_key' => 'japandi',
                'title' => 'Si Tenang Japandi',
                'description' => 'Kamu suka ruangan yang seimbang, lembut, dan tenang. Japandi cocok untuk kamu yang menyukai suasana natural tanpa banyak distraksi.',
                'image' => 'assets/images/img-container.png',
                'sort_order' => 3,
            ],
            [
                'style_key' => 'minimalist',
                'title' => 'Si Kalem Minimalist',
                'description' => 'Kamu cocok dengan ruangan yang simpel, lapang, dan efisien. Warna netral dan elemen yang bersih bikin kamu nyaman.',
                'image' => 'assets/images/img-container.png',
                'sort_order' => 4,
            ],
            [
                'style_key' => 'modern',
                'title' => 'Si Elegan Modern',
                'description' => 'Kamu suka ruang yang terlihat tegas, bersih, dan berkelas. Bentuk yang presisi dan detail premium pas untuk gaya kamu.',
                'image' => 'assets/images/img-Modern.png',
                'sort_order' => 5,
            ],
            [
                'style_key' => 'industrial',
                'title' => 'Si Kuat Industrial',
                'description' => 'Kamu suka ruangan yang berkarakter kuat, tegas, dan sedikit mentah. Material ekspos, warna gelap, dan aksen metal cocok buatmu.',
                'image' => 'assets/images/img-container.png',
                'sort_order' => 6,
            ],
        ];

        DB::transaction(function () use ($results) {
            $resultKeys = array_column($results, 'style_key');

            AssessmentResult::query()
                ->whereIn('style_key', array_merge($resultKeys, ['minimalis']))
                ->update([
                    'sort_order' => DB::raw('sort_order + 100'),
                ]);

            foreach ($results as $result) {
                AssessmentResult::updateOrCreate(
                    ['style_key' => $result['style_key']],
                    $result,
                );
            }

            AssessmentResult::where('style_key', 'minimalis')->delete();
        });
    }
}
