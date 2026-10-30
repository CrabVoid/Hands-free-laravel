<x-layout title="Produkti">
    <h1>Produkti</h1>
    <p><a href="{{ route('products.create') }}">Pievienot produktu</a></p>

    @if ($products->isEmpty())
        <p>Produktu vēl nav. Pievienojiet pirmo produktu.</p>
    @else
        <table>
            <thead>
                <tr><th>Nosaukums</th><th>Daudzums</th><th>Derīguma termiņš</th><th>Statuss</th><th>Darbības</th></tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->expiration_date?->format('Y-m-d') ?? '—' }}</td>
                        <td>{{ $product->status }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product) }}">Rediģēt</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Dzēst</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $products->links() }}
    @endif
</x-layout>
