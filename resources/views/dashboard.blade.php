<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Daftar Pesanan Masuk') }}
            </h2>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('laundry_items.index') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm hover:bg-indigo-700">
                    Kelola Layanan
                </a>
                <a href="{{ route('customer.index') }}" target="_blank" rel="noopener" class="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                    Lihat Layanan Customer
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded border-l-4 border-green-500 bg-green-100 p-4 font-medium text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto p-6 text-gray-900">
                    <table class="w-full border-collapse text-left">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-700">
                            <tr>
                                <th class="border-b p-4"># ID</th>
                                <th class="border-b p-4">Pelanggan</th>
                                <th class="border-b p-4">No. Antrean</th>
                                <th class="border-b p-4">Rincian Pesanan</th>
                                <th class="border-b p-4">Total Harga</th>
                                <th class="border-b p-4">Status</th>
                                <th class="border-b p-4 text-center">Aksi Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-sm">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4 font-bold text-gray-700">#{{ $order->id }}</td>
                                    <td class="p-4 font-medium">{{ $order->customer_name }}</td>
                                    <td class="p-4">
                                        <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-800">
                                            Antrean {{ $order->queue_number }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <ul class="list-inside list-disc space-y-1 text-gray-600">
                                            @foreach($order->orderDetails as $detail)
                                                <li>
                                                    <strong>{{ $detail->laundryItem?->name ?? 'Layanan dihapus' }}</strong>
                                                    x{{ $detail->quantity }}
                                                    <span class="text-xs text-gray-400">(Rp {{ number_format($detail->price, 0, ',', '.') }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="p-4 font-bold text-green-600">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4">
                                        @if($order->status === 'pending')
                                            <span class="rounded bg-yellow-100 px-2.5 py-1 text-xs font-bold text-yellow-800">PENDING</span>
                                        @elseif($order->status === 'completed')
                                            <span class="rounded bg-green-100 px-2.5 py-1 text-xs font-bold text-green-800">SELESAI</span>
                                        @else
                                            <span class="rounded bg-red-100 px-2.5 py-1 text-xs font-bold text-red-800">DIBATALKAN</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <label class="sr-only" for="status-{{ $order->id }}">Ubah status pesanan {{ $order->id }}</label>
                                            <select id="status-{{ $order->id }}" name="status" onchange="this.form.submit()" class="rounded border border-gray-300 bg-white p-1.5 text-xs font-semibold shadow-sm">
                                                <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                                <option value="completed" @selected($order->status === 'completed')>Selesai / Lunas</option>
                                                <option value="cancelled" @selected($order->status === 'cancelled')>Batalkan</option>
                                            </select>
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
