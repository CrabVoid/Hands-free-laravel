<x-layout :title="$product->name">
    <h1>{{ $product->name }}</h1>
    <dl>
        <dt>Apraksts</dt>
        <dd>{{ $product->description ?: 'Apraksts nav pievienots.' }}</dd>
        <dt>Cena</dt>
        <dd>€{{ number_format((float) $product->price, 2) }}</dd>
        <dt>Daudzums</dt>
        <dd>{{ $product->stock }}</dd>
    </dl>

    <p><a href="{{ route('products.edit', $product) }}">Rediģēt produktu</a></p>
    <p><a href="{{ route('products.index') }}">Atpakaļ uz produktiem</a></p>
</x-layout>
