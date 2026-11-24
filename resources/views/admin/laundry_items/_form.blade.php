<div>
    <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Layanan</label>
    <input id="name" type="text" name="name" value="{{ old('name', $laundryItem->name ?? '') }}" maxlength="255" required class="w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">
    @error('name') <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label for="category" class="mb-1.5 block text-sm font-semibold text-slate-700">Kategori</label>
    <select id="category" name="category" required class="w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">
        @foreach(['Cuci', 'Setrika', 'Paket'] as $category)
            <option value="{{ $category }}" @selected(old('category', $laundryItem->category ?? '') === $category)>{{ $category }}</option>
        @endforeach
    </select>
    @error('category') <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label for="price" class="mb-1.5 block text-sm font-semibold text-slate-700">Harga per kg (Rp)</label>
    <input id="price" type="number" name="price" value="{{ old('price', $laundryItem->price ?? '') }}" min="0" step="1" required class="w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">
    @error('price') <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label for="processing_time" class="mb-1.5 block text-sm font-semibold text-slate-700">Estimasi Pengerjaan</label>
    <input id="processing_time" type="text" name="processing_time" value="{{ old('processing_time', $laundryItem->processing_time ?? '') }}" maxlength="100" required class="w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">
    @error('processing_time') <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi</label>
    <textarea id="description" name="description" rows="3" required class="w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $laundryItem->description ?? '') }}</textarea>
    @error('description') <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
<div>
    <label for="image" class="mb-1.5 block text-sm font-semibold text-slate-700">Gambar Layanan (Opsional)</label>
    <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png" class="w-full rounded-xl border border-slate-200 bg-slate-50 p-2.5 text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
    @error('image') <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
</div>
