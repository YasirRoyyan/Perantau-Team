<?php

namespace App\Http\Controllers;

use App\Models\NavigationItem;
use App\Models\SiteContent;
use App\Models\SocialLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminContentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        return view('admin.content', [
            'home' => SiteContent::payload('home', []),
            'navigationItems' => NavigationItem::orderBy('sort_order')->get(),
            'socialLinks' => SocialLink::orderBy('sort_order')->get(),
        ]);
    }

    public function updateHome(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:180'],
            'hero_description' => ['required', 'string', 'max:300'],
            'hero_button' => ['required', 'string', 'max:60'],
            'workflow_title' => ['required', 'string', 'max:160'],
            'step_label' => ['required', 'array', 'size:4'],
            'step_icon' => ['required', 'array', 'size:4'],
            'step_alt' => ['required', 'array', 'size:4'],
            'showcase_image' => ['required', 'string', 'max:255'],
            'showcase_alt' => ['required', 'string', 'max:100'],
            'cta_title' => ['required', 'string', 'max:160'],
            'cta_button' => ['required', 'string', 'max:60'],
            'gallery_image' => ['required', 'array', 'size:3'],
            'gallery_alt' => ['required', 'array', 'size:3'],
            'footer_title' => ['required', 'string', 'max:120'],
            'footer_description' => ['required', 'string', 'max:500'],
            'footer_location' => ['required', 'string', 'max:160'],
        ]);

        SiteContent::updateOrCreate(['key' => 'home'], [
            'payload' => [
                'hero' => [
                    'title' => $validated['hero_title'],
                    'description' => $validated['hero_description'],
                    'button' => $validated['hero_button'],
                ],
                'workflow_title' => $validated['workflow_title'],
                'workflow_steps' => collect($validated['step_label'])->map(fn ($label, $index) => [
                    'class' => 'step-card-'.($index + 1),
                    'icon' => $validated['step_icon'][$index],
                    'alt' => $validated['step_alt'][$index],
                    'label' => $label,
                ])->values()->all(),
                'showcase' => [
                    'image' => $validated['showcase_image'],
                    'alt' => $validated['showcase_alt'],
                ],
                'custom_room_cta' => [
                    'title' => $validated['cta_title'],
                    'button' => $validated['cta_button'],
                ],
                'gallery_images' => collect($validated['gallery_image'])->map(fn ($image, $index) => [
                    'image' => $image,
                    'alt' => $validated['gallery_alt'][$index],
                ])->values()->all(),
                'footer' => [
                    'title' => $validated['footer_title'],
                    'description' => $validated['footer_description'],
                    'location' => $validated['footer_location'],
                ],
            ],
        ]);

        return back()->with('status', 'Konten homepage berhasil diperbarui.');
    }

    public function updateNavigation(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['nullable', 'integer', 'exists:navigation_items,id'],
            'items.*.label' => ['required', 'string', 'max:80'],
            'items.*.route_name' => ['nullable', 'string', 'max:80'],
            'items.*.anchor' => ['nullable', 'string', 'max:80'],
            'items.*.external_url' => ['nullable', 'string', 'max:255'],
            'items.*.auth_state' => ['required', 'in:all,guest,auth'],
            'items.*.is_cta' => ['nullable', 'boolean'],
            'items.*.is_active' => ['nullable', 'boolean'],
            'items.*.sort_order' => ['required', 'integer', 'min:1'],
        ]);

        foreach ($validated['items'] as $item) {
            NavigationItem::updateOrCreate(
                ['id' => $item['id'] ?? null],
                [
                    'label' => $item['label'],
                    'route_name' => $item['route_name'] ?? null,
                    'anchor' => $item['anchor'] ?? null,
                    'external_url' => $item['external_url'] ?? null,
                    'auth_state' => $item['auth_state'],
                    'is_cta' => (bool) ($item['is_cta'] ?? false),
                    'is_active' => (bool) ($item['is_active'] ?? false),
                    'sort_order' => $item['sort_order'],
                ],
            );
        }

        return back()->with('status', 'Menu navigasi berhasil diperbarui.');
    }

    public function updateSocialLinks(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'links' => ['required', 'array'],
            'links.*.id' => ['nullable', 'integer', 'exists:social_links,id'],
            'links.*.label' => ['required', 'string', 'max:80'],
            'links.*.url' => ['required', 'string', 'max:255'],
            'links.*.icon' => ['required', 'string', 'max:255'],
            'links.*.is_active' => ['nullable', 'boolean'],
            'links.*.sort_order' => ['required', 'integer', 'min:1'],
        ]);

        foreach ($validated['links'] as $link) {
            SocialLink::updateOrCreate(
                ['id' => $link['id'] ?? null],
                [
                    'label' => $link['label'],
                    'url' => $link['url'],
                    'icon' => $link['icon'],
                    'is_active' => (bool) ($link['is_active'] ?? false),
                    'sort_order' => $link['sort_order'],
                ],
            );
        }

        return back()->with('status', 'Link sosial berhasil diperbarui.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'admin', 403);
    }
}
