@if ($errors->any())
    <x-flash-message type="error">
        <p>Neizdevās saglabāt datus. Lūdzu, izlabojiet šīs kļūdas:</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-flash-message>
@endif
