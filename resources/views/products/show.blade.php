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

    <section aria-labelledby="quantity-adjustment-heading">
        <h2 id="quantity-adjustment-heading">Mainīt noliktavas daudzumu</h2>
        <p>Pašreizējais daudzums: <strong>{{ $product->quantity }}</strong></p>
        <form action="{{ route('products.quantity.adjust', $product) }}" method="POST">
            @csrf
            @method('PATCH')
            <label for="adjustment">Izmaiņa (pozitīvs pievedumam, negatīvs izpirkšanai)</label>
            <input id="adjustment" name="adjustment" type="number" step="1" required value="{{ old('adjustment', 1) }}">
            <button type="submit">Atjaunināt daudzumu</button>
        </form>
    </section>

    <p><a href="{{ route('products.edit', $product) }}">Rediģēt produktu</a></p>
    <p><a href="{{ route('products.index') }}">Atpakaļ uz produktiem</a></p>
</x-layout>
