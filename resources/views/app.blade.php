<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        @php
            $faviconPath = \App\Models\Setting::get('app_favicon');
            $faviconUrl = $faviconPath ? \Illuminate\Support\Facades\Storage::disk('public')->url($faviconPath) : null;
            $faviconExt = $faviconPath ? pathinfo($faviconPath, PATHINFO_EXTENSION) : null;
            $faviconType = match ($faviconExt) {
                'svg' => 'image/svg+xml',
                'ico' => 'image/x-icon',
                'png' => 'image/png',
                'webp' => 'image/webp',
                default => null,
            };
        @endphp

        @if($faviconUrl)
            <link rel="icon" href="{{ $faviconUrl }}" @if($faviconType) type="{{ $faviconType }}" @endif>
        @else
            <link rel="icon" href="/favicon.ico" sizes="any">
            <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        @endif
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
