<x-layout title="Rediģēt produktu">
    <h1>Rediģēt produktu</h1>

    <form action="{{ route('products.update', $product) }}" method="POST">
        @method('PUT')
        @include('products._form', ['buttonLabel' => 'Saglabāt izmaiņas'])
    </form>

    <p><a href="{{ route('products.show', $product) }}">Atcelt</a></p>
</x-layout>
