<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Laundry</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-indigo-50/70 p-4 font-sans text-slate-800 sm:p-6">
    <main class="mx-auto max-w-6xl pt-16">
        <nav class="fixed inset-x-0 top-0 z-40 border-b border-slate-200/80 bg-white/90 shadow-sm backdrop-blur">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
                <a href="{{ route('customer.index') }}" class="flex items-center gap-3 font-bold tracking-tight text-slate-800">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-200">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h10l1 4H6l1-4Zm-1 6h12l1 12H5L6 9Zm3 3h6m-6 3h6" />
                        </svg>
                    </span>
                    <span>Laundry<span class="text-indigo-600">mam</span></span>
                </a>
                <div class="flex items-center gap-2 text-sm">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-lg bg-indigo-600 px-4 py-2 font-semibold text-white shadow-sm transition hover:bg-indigo-700">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-lg border border-slate-200 bg-white px-4 py-2 font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50" type="submit">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg bg-indigo-600 px-4 py-2 font-semibold text-white shadow-sm transition hover:bg-indigo-700">Login</a>
                        <a href="{{ route('register') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2 font-semibold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50">Daftar</a>
                    @endauth
                </div>
            </div>
        </nav>

        <header class="mb-8 overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-blue-600 px-6 py-10 text-center text-white shadow-xl shadow-indigo-200/60 sm:px-10 sm:py-12">
            <p class="mb-3 inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-indigo-100">Bersih, cepat, terpercaya</p>
            <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Layanan Laundry</h1>
            <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-indigo-100 sm:text-base">Pilih layanan yang sesuai kebutuhan, masukkan nomor HP, dan pantau pesanan Anda dengan mudah.</p>
        </header>

        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-center font-semibold text-green-700 shadow-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-center font-semibold text-red-700 shadow-sm">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 font-semibold text-red-700 shadow-sm">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-8 flex flex-wrap justify-center gap-3">
            <button type="button" onclick="filterCategory('all', this)" class="category-filter rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-200 transition hover:-translate-y-0.5">Semua Layanan</button>
            @foreach(['Cuci', 'Setrika', 'Paket'] as $category)
                <button type="button" onclick="filterCategory('{{ $category }}', this)" class="category-filter rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50">{{ $category }}</button>
            @endforeach
        </div>

        <form id="orderForm" action="{{ route('customer.checkout') }}" method="POST">
            @csrf
            <section class="mb-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-lg shadow-slate-200/50 sm:p-7">
                <h2 class="mb-5 flex items-center gap-3 border-b border-slate-100 pb-4 text-lg font-bold text-slate-800">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-sm font-bold text-indigo-700">1</span>
                    Informasi Pemesan
                </h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="customer_name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required maxlength="255" placeholder="Masukkan nama pemesan" class="w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="customer_phone" class="mb-1.5 block text-sm font-semibold text-slate-700">Nomor HP</label>
                        <input type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required maxlength="20" placeholder="Contoh: 081234567890" class="w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </section>

            <h2 class="mb-5 flex items-center gap-3 text-lg font-bold text-slate-800">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-sm font-bold text-indigo-700">2</span>
                Pilih Layanan
            </h2>
            @if($laundry_items->isEmpty())
                <p class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500 shadow-sm">Belum ada layanan tersedia.</p>
            @else
                <section class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($laundry_items as $laundryItem)
                        <article class="laundry-item-card group flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-md shadow-slate-200/50 transition duration-200 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/70" data-category="{{ $laundryItem->category }}">
                            <div>
                                @if($laundryItem->image)
                                    <img src="{{ asset('storage/'.$laundryItem->image) }}" alt="{{ $laundryItem->name }}" class="h-48 w-full object-cover transition duration-300 group-hover:scale-[1.02]">
                                @else
                                    <div class="flex h-48 items-center justify-center bg-gradient-to-br from-indigo-100 via-sky-50 to-blue-100 font-medium text-indigo-400">
                                        <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 3h10l1 4H6l1-4Zm-1 6h12l1 12H5L6 9Zm3 3h6m-6 3h6" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-5">
                                    <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $laundryItem->category }}</span>
                                        <span class="rounded-lg bg-green-50 px-3 py-1.5 text-sm font-bold text-green-700">Rp {{ number_format($laundryItem->price, 0, ',', '.') }}/kg</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800">{{ $laundryItem->name }}</h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ $laundryItem->description }}</p>
                                    @if($laundryItem->processing_time)
                                        <p class="mt-3 inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">Estimasi: {{ $laundryItem->processing_time }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="border-t border-slate-100 bg-slate-50/80 p-5">
                                <label for="item-{{ $laundryItem->id }}" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Jumlah (kg)</label>
                                <input id="item-{{ $laundryItem->id }}" type="number" name="items[{{ $laundryItem->id }}]" min="0" value="{{ old('items.'.$laundryItem->id, 0) }}" data-name="{{ $laundryItem->name }}" data-price="{{ $laundryItem->price }}" class="item-quantity w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-center font-bold text-slate-700 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </article>
                    @endforeach
                </section>
            @endif

            <div class="mt-8 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-indigo-100 bg-white p-4 shadow-lg shadow-indigo-100/50 sm:p-5">
                <p class="text-sm font-semibold text-slate-600">
                    Total Harga
                    <span id="orderTotalPrice" class="ml-2 text-lg font-extrabold text-green-600">Rp 0</span>
                </p>
                <button type="button" onclick="showConfirmationModal()" @disabled($laundry_items->isEmpty()) class="rounded-xl bg-indigo-600 px-7 py-3 font-bold text-white shadow-md shadow-indigo-200 transition hover:-translate-y-0.5 hover:bg-indigo-700 hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-50">Pesan Sekarang</button>
            </div>

            <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
                <div class="w-full max-w-md rounded-2xl border border-white/60 bg-white p-6 shadow-2xl shadow-slate-950/20">
                    <h2 id="modalTitle" class="mb-4 border-b border-slate-100 pb-3 text-xl font-bold text-slate-800">Konfirmasi Pesanan</h2>
                    <div class="mb-4 space-y-2 text-sm text-slate-600">
                        <p class="flex justify-between"><strong>Nama:</strong><span id="modalName" class="font-bold text-gray-900"></span></p>
                        <p class="flex justify-between"><strong>No. HP:</strong><span id="modalPhone" class="font-bold text-gray-900"></span></p>
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
                item.className = 'category-filter rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50';
            });
            button.className = 'category-filter rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-200 transition hover:-translate-y-0.5';

            document.querySelectorAll('.laundry-item-card').forEach((card) => {
                card.classList.toggle('hidden', category !== 'all' && card.dataset.category !== category);
            });
        }

        function updateOrderTotal() {
            let total = 0;

            document.querySelectorAll('.item-quantity').forEach((input) => {
                const quantity = Number.parseInt(input.value, 10) || 0;
                if (quantity > 0) {
                    total += quantity * Number(input.dataset.price);
                }
            });

            document.getElementById('orderTotalPrice').textContent = `Rp ${total.toLocaleString('id-ID')}`;
        }

        function showConfirmationModal() {
            const form = document.getElementById('orderForm');
            if (!form.reportValidity()) return;

            const name = document.getElementById('customer_name').value.trim();
            const phone = document.getElementById('customer_phone').value.trim();
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
            document.getElementById('modalPhone').textContent = phone;
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

        document.querySelectorAll('.item-quantity').forEach((input) => {
            input.addEventListener('input', updateOrderTotal);
        });
        updateOrderTotal();
    </script>
</body>
</html>
