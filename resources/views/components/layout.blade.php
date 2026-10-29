<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Produkti' }} · Products Iterative</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header>
        <a href="{{ route('products.index') }}">Products Iterative</a>
        <nav aria-label="Galvenā navigācija">
            <a href="{{ route('products.index') }}">Produkti</a>
            <a href="{{ route('products.create') }}">Pievienot produktu</a>
        </nav>
    </header>
    <main>
        @if (session('status'))
            <p role="status">{{ session('status') }}</p>
        @endif

        {{ $slot }}
    </main>
    <footer>
        <small>Produktu katalogs</small>
    </footer>
</body>
</html>
