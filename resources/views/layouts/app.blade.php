<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Interiology' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icons/icon-sofa.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">
    @foreach (($styles ?? []) as $style)
        @php($stylePath = public_path($style))
        <link rel="stylesheet" href="{{ asset($style) }}@if (file_exists($stylePath))?v={{ filemtime($stylePath) }}@endif">
    @endforeach
</head>
<body @if (! empty($bodyClass)) class="{{ $bodyClass }}" @endif>
    @yield('body')

    @stack('scripts')
</body>
</html>
