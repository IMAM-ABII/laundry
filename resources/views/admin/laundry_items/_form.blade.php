<div>
    <label for="name" class="mb-1 block font-medium">Nama Layanan</label>
    <input id="name" type="text" name="name" value="{{ old('name', $laundryItem->name ?? '') }}" maxlength="255" required class="w-full rounded border p-2">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label for="category" class="mb-1 block font-medium">Kategori</label>
    <select id="category" name="category" required class="w-full rounded border p-2">
        @foreach(['Cuci', 'Setrika', 'Paket'] as $category)
            <option value="{{ $category }}" @selected(old('category', $laundryItem->category ?? '') === $category)>{{ $category }}</option>
        @endforeach
    </select>
    @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label for="price" class="mb-1 block font-medium">Harga (Rp)</label>
    <input id="price" type="number" name="price" value="{{ old('price', $laundryItem->price ?? '') }}" min="0" step="1" required class="w-full rounded border p-2">
    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label for="description" class="mb-1 block font-medium">Deskripsi</label>
    <textarea id="description" name="description" rows="3" required class="w-full rounded border p-2">{{ old('description', $laundryItem->description ?? '') }}</textarea>
    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label for="image" class="mb-1 block font-medium">Gambar Layanan (Opsional)</label>
    <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png" class="w-full rounded border p-2">
    @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
