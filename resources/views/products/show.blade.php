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
        <p>Pašreizējais daudzums: <strong data-quantity-value>{{ $product->quantity }}</strong></p>
        <form action="{{ route('products.quantity.adjust', $product) }}" method="POST" data-quantity-form>
            @csrf
            @method('PATCH')
            <label for="adjustment">Izmaiņa (pozitīvs pievedumam, negatīvs izpirkšanai)</label>
            <input id="adjustment" name="adjustment" type="number" step="1" required value="{{ old('adjustment', 1) }}">
            <button type="submit">Atjaunināt daudzumu</button>
        </form>
        <p data-quantity-feedback aria-live="polite"></p>
    </section>

    <section class="tag-manager" data-tag-manager data-search-url="{{ route('tags.search') }}" aria-labelledby="tag-manager-heading">
        <h2 id="tag-manager-heading">Produkta birkas</h2>
        <form action="{{ route('products.tags.update', $product) }}" method="POST" data-tag-form>
            @csrf
            @method('PUT')
            <label for="tag-search">Pievienot birku</label>
            <input id="tag-search" type="search" autocomplete="off" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="tag-suggestions" data-tag-input>
            <ul id="tag-suggestions" class="tag-suggestions" role="listbox" data-tag-suggestions hidden></ul>
            <p class="tag-help">Izvēlies ieteikumu ar Enter vai pievieno jaunu nosaukumu.</p>
            <ul class="tag-list" aria-label="Izvēlētās birkas" data-tag-list>
                @foreach ($product->tags as $tag)
                    <li class="tag-chip" data-tag-chip>
                        <span>{{ $tag->name }}</span>
                        <input type="hidden" name="tags[]" value="{{ $tag->name }}">
                        <button type="button" aria-label="Noņemt birku {{ $tag->name }}" data-remove-tag>×</button>
                    </li>
                @endforeach
            </ul>
            <button type="submit">Atjaunināt birkas</button>
        </form>
    </section>

    <p><a href="{{ route('products.edit', $product) }}">Rediģēt produktu</a></p>
    <p><a href="{{ route('products.index') }}">Atpakaļ uz produktiem</a></p>
</x-layout>
