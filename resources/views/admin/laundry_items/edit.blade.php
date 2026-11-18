<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Edit Layanan</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <form action="{{ route('laundry_items.update', $laundryItem) }}" method="POST" enctype="multipart/form-data" class="space-y-4 rounded-lg border bg-white p-6 shadow-sm">
                @csrf
                @method('PUT')
                @include('admin.laundry_items._form')
                <div class="flex gap-3">
                    <button type="submit" class="rounded bg-blue-600 px-6 py-2.5 font-semibold text-white hover:bg-blue-700">Update Data</button>
                    <a href="{{ route('laundry_items.index') }}" class="rounded bg-gray-200 px-6 py-2.5 font-semibold text-gray-700 hover:bg-gray-300">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
