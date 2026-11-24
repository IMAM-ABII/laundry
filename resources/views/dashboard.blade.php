<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-bold leading-tight tracking-tight text-slate-800">
                {{ __('Rekap Transaksi Laundry') }}
            </h2>
            <div class="flex flex-wrap items-center gap-3">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('laundry_items.index') }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-indigo-700 hover:shadow-md">
                        Data Layanan
                    </a>
                @endif
                <a href="{{ route('customer.index') }}" target="_blank" rel="noopener" class="rounded-lg bg-slate-800 px-4 py-2.5 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-900 hover:shadow-md">
                    Lihat Layanan Customer
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 border-l-4 border-l-green-500 bg-green-50 p-4 font-medium text-green-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(auth()->user()->role === 'admin')
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <div class="rounded-lg border border-gray-200 border-l-4 border-l-indigo-500 bg-white p-4 shadow-sm transition-shadow hover:shadow-md">
                        <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                        <p class="mt-2 text-2xl font-bold text-gray-800">{{ number_format($summary['total_transactions'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 border-l-4 border-l-green-500 bg-white p-4 shadow-sm transition-shadow hover:shadow-md">
                        <p class="text-sm font-medium text-gray-500">Total Pendapatan (Lunas)</p>
                        <p class="mt-2 text-2xl font-bold text-green-600">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 border-l-4 border-l-amber-500 bg-white p-4 shadow-sm transition-shadow hover:shadow-md">
                        <p class="text-sm font-medium text-gray-500">Sedang Diproses</p>
                        <p class="mt-2 text-2xl font-bold text-amber-600">{{ number_format($summary['processing_transactions'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 border-l-4 border-l-emerald-500 bg-white p-4 shadow-sm transition-shadow hover:shadow-md">
                        <p class="text-sm font-medium text-gray-500">Transaksi Selesai</p>
                        <p class="mt-2 text-2xl font-bold text-gray-800">{{ number_format($summary['completed_transactions'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 border-l-4 border-l-red-500 bg-white p-4 shadow-sm transition-shadow hover:shadow-md">
                        <p class="text-sm font-medium text-gray-500">Transaksi Dibatalkan</p>
                        <p class="mt-2 text-2xl font-bold text-red-600">{{ number_format($summary['cancelled_transactions'], 0, ',', '.') }}</p>
                    </div>
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg shadow-slate-200/50">
                <div class="overflow-x-auto p-4 text-slate-800 sm:p-6">
                    <table class="w-full min-w-[900px] border-collapse text-left">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-600">
                            <tr>
                                <th class="whitespace-nowrap border-b border-gray-200 px-4 py-3"># ID</th>
                                <th class="whitespace-nowrap border-b border-gray-200 px-4 py-3">Pelanggan</th>
                                <th class="whitespace-nowrap border-b border-gray-200 px-4 py-3">No. HP</th>
                                <th class="whitespace-nowrap border-b border-gray-200 px-4 py-3">Rincian Pesanan</th>
                                <th class="whitespace-nowrap border-b border-gray-200 px-4 py-3">Total Harga</th>
                                <th class="whitespace-nowrap border-b border-gray-200 px-4 py-3">Status</th>
                                <th class="whitespace-nowrap border-b border-gray-200 px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($orders as $order)
                                <tr class="transition-colors hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-4 py-4 align-middle font-bold text-gray-700">#{{ $order->id }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 align-middle font-medium text-gray-800">{{ $order->customer_name }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 align-middle text-gray-600">{{ $order->customer_phone ?? '—' }}</td>
                                    <td class="px-4 py-4 align-middle">
                                        <ul class="list-inside list-disc space-y-2 text-gray-700">
                                            @foreach($order->orderDetails as $detail)
                                                <li>
                                                    <strong>{{ $detail->laundryItem?->name ?? 'Layanan dihapus' }}</strong>
                                                    x{{ $detail->quantity }}
                                                    <span class="whitespace-nowrap text-xs text-gray-500">(Rp {{ number_format($detail->price, 0, ',', '.') }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 align-middle font-bold text-green-600">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 align-middle">
                                        @if(in_array($order->status, ['pending', 'processing'], true))
                                            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">PROSES</span>
                                        @elseif($order->status === 'completed')
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">SELESAI</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">DIBATALKAN</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 align-middle text-center">
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <label class="sr-only" for="status-{{ $order->id }}">Ubah status pesanan {{ $order->id }}</label>
                                            <select id="status-{{ $order->id }}" name="status" onchange="this.form.submit()" class="w-full max-w-36 rounded-md border border-gray-300 bg-white px-2 py-1.5 text-xs font-semibold text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                                <option value="processing" @selected(in_array($order->status, ['pending', 'processing'], true))>Proses</option>
                                                <option value="completed" @selected($order->status === 'completed')>Selesai / Lunas</option>
                                                <option value="cancelled" @selected($order->status === 'cancelled')>Batalkan</option>
                                            </select>
                                        </form>
                                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="mt-2" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition-colors hover:border-red-300 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-200">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-6 text-center text-gray-500">Belum ada pesanan masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
