<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Produkti' }} · Products Iterative</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-layout">
    <a class="skip-link" href="#main-content">Pāriet uz saturu</a>

    <header class="site-header">
        <div class="site-header__inner">
            <a class="brand" href="{{ route('products.index') }}">Products Iterative</a>
            <nav class="primary-nav" aria-label="Galvenā navigācija">
                <a href="{{ route('products.index') }}">Produkti</a>
                <a href="{{ route('products.create') }}">Pievienot produktu</a>
            </nav>
        </div>
    </header>

    <div class="site-grid">
        <aside class="sidebar sidebar--left" aria-label="Produktu izvēlne">
            <section>
                <h2>Katalogs</h2>
                <nav class="sidebar-nav" aria-label="Kataloga navigācija">
                    <a href="{{ route('products.index') }}">Visi produkti</a>
                    <a href="{{ route('products.create') }}">Jauns produkts</a>
                </nav>
            </section>
        </aside>

        <main class="page-content" id="main-content">
            @if (session('status'))
                <p class="status-message" role="status">{{ session('status') }}</p>
            @endif

            {{ $slot }}
        </main>

        <aside class="sidebar sidebar--right" aria-label="Papildu informācija">
            <section>
                <h2>Par katalogu</h2>
                <p>Pārvaldiet produktu nosaukumus, aprakstus, cenas un noliktavas atlikumus.</p>
            </section>
            <section>
                <h2>Ātrā darbība</h2>
                <p><a href="{{ route('products.create') }}">Pievienot jaunu produktu</a></p>
            </section>
        </aside>
    </div>

    <footer class="site-footer">
        <small>Produktu katalogs · Products Iterative</small>
    </footer>
</body>
</html>
