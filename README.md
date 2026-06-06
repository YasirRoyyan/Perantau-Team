# Perantau - Team

Pemrograman Web - RPL 4A

- Hafizh Iltizam Ilham - 2401286
- Juan Rezel Oktara Ramadhan - 2403469
- Muhamad Ilham Akbar Porindo R. - 2403513
- Muhammad Yasir Royyan - 2401266

## Deskripsi Project

Interiology adalah website asesmen selera desain interior berbasis Laravel. Konten halaman, menu, link sosial, pertanyaan asesmen, hasil rekomendasi, akun, dan riwayat asesmen disimpan di database sehingga dapat dikelola secara dinamis.

## Fitur

- Halaman utama Interiology berbasis data `site_contents`.
- Login, register, logout.
- Role akun: `admin` dan `user`.
- Dashboard sederhana sesuai role.
- Panel admin untuk mengelola homepage, menu navigasi, link sosial, pertanyaan asesmen, dan hasil desain.
- Halaman profil untuk mengubah data akun.
- Halaman persiapan sebelum asesmen.
- Asesmen yang pertanyaan dan pilihan jawabannya diambil dari database.
- Penyimpanan progres jawaban di session dan riwayat hasil di database.
- Halaman hasil dinamis berdasarkan jawaban user dan data hasil dari database.
- Download gambar rekomendasi interior.

## Cara Menjalankan

Cara paling aman setelah clone/pull project dari GitHub:

```bash
composer setup
php artisan serve
```

Atau gunakan satu command:

```bash
composer start
```

Setelah server berjalan, buka:

```text
http://127.0.0.1:8000
```

Jika ingin menjalankan manual satu per satu:

```bash
composer install
copy .env.example .env
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan key:generate
php artisan migrate --seed
php artisan optimize:clear
php artisan serve
```

Catatan penting setelah ambil revisi dari Git:

- Jalankan `composer install` jika ada perubahan dependency.
- Jalankan `php artisan migrate --seed` jika ada perubahan database atau data awal.
- Jalankan `php artisan optimize:clear` jika tampilan/route/config masih seperti versi lama.

Untuk reset database lokal dari awal:

```bash
php artisan migrate:fresh --seed
```

## Akun Contoh

Seeder menyiapkan dua akun demo:

- Admin: `admin@interiology.test` / `password`
- User: `user@interiology.test` / `password`

## Route Utama

- `/` halaman utama.
- `/login` halaman masuk.
- `/register` halaman daftar.
- `/dashboard` dashboard user/admin.
- `/profile` halaman profil.
- `/prepare`, `/assessment`, dan `/result` untuk alur asesmen setelah login.
- `/admin/content` panel admin untuk konten homepage, menu, dan sosial.
- `/admin/questions` panel admin untuk pertanyaan asesmen.
- `/admin/results` panel admin untuk hasil rekomendasi.

## Struktur Utama

- `routes/web.php` untuk route halaman dan flow asesmen.
- `app/Http/Controllers/AssessmentController.php` untuk logika asesmen, hasil, dan riwayat.
- `app/Http/Controllers/AdminContentController.php` untuk pengelolaan konten dinamis.
- `app/Http/Controllers/AdminQuestionController.php` untuk CRUD pertanyaan asesmen.
- `app/Http/Controllers/AdminResultController.php` untuk CRUD hasil rekomendasi.
- `app/Http/Controllers/AuthController.php` untuk login, register, dan logout.
- `app/Http/Controllers/DashboardController.php` untuk dashboard role admin/user.
- `app/Http/Controllers/ProfileController.php` untuk halaman profil.
- `app/Models/SiteContent.php`, `NavigationItem.php`, dan `SocialLink.php` untuk konten website dinamis.
- `app/Models/AssessmentQuestion.php`, `AssessmentResult.php`, dan `AssessmentAttempt.php` untuk asesmen dinamis dan riwayat user.
- `resources/views/layouts/app.blade.php` untuk layout utama.
- `resources/views/pages` untuk halaman Blade.
- `resources/views/admin` untuk panel admin.
- `resources/views/auth` untuk halaman login dan register.
- `resources/views/partials/nav.blade.php` untuk navigasi bersama.
- `public/assets/css` untuk stylesheet yang masih dibutuhkan tampilan Blade.
- `public/assets/js` untuk JavaScript interaksi UI yang masih dibutuhkan.
- `public/assets/images`, `public/assets/icons`, dan `public/assets/fonts` untuk asset visual dan font.
- `database/database.sqlite` untuk database lokal Laravel.
