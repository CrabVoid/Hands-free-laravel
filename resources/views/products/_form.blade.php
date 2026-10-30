@csrf

<div>
    <label for="name">Nosaukums</label>
    <input id="name" name="name" type="text" value="{{ old('name', $product?->name) }}" required maxlength="255">
</div>

<div>
    <label for="quantity">Daudzums</label>
    <input id="quantity" name="quantity" type="number" min="0" step="1" value="{{ old('quantity', $product?->quantity ?? 0) }}" required>
</div>

<div>
    <label for="description">Apraksts</label>
    <textarea id="description" name="description" rows="5">{{ old('description', $product?->description) }}</textarea>
</div>

<div>
    <label for="expiration_date">Derīguma termiņš</label>
    <input id="expiration_date" name="expiration_date" type="date" value="{{ old('expiration_date', $product?->expiration_date?->format('Y-m-d')) }}">
</div>

<div>
    <label for="status">Statuss</label>
    <select id="status" name="status" required>
        @foreach (\App\Models\Product::STATUSES as $status)
            <option value="{{ $status }}" @selected(old('status', $product?->status ?? 'active') === $status)>
                {{ match ($status) { 'active' => 'Aktīvs', 'inactive' => 'Neaktīvs', 'expired' => 'Beidzies', default => $status } }}
            </option>
        @endforeach
    </select>
</div>

<button type="submit">{{ $buttonLabel }}</button>
