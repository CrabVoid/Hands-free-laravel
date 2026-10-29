@csrf

<div>
    <label for="name">Nosaukums</label>
    <input id="name" name="name" type="text" value="{{ old('name', $product?->name) }}" required maxlength="255">
    @error('name') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="description">Apraksts</label>
    <textarea id="description" name="description" rows="5">{{ old('description', $product?->description) }}</textarea>
    @error('description') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="price">Cena (€)</label>
    <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $product?->price) }}" required>
    @error('price') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="stock">Daudzums</label>
    <input id="stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', $product?->stock ?? 0) }}" required>
    @error('stock') <p>{{ $message }}</p> @enderror
</div>

<button type="submit">{{ $buttonLabel }}</button>
