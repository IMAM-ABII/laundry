<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-bold leading-tight text-slate-800">Master Data Layanan</h2>
            <a href="{{ route('laundry_items.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-indigo-700 hover:shadow-md">
                + Tambah Layanan
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 font-medium text-green-700 shadow-sm">{{ session('success') }}</div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg shadow-slate-200/50">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px]">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                                <th class="px-5 py-4">Gambar</th>
                                <th class="px-5 py-4">Nama</th>
                                <th class="px-5 py-4">Kategori</th>
                                <th class="px-5 py-4">Harga/kg</th>
                                <th class="px-5 py-4">Estimasi</th>
                                <th class="px-5 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($laundry_items as $laundryItem)
                                <tr class="text-sm transition-colors hover:bg-indigo-50/40">
                                    <td class="px-5 py-4">
                                        @if($laundryItem->image)
                                            <img src="{{ asset('storage/'.$laundryItem->image) }}" alt="{{ $laundryItem->name }}" class="mx-auto h-16 w-16 rounded-xl object-cover shadow-sm ring-1 ring-slate-200">
                                        @else
                                            <span class="flex h-16 w-16 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-100 to-sky-100 text-[10px] font-semibold text-indigo-500">Laundry</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-slate-800">{{ $laundryItem->name }}</td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $laundryItem->category }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 font-bold text-green-700">Rp {{ number_format($laundryItem->price, 0, ',', '.') }}/kg</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $laundryItem->processing_time ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('laundry_items.edit', $laundryItem) }}" class="rounded-lg border border-indigo-100 bg-indigo-50 px-3 py-1.5 font-semibold text-indigo-700 transition hover:border-indigo-200 hover:bg-indigo-100">Edit</a>
                                            <form action="{{ route('laundry_items.destroy', $laundryItem) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus layanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-100 bg-red-50 px-3 py-1.5 font-semibold text-red-600 transition hover:border-red-200 hover:bg-red-100">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <div class="mx-auto flex max-w-sm flex-col items-center gap-2 text-slate-500">
                                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-400">—</span>
                                            <span class="font-semibold text-slate-700">Belum ada layanan</span>
                                            <span class="text-sm">Tambahkan layanan pertama untuk mulai menerima pesanan.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $laundry_items->links() }}</div>
        </div>
    </div>
</x-app-layout>
