<?php

namespace App\Http\Controllers;

use App\Models\LaundryItem;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        $laundry_items = LaundryItem::query()->orderBy('name')->get();

        return view('customer.index', compact('laundry_items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'items' => ['required', 'array'],
            'items.*' => ['nullable', 'integer', 'min:0'],
        ]);

        $orderedItems = array_filter(
            $validated['items'],
            fn ($quantity) => (int) $quantity > 0,
        );

        if ($orderedItems === []) {
            throw ValidationException::withMessages([
                'items' => 'Pilih minimal satu layanan.',
            ]);
        }

        DB::transaction(function () use ($validated, $orderedItems): void {
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'queue_number' => '0',
                'total_price' => 0,
                'status' => 'processing',
            ]);

            $totalPrice = 0;

            foreach ($orderedItems as $laundryItemId => $quantity) {
                $laundryItem = LaundryItem::query()->findOrFail((int) $laundryItemId);
                $linePrice = $laundryItem->price * (int) $quantity;
                $totalPrice += $linePrice;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'laundry_item_id' => $laundryItem->id,
                    'quantity' => (int) $quantity,
                    'price' => $linePrice,
                ]);
            }

            $order->update(['total_price' => $totalPrice]);
            $order->update([
                'queue_number' => str_pad((string) $order->id, 3, '0', STR_PAD_LEFT),
            ]);
        });

        return redirect()->route('customer.index')
            ->with('success', 'Pesanan berhasil dibuat!');
    }

    public function adminDashboard()
    {
        $orders = Order::query()
            ->with('orderDetails.laundryItem')
            ->latest()
            ->get();
        $summary = [
            'total_transactions' => $orders->count(),
            'total_revenue' => $orders->where('status', 'completed')->sum('total_price'),
            'processing_transactions' => $orders->whereIn('status', ['pending', 'processing'])->count(),
            'completed_transactions' => $orders->where('status', 'completed')->count(),
            'cancelled_transactions' => $orders->where('status', 'cancelled')->count(),
        ];

        return view('dashboard', compact('orders', 'summary'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['processing', 'completed', 'cancelled'])],
        ]);

        $order = Order::query()->findOrFail($id);
        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status pesanan #'.$order->id.' berhasil diperbarui!');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return back()->with('success', 'Pesanan #'.$order->id.' berhasil dihapus!');
    }
}
