<x-layout :title="$product->name">
    <h1>{{ $product->name }}</h1>
    <dl>
        <dt>Apraksts</dt>
        <dd>{{ $product->description ?: 'Apraksts nav pievienots.' }}</dd>
        <dt>Daudzums</dt>
        <dd>{{ $product->quantity }}</dd>
        <dt>Derīguma termiņš</dt>
        <dd>{{ $product->expiration_date?->format('Y-m-d') ?? 'Nav norādīts' }}</dd>
        <dt>Statuss</dt>
        <dd>{{ $product->status }}</dd>
    </dl>

    <p><a href="{{ route('products.edit', $product) }}">Rediģēt produktu</a></p>
    <p><a href="{{ route('products.index') }}">Atpakaļ uz produktiem</a></p>
</x-layout>
