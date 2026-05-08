@extends('layouts.app', [
    'title' => 'Interiology - Kelola Konten',
    'styles' => ['assets/css/shared.css', 'assets/css/auth.css', 'assets/css/admin.css'],
])

@section('body')
    @include('partials.nav')

    <main class="dashboard-page">
        <section class="dashboard-hero">
            <div>
                <p class="eyebrow">Admin</p>
                <h1>Kelola Konten Dinamis</h1>
                <p class="auth-copy">Ubah homepage, menu, dan link sosial dari database.</p>
            </div>
            <div class="dashboard-actions">
                <a href="{{ route('admin.questions.index') }}" class="btn-outline">Pertanyaan</a>
                <a href="{{ route('admin.results.index') }}" class="btn-outline">Hasil Desain</a>
            </div>
        </section>

        <section class="panel">
            @include('partials.alerts')

            <h2>Homepage</h2>
            <form action="{{ route('admin.content.home') }}" method="POST" class="profile-form">
                @csrf
                @method('PUT')

                <label>
                    Judul Hero
                    <input name="hero_title" value="{{ old('hero_title', data_get($home, 'hero.title')) }}" required>
                </label>
                <label>
                    Tombol Hero
                    <input name="hero_button" value="{{ old('hero_button', data_get($home, 'hero.button')) }}" required>
                </label>
                <label class="full-span">
                    Deskripsi Hero
                    <textarea name="hero_description" rows="3" required>{{ old('hero_description', data_get($home, 'hero.description')) }}</textarea>
                </label>
                <label class="full-span">
                    Judul Cara Kerja
                    <input name="workflow_title" value="{{ old('workflow_title', data_get($home, 'workflow_title')) }}" required>
                </label>

                @for ($i = 0; $i < 4; $i++)
                    <div class="admin-subgrid full-span">
                        <label>
                            Label Langkah {{ $i + 1 }}
                            <input name="step_label[]" value="{{ old("step_label.$i", data_get($home, "workflow_steps.$i.label")) }}" required>
                        </label>
                        <label>
                            Icon Langkah {{ $i + 1 }}
                            <input name="step_icon[]" value="{{ old("step_icon.$i", data_get($home, "workflow_steps.$i.icon")) }}" required>
                        </label>
                        <label>
                            Alt Icon {{ $i + 1 }}
                            <input name="step_alt[]" value="{{ old("step_alt.$i", data_get($home, "workflow_steps.$i.alt")) }}" required>
                        </label>
                    </div>
                @endfor

                <label>
                    Gambar Showcase
                    <input name="showcase_image" value="{{ old('showcase_image', data_get($home, 'showcase.image')) }}" required>
                </label>
                <label>
                    Alt Showcase
                    <input name="showcase_alt" value="{{ old('showcase_alt', data_get($home, 'showcase.alt')) }}" required>
                </label>
                <label>
                    Judul CTA
                    <input name="cta_title" value="{{ old('cta_title', data_get($home, 'custom_room_cta.title')) }}" required>
                </label>
                <label>
                    Tombol CTA
                    <input name="cta_button" value="{{ old('cta_button', data_get($home, 'custom_room_cta.button')) }}" required>
                </label>

                @for ($i = 0; $i < 3; $i++)
                    <div class="admin-subgrid full-span">
                        <label>
                            Gambar Galeri {{ $i + 1 }}
                            <input name="gallery_image[]" value="{{ old("gallery_image.$i", data_get($home, "gallery_images.$i.image")) }}" required>
                        </label>
                        <label>
                            Alt Galeri {{ $i + 1 }}
                            <input name="gallery_alt[]" value="{{ old("gallery_alt.$i", data_get($home, "gallery_images.$i.alt")) }}" required>
                        </label>
                    </div>
                @endfor

                <label>
                    Judul Footer
                    <input name="footer_title" value="{{ old('footer_title', data_get($home, 'footer.title')) }}" required>
                </label>
                <label>
                    Lokasi Footer
                    <input name="footer_location" value="{{ old('footer_location', data_get($home, 'footer.location')) }}" required>
                </label>
                <label class="full-span">
                    Deskripsi Footer
                    <textarea name="footer_description" rows="4" required>{{ old('footer_description', data_get($home, 'footer.description')) }}</textarea>
                </label>

                <button class="btn-submit full-span" type="submit">Simpan Homepage</button>
            </form>
        </section>

        <section class="panel">
            <h2>Menu Navigasi</h2>
            <form action="{{ route('admin.content.navigation') }}" method="POST" class="admin-table-form">
                @csrf
                @method('PUT')

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Label</th>
                                <th>Route</th>
                                <th>Anchor</th>
                                <th>URL Eksternal</th>
                                <th>Akses</th>
                                <th>CTA</th>
                                <th>Aktif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($navigationItems as $item)
                                <tr>
                                    <td>
                                        <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                        <input class="admin-small-input" name="items[{{ $loop->index }}][sort_order]" value="{{ $item->sort_order }}" required>
                                    </td>
                                    <td><input name="items[{{ $loop->index }}][label]" value="{{ $item->label }}" required></td>
                                    <td><input name="items[{{ $loop->index }}][route_name]" value="{{ $item->route_name }}"></td>
                                    <td><input name="items[{{ $loop->index }}][anchor]" value="{{ $item->anchor }}"></td>
                                    <td><input name="items[{{ $loop->index }}][external_url]" value="{{ $item->external_url }}"></td>
                                    <td>
                                        <select name="items[{{ $loop->index }}][auth_state]">
                                            @foreach (['all' => 'Semua', 'guest' => 'Guest', 'auth' => 'Login'] as $value => $label)
                                                <option value="{{ $value }}" @selected($item->auth_state === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="checkbox" name="items[{{ $loop->index }}][is_cta]" value="1" @checked($item->is_cta)></td>
                                    <td><input type="checkbox" name="items[{{ $loop->index }}][is_active]" value="1" @checked($item->is_active)></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button class="btn-submit" type="submit">Simpan Menu</button>
            </form>
        </section>

        <section class="panel">
            <h2>Link Sosial</h2>
            <form action="{{ route('admin.content.social-links') }}" method="POST" class="admin-table-form">
                @csrf
                @method('PUT')

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Label</th>
                                <th>URL</th>
                                <th>Icon</th>
                                <th>Aktif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($socialLinks as $link)
                                <tr>
                                    <td>
                                        <input type="hidden" name="links[{{ $loop->index }}][id]" value="{{ $link->id }}">
                                        <input class="admin-small-input" name="links[{{ $loop->index }}][sort_order]" value="{{ $link->sort_order }}" required>
                                    </td>
                                    <td><input name="links[{{ $loop->index }}][label]" value="{{ $link->label }}" required></td>
                                    <td><input name="links[{{ $loop->index }}][url]" value="{{ $link->url }}" required></td>
                                    <td><input name="links[{{ $loop->index }}][icon]" value="{{ $link->icon }}" required></td>
                                    <td><input type="checkbox" name="links[{{ $loop->index }}][is_active]" value="1" @checked($link->is_active)></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button class="btn-submit" type="submit">Simpan Link Sosial</button>
            </form>
        </section>
    </main>
@endsection
