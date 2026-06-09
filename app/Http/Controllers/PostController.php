<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAttempt;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    private const ROOM_DRAFT_SESSION = 'interiology.custom_room_draft';

    private const STYLE_ALIASES = [
        'minimalis' => 'minimalist',
    ];

    private const ROOM_STYLES = [
        'scandinavian' => [
            'label' => 'Scandinavian',
            'description' => 'Kayu terang, warna lembut, dan bentuk yang bersih.',
        ],
        'bohemian' => [
            'label' => 'Bohemian',
            'description' => 'Tekstur berlapis, dekor ekspresif, dan suasana bebas.',
        ],
        'japandi' => [
            'label' => 'Japandi',
            'description' => 'Tenang, natural, rapi, dan dekat dengan nuansa zen.',
        ],
        'minimalist' => [
            'label' => 'Minimalist',
            'description' => 'Sederhana, lapang, dan fokus pada elemen penting.',
        ],
        'modern' => [
            'label' => 'Modern',
            'description' => 'Garis tegas, visual bersih, dan terasa kontemporer.',
        ],
        'industrial' => [
            'label' => 'Industrial',
            'description' => 'Material mentah, warna gelap, dan karakter urban.',
        ],
    ];

    private const ROOM_CATEGORIES = [
        'chairs' => ['label' => 'Kursi'],
        'tables' => ['label' => 'Meja'],
        'walls' => ['label' => 'Hiasan'],
    ];

    public function index(): View
    {
        return view('pages.dashboard', [
            'posts' => Post::with('user')->latest()->get(),
        ]);
    }

    public function customRoom(Request $request): View
    {
        $activeStyle = $this->resolveRoomStyle($request);

        return view('pages.custom-room', [
            'roomConfig' => $this->buildRoomConfig($activeStyle),
        ]);
    }

    public function stashCustomRoomDraft(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'string'],
            'style' => ['nullable', 'string'],
        ]);

        if (! $this->isPngDataUri($validated['image'])) {
            return response()->json([
                'message' => 'Format gambar hasil kustom ruangan tidak valid.',
            ], 422);
        }

        $style = $this->normalizeStyleKey($validated['style'] ?? null) ?? $this->resolveRoomStyle($request);

        $request->session()->put(self::ROOM_DRAFT_SESSION, [
            'image' => $validated['image'],
            'style' => $style,
            'created_at' => now()->toIso8601String(),
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('custom-room.upload'),
        ]);
    }

    public function showCustomRoomUpload(Request $request): View|RedirectResponse
    {
        $draft = $request->session()->get(self::ROOM_DRAFT_SESSION);

        if (! is_array($draft) || empty($draft['image'])) {
            return redirect()->route('custom-room')
                ->with('error', 'Hasil kustom ruangan belum tersedia. Susun ruangan dulu ya.');
        }

        $style = $this->normalizeStyleKey($draft['style'] ?? null) ?? $this->resolveRoomStyle($request);
        $styleMeta = self::ROOM_STYLES[$style] ?? self::ROOM_STYLES['scandinavian'];
        $user = $request->user();

        return view('pages.custom-room-upload', [
            'draft' => $draft + ['style' => $style],
            'styleLabel' => $styleMeta['label'],
            'user' => $user,
            'totalPosts' => $user?->posts()->count() ?? 0,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'image' => ['nullable', 'string'],
            'caption' => ['nullable', 'string', 'max:1000'],
        ]);

        $draft = $request->session()->get(self::ROOM_DRAFT_SESSION, []);
        $imageData = $validated['image'] ?? ($draft['image'] ?? null);

        if (! is_string($imageData) || ! $this->isPngDataUri($imageData)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Gambar hasil kustom ruangan tidak ditemukan.',
                ], 422);
            }

            return redirect()->route('custom-room')
                ->with('error', 'Gambar hasil kustom ruangan tidak ditemukan.');
        }

        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        $decodedImage = base64_decode($image, true);

        if ($decodedImage === false) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gambar gagal diproses.'], 422);
            }

            return redirect()->route('custom-room.upload')
                ->with('error', 'Gambar gagal diproses.');
        }

        $fileName = 'posts/custom_room_'.Str::uuid().'.png';
        Storage::disk(env('FILESYSTEM_DISK', 'public'))->put($fileName, $decodedImage);

        $caption = trim((string) ($validated['caption'] ?? ''));

        Post::create([
            'user_id' => $request->user()->id,
            'image' => $fileName,
            'caption' => $caption !== '' ? $caption : null,
        ]);

        $request->session()->forget(self::ROOM_DRAFT_SESSION);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('dashboard')->with('success', 'Hasil kustom ruangan berhasil dibagikan!');
    }

    private function resolveRoomStyle(Request $request): string
    {
        $fromQuery = $this->normalizeStyleKey($request->query('style'));

        if ($fromQuery) {
            return $fromQuery;
        }

        $fromSession = $this->normalizeStyleKey($request->session()->get('interiology.result'));

        if ($fromSession) {
            return $fromSession;
        }

        $latestAttempt = AssessmentAttempt::query()
            ->where('user_id', $request->user()?->id)
            ->latest()
            ->first();

        $fromAttempt = $this->normalizeStyleKey($latestAttempt?->result_key);

        return $fromAttempt ?? 'scandinavian';
    }

    private function normalizeStyleKey(?string $style): ?string
    {
        if (! $style) {
            return null;
        }

        $style = Str::of($style)->lower()->replace(' ', '-')->toString();
        $style = self::STYLE_ALIASES[$style] ?? $style;

        return array_key_exists($style, self::ROOM_STYLES) ? $style : null;
    }

    private function buildRoomConfig(string $activeStyle): array
    {
        $styles = [];

        foreach (self::ROOM_STYLES as $key => $style) {
            $styles[$key] = [
                'key' => $key,
                'label' => $style['label'],
                'description' => $style['description'],
                'background' => $this->backgroundForStyle($key),
            ];
        }

        return [
            'activeStyle' => $this->normalizeStyleKey($activeStyle) ?? 'scandinavian',
            'activeCategory' => 'chairs',
            'styles' => $styles,
            'categories' => self::ROOM_CATEGORIES,
            'items' => $this->loadStyleItems(),
        ];
    }

    private function backgroundForStyle(string $style): string
    {
        foreach (['png', 'jpg', 'jpeg', 'webp'] as $extension) {
            $relativePath = "assets/images/custom-room/backgrounds/{$style}.{$extension}";

            if (File::exists(public_path($relativePath))) {
                return asset($relativePath);
            }
        }

        return asset('assets/images/custom-room-empty.png');
    }

    private function loadStyleItems(): array
    {
        $items = [];

        foreach (array_keys(self::ROOM_STYLES) as $style) {
            $items[$style] = [];

            foreach (array_keys(self::ROOM_CATEGORIES) as $category) {
                $items[$style][$category] = $this->loadCategoryItems($style, $category);
            }
        }

        return $items;
    }

    private function loadCategoryItems(string $style, string $category): array
    {
        $directory = public_path("assets/images/custom-room/items/{$style}/{$category}");

        if (! File::isDirectory($directory)) {
            return [];
        }

        return collect(File::files($directory))
            ->sortBy(fn ($file) => $file->getFilename())
            ->values()
            ->map(function ($file) use ($style, $category) {
                $filename = $file->getFilename();
                $defaults = $this->itemDefaults($filename, $category);
                $id = $style.'-'.$category.'-'.Str::slug(pathinfo($filename, PATHINFO_FILENAME));

                return [
                    'id' => $id,
                    'type' => $category,
                    'name' => $this->displayNameFromFile($filename),
                    'image' => asset("assets/images/custom-room/items/{$style}/{$category}/{$filename}"),
                    'width' => $defaults['width'],
                    'x' => $defaults['x'],
                    'y' => $defaults['y'],
                ];
            })
            ->all();
    }

    private function itemDefaults(string $filename, string $category): array
    {
        $name = Str::of(pathinfo($filename, PATHINFO_FILENAME))->lower()->toString();

        if ($category === 'chairs') {
            if (Str::contains($name, ['sofa', 'daybed', 'bench'])) {
                return ['width' => 260, 'x' => 55, 'y' => 74];
            }

            if (Str::contains($name, ['pouf', 'ottoman', 'stool'])) {
                return ['width' => 120, 'x' => 48, 'y' => 78];
            }

            return ['width' => 150, 'x' => 50, 'y' => 70];
        }

        if ($category === 'tables') {
            if (Str::contains($name, ['desk', 'console', 'cabinet', 'media'])) {
                return ['width' => 210, 'x' => 50, 'y' => 75];
            }

            return ['width' => 175, 'x' => 50, 'y' => 77];
        }

        if (Str::contains($name, ['rug', 'carpet'])) {
            return ['width' => 260, 'x' => 50, 'y' => 84];
        }

        if (Str::contains($name, ['plant', 'vase'])) {
            return ['width' => 85, 'x' => 72, 'y' => 68];
        }

        if (Str::contains($name, ['lamp'])) {
            return ['width' => 90, 'x' => 68, 'y' => 62];
        }

        return ['width' => 90, 'x' => 50, 'y' => 36];
    }

    private function displayNameFromFile(string $filename): string
    {
        return Str::of(pathinfo($filename, PATHINFO_FILENAME))
            ->replace(['-', '_'], ' ')
            ->title()
            ->toString();
    }

    private function isPngDataUri(string $imageData): bool
    {
        return str_starts_with($imageData, 'data:image/png;base64,');
    }
}
