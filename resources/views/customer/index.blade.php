<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Laundry</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 p-6 font-sans">
    <main class="mx-auto max-w-5xl">
        <header class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-gray-800">Layanan Laundry</h1>
            <p class="mt-1 text-sm text-gray-500">Pilih layanan dan masukkan nomor antrean Anda</p>
        </header>

        <div class="fixed right-6 top-4 z-50 flex items-center gap-3 text-sm">
            @auth
                <a href="{{ route('dashboard') }}" class="rounded-lg border border-blue-600 bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-lg border border-gray-300 bg-white px-4 py-2 font-semibold text-gray-700 hover:bg-gray-100" type="submit">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-lg border border-blue-600 bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Login admin</a>
                <a href="{{ route('register') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 font-semibold text-gray-700 hover:bg-gray-100">Daftar</a>
            @endauth
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-400 bg-green-100 px-4 py-3 text-center font-semibold text-green-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-xl border border-red-400 bg-red-100 px-4 py-3 text-center font-semibold text-red-700">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-400 bg-red-100 px-4 py-3 font-semibold text-red-700">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-8 flex flex-wrap justify-center gap-3">
            <button type="button" onclick="filterCategory('all', this)" class="category-filter rounded-full bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow">Semua Layanan</button>
            @foreach(['Cuci', 'Setrika', 'Paket'] as $category)
                <button type="button" onclick="filterCategory('{{ $category }}', this)" class="category-filter rounded-full border bg-white px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-200">{{ $category }}</button>
            @endforeach
        </div>

        <form id="orderForm" action="{{ route('customer.checkout') }}" method="POST">
            @csrf
            <section class="mb-6 rounded-xl border bg-white p-6 shadow-sm">
                <h2 class="mb-4 border-b pb-2 text-lg font-bold text-gray-700">1. Informasi Pemesan</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="customer_name" class="mb-1 block text-sm font-semibold text-gray-600">Nama Lengkap</label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required maxlength="255" placeholder="Masukkan nama pemesan" class="w-full rounded-lg border px-4 py-2">
                    </div>
                    <div>
                        <label for="queue_number" class="mb-1 block text-sm font-semibold text-gray-600">Nomor Antrean</label>
                        <input type="text" id="queue_number" name="queue_number" value="{{ old('queue_number') }}" required maxlength="20" placeholder="Contoh: 05" class="w-full rounded-lg border px-4 py-2">
                    </div>
                </div>
            </section>

            <h2 class="mb-4 text-lg font-bold text-gray-700">2. Pilih Layanan</h2>
            @if($laundry_items->isEmpty())
                <p class="rounded-lg border bg-white p-6 text-center text-gray-500">Belum ada layanan tersedia.</p>
            @else
                <section class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    @foreach($laundry_items as $laundryItem)
                        <article class="laundry-item-card flex flex-col justify-between overflow-hidden rounded-xl border bg-white shadow-sm" data-category="{{ $laundryItem->category }}">
                            <div>
                                @if($laundryItem->image)
                                    <img src="{{ asset('storage/'.$laundryItem->image) }}" alt="{{ $laundryItem->name }}" class="h-40 w-full object-cover">
                                @else
                                    <div class="flex h-40 items-center justify-center bg-gray-200 font-medium text-gray-400">Tanpa Gambar</div>
                                @endif
                                <div class="p-4">
                                    <div class="mb-2 flex items-center justify-between">
                                        <span class="rounded bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">{{ $laundryItem->category }}</span>
                                        <span class="font-bold text-green-600">Rp {{ number_format($laundryItem->price, 0, ',', '.') }}</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">{{ $laundryItem->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $laundryItem->description }}</p>
                                </div>
                            </div>
                            <div class="border-t bg-gray-50 p-4">
                                <label for="item-{{ $laundryItem->id }}" class="mb-1 block text-xs font-semibold text-gray-500">Jumlah</label>
                                <input id="item-{{ $laundryItem->id }}" type="number" name="items[{{ $laundryItem->id }}]" min="0" value="{{ old('items.'.$laundryItem->id, 0) }}" data-name="{{ $laundryItem->name }}" data-price="{{ $laundryItem->price }}" class="item-quantity w-full rounded-lg border px-3 py-1.5 text-center font-bold text-gray-700">
                            </div>
                        </article>
                    @endforeach
                </section>
            @endif

            <div class="mt-8 text-right">
                <button type="button" onclick="showConfirmationModal()" @disabled($laundry_items->isEmpty()) class="rounded-xl bg-blue-600 px-8 py-3 font-bold text-white shadow-md transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">Pesan Sekarang</button>
            </div>

            <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                    <h2 id="modalTitle" class="mb-4 border-b pb-3 text-xl font-bold text-gray-800">Konfirmasi Pesanan</h2>
                    <div class="mb-4 space-y-2 text-sm text-gray-600">
                        <p class="flex justify-between"><strong>Nama:</strong><span id="modalName" class="font-bold text-gray-900"></span></p>
                        <p class="flex justify-between"><strong>No. Antrean:</strong><span id="modalQueue" class="font-bold text-gray-900"></span></p>
                    </div>
                    <div class="mb-4 max-h-48 overflow-y-auto border-y py-3">
                        <p class="mb-2 text-xs font-semibold uppercase text-gray-400">Rincian Item</p>
                        <ul id="modalItemList" class="space-y-2 text-sm"></ul>
                    </div>
                    <p class="mb-6 flex items-center justify-between text-lg font-bold text-gray-800">
                        <span>Total Pembayaran:</span>
                        <span id="modalTotalPrice" class="text-xl text-green-600">Rp 0</span>
                    </p>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeConfirmationModal()" class="w-1/2 rounded-xl bg-gray-200 py-2.5 font-semibold text-gray-700 hover:bg-gray-300">Batal</button>
                        <button type="submit" class="w-1/2 rounded-xl bg-green-600 py-2.5 font-bold text-white shadow hover:bg-green-700">Kirim Pesanan</button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script>
        function filterCategory(category, button) {
            document.querySelectorAll('.category-filter').forEach((item) => {
                item.className = 'category-filter rounded-full border bg-white px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-200';
            });
            button.className = 'category-filter rounded-full bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow';

            document.querySelectorAll('.laundry-item-card').forEach((card) => {
                card.classList.toggle('hidden', category !== 'all' && card.dataset.category !== category);
            });
        }

        function showConfirmationModal() {
            const form = document.getElementById('orderForm');
            if (!form.reportValidity()) return;

            const name = document.getElementById('customer_name').value.trim();
            const queue = document.getElementById('queue_number').value.trim();
            const itemList = document.getElementById('modalItemList');
            let total = 0;
            let hasOrder = false;
            itemList.replaceChildren();

            document.querySelectorAll('.item-quantity').forEach((input) => {
                const quantity = Number.parseInt(input.value, 10) || 0;
                if (quantity <= 0) return;

                hasOrder = true;
                const lineTotal = quantity * Number(input.dataset.price);
                total += lineTotal;

                const row = document.createElement('li');
                row.className = 'flex justify-between gap-4';
                const description = document.createElement('span');
                description.textContent = `${input.dataset.name} x${quantity}`;
                const price = document.createElement('span');
                price.textContent = `Rp ${lineTotal.toLocaleString('id-ID')}`;
                row.append(description, price);
                itemList.append(row);
            });

            if (!hasOrder) {
                alert('Pilih minimal satu layanan dengan jumlah lebih dari 0.');
                return;
            }

            document.getElementById('modalName').textContent = name;
            document.getElementById('modalQueue').textContent = queue;
            document.getElementById('modalTotalPrice').textContent = `Rp ${total.toLocaleString('id-ID')}`;
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeConfirmationModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>
