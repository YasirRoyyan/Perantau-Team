# Perantau - Team

Pemrograman Web - RPL 4A

- Hafizh Iltizam Ilham - 2401286
- Juan Rezel Oktara Ramadhan - 2403469
- Muhamad Ilham Akbar Porindo - 2403513
- Muhammad Yasir Royyan - 2401266

## Deskripsi Project

Interiology adalah website asesmen selera desain interior berbasis Laravel. Versi awal proyek yang masih HTML, CSS, dan JavaScript statis sudah dimigrasikan menjadi web dinamis dengan routing, Blade view, dan pengolahan jawaban di backend menggunakan session Laravel.

## Fitur

- Halaman utama Interiology.
- Login, register, logout.
- Role akun: `admin` dan `user`.
- Dashboard sederhana sesuai role.
- Halaman profil untuk mengubah data akun.
- Halaman persiapan sebelum asesmen.
- Asesmen 10 pertanyaan yang diproses oleh Laravel.
- Penyimpanan progres jawaban di session.
- Halaman hasil dinamis berdasarkan jawaban user.
- Download gambar rekomendasi interior.

## Cara Menjalankan

```bash
composer install
copy .env.example .env
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan key:generate
php artisan migrate
php artisan serve
```

Setelah server berjalan, buka:

```text
http://127.0.0.1:8000
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

## Struktur Utama

- `routes/web.php` untuk route halaman dan flow asesmen.
- `app/Http/Controllers/AssessmentController.php` untuk logika asesmen dan hasil.
- `app/Http/Controllers/AuthController.php` untuk login, register, dan logout.
- `app/Http/Controllers/DashboardController.php` untuk dashboard role admin/user.
- `app/Http/Controllers/ProfileController.php` untuk halaman profil.
- `resources/views/layouts/app.blade.php` untuk layout utama.
- `resources/views/pages` untuk halaman Blade.
- `resources/views/auth` untuk halaman login dan register.
- `resources/views/partials/nav.blade.php` untuk navigasi bersama.
- `public/assets/css` untuk file CSS dari halaman lama.
- `public/assets/js` untuk JavaScript halaman lama yang masih dibutuhkan.
- `public/assets/images`, `public/assets/icons`, dan `public/assets/fonts` untuk asset visual dan font.
- `database/database.sqlite` untuk database lokal Laravel.
