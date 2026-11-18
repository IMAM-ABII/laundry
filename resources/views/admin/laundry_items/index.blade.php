<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Master Data Layanan</h2>
            <a href="{{ route('laundry_items.create') }}" class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">
                + Tambah Layanan
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded border border-green-400 bg-green-100 p-3 text-green-700">{{ session('success') }}</div>
            @endif

            <div class="overflow-x-auto rounded-lg border bg-white shadow-sm">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-gray-100 text-left text-sm text-gray-600">
                            <th class="p-3">Gambar</th>
                            <th class="p-3">Nama</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Harga</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laundry_items as $laundryItem)
                            <tr class="border-b text-sm">
                                <td class="p-3">
                                    @if($laundryItem->image)
                                        <img src="{{ asset('storage/'.$laundryItem->image) }}" alt="{{ $laundryItem->name }}" class="mx-auto h-16 w-16 rounded object-cover">
                                    @else
                                        <span class="text-xs text-gray-400">Tanpa gambar</span>
                                    @endif
                                </td>
                                <td class="p-3 font-semibold">{{ $laundryItem->name }}</td>
                                <td class="p-3">{{ $laundryItem->category }}</td>
                                <td class="p-3 font-bold text-green-600">Rp {{ number_format($laundryItem->price, 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap p-3">
                                    <a href="{{ route('laundry_items.edit', $laundryItem) }}" class="mr-3 font-semibold text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('laundry_items.destroy', $laundryItem) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus layanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">Belum ada layanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $laundry_items->links() }}</div>
        </div>
    </div>
</x-app-layout>
