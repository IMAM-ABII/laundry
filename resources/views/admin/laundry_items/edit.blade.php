<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold leading-tight text-slate-800">Edit Layanan</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('laundry_items.update', $laundryItem) }}" method="POST" enctype="multipart/form-data" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-lg shadow-slate-200/50 sm:p-8">
                @csrf
                @method('PUT')
                @include('admin.laundry_items._form')
                <div class="flex gap-3">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 font-semibold text-white shadow-sm transition hover:bg-indigo-700">Update Data</button>
                    <a href="{{ route('laundry_items.index') }}" class="rounded-lg border border-slate-200 bg-white px-6 py-2.5 font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
