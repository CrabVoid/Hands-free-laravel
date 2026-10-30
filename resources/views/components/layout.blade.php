<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Produkti' }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-layout">
    <a class="skip-link" href="#main-content">Pāriet uz saturu</a>

    <header class="site-header">
        <div class="site-header__inner">
            <div class="brand-group">
                <span class="logo-placeholder" aria-label="Testa logo">LOGO</span>
                <a class="brand" href="{{ route('products.index') }}">{{ config('app.name') }}</a>
            </div>
        </div>
    </header>

    <div class="site-grid">
        <aside class="sidebar sidebar--left" aria-label="Produktu izvēlne">
            <section>
                <h2>Katalogs</h2>
                <nav class="sidebar-nav" aria-label="Kataloga navigācija">
                    <ul>
                        <li><a href="{{ route('products.index') }}">Visi produkti</a></li>
                        <li><a href="{{ route('products.create') }}">Jauns produkts</a></li>
                    </ul>
                </nav>
            </section>
        </aside>

        <main class="page-content" id="main-content">
            <x-validation-errors />

            @if (session('status'))
                <x-flash-message type="success" :message="session('status')" />
            @endif

            {{ $slot }}
        </main>

        <aside class="sidebar sidebar--right" aria-label="Papildu informācija">
            <section class="advertisement" aria-label="Testa reklāma">
                <h2>Reklāma</h2>
                <p>Testa reklāmas teksts: atklājiet jaunumus un īpašos piedāvājumus!</p>
            </section>
        </aside>
    </div>

    <footer class="site-footer">
        <small>© {{ now()->year }} {{ config('app.name') }}. Visas tiesības aizsargātas.</small>
    </footer>
</body>
</html>
