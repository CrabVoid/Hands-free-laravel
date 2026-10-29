<x-layout title="Jauns produkts">
    <h1>Jauns produkts</h1>

    <form action="{{ route('products.store') }}" method="POST">
        @include('products._form', ['product' => null, 'buttonLabel' => 'Izveidot produktu'])
    </form>

    <p><a href="{{ route('products.index') }}">Atpakaļ uz produktiem</a></p>
</x-layout>
