<?php

namespace Tests\Feature;

use App\Models\LaundryItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaundryWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_the_laundry_catalog(): void
    {
        LaundryItem::create([
            'name' => 'Cuci Reguler',
            'category' => 'Cuci',
            'price' => 25000,
            'processing_time' => '3-4 hari',
            'description' => 'Layanan reguler.',
        ]);

        $this->get(route('customer.index'))
            ->assertOk()
            ->assertSee('Cuci Reguler')
            ->assertSee('Pilih Layanan')
            ->assertSee('Total Harga')
            ->assertSee('id="orderTotalPrice"', false)
            ->assertSee('Pesan Sekarang');
    }

    public function test_checkout_uses_server_prices_and_saves_order_details(): void
    {
        $item = LaundryItem::create([
            'name' => 'Cuci Reguler',
            'category' => 'Cuci',
            'price' => 25000,
            'processing_time' => '3-4 hari',
            'description' => 'Layanan reguler.',
        ]);

        $this->post(route('customer.checkout'), [
            'customer_name' => 'Budi',
            'customer_phone' => '081234567890',
            'items' => [$item->id => 2],
        ])->assertRedirect(route('customer.index'));

        $order = Order::query()->firstOrFail();

        $this->assertSame('081234567890', $order->customer_phone);
        $this->assertSame(50000, $order->total_price);
        $this->assertSame('processing', $order->status);
        $this->assertDatabaseHas('order_details', [
            'order_id' => $order->id,
            'laundry_item_id' => $item->id,
            'quantity' => 2,
            'price' => 50000,
        ]);
    }

    public function test_registered_users_cannot_access_admin_pages(): void
    {
        $user = User::create([
            'name' => 'Laundry User',
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $this->assertSame('user', $user->role);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertForbidden();

        $order = Order::create([
            'customer_name' => 'Budi',
            'queue_number' => '01',
            'total_price' => 25000,
            'status' => 'processing',
        ]);

        $this->actingAs($user)
            ->delete(route('admin.orders.destroy', $order))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('customer.index'));
    }

    public function test_admin_can_create_update_and_delete_a_laundry_item(): void
    {
        $admin = User::create([
            'name' => 'Admin Laundry',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Rekap Transaksi Laundry');

        $this->actingAs($admin)
            ->get(route('laundry_items.create'))
            ->assertOk()
            ->assertSee('Tambah Layanan');

        $this->actingAs($admin)
            ->post(route('laundry_items.store'), [
                'name' => 'Setrika Reguler',
                'category' => 'Setrika',
                'price' => 5000,
                'processing_time' => '3-4 hari',
                'description' => 'Layanan setrika.',
            ])
            ->assertRedirect(route('laundry_items.index'));

        $item = LaundryItem::query()->firstOrFail();

        $this->actingAs($admin)
            ->get(route('laundry_items.edit', $item))
            ->assertOk()
            ->assertSee('Edit Layanan');

        $this->actingAs($admin)
            ->put(route('laundry_items.update', $item), [
                'name' => 'Setrika Ekspres',
                'category' => 'Setrika',
                'price' => 15000,
                'processing_time' => '1-2 hari',
                'description' => 'Layanan setrika ekspres.',
            ])
            ->assertRedirect(route('laundry_items.index'));

        $this->assertDatabaseHas('laundry_items', [
            'id' => $item->id,
            'name' => 'Setrika Ekspres',
            'price' => 15000,
        ]);

        $this->actingAs($admin)
            ->delete(route('laundry_items.destroy', $item))
            ->assertRedirect(route('laundry_items.index'));

        $this->assertDatabaseMissing('laundry_items', ['id' => $item->id]);
    }

    public function test_admin_can_update_an_order_status(): void
    {
        $admin = User::create([
            'name' => 'Admin Laundry',
            'email' => 'status-admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);
        $order = Order::create([
            'customer_name' => 'Budi',
            'queue_number' => '05',
            'total_price' => 25000,
            'status' => 'processing',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.updateStatus', $order->id), [
                'status' => 'completed',
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
    }

    public function test_admin_can_delete_an_order_and_its_details_from_the_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin Laundry',
            'email' => 'delete-admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);
        $item = LaundryItem::create([
            'name' => 'Cuci Reguler',
            'category' => 'Cuci',
            'price' => 25000,
            'processing_time' => '3-4 hari',
            'description' => 'Layanan reguler.',
        ]);
        $order = Order::create([
            'customer_name' => 'Budi',
            'queue_number' => '01',
            'total_price' => 25000,
            'status' => 'processing',
        ]);
        $detail = $order->orderDetails()->create([
            'laundry_item_id' => $item->id,
            'quantity' => 1,
            'price' => 25000,
        ]);

        $this->actingAs($admin)
            ->from(route('dashboard'))
            ->delete(route('admin.orders.destroy', $order))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('success', 'Pesanan #'.$order->id.' berhasil dihapus!');

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('order_details', ['id' => $detail->id]);
    }

    public function test_admin_dashboard_summarizes_transactions_and_completed_revenue(): void
    {
        $admin = User::create([
            'name' => 'Admin Laundry',
            'email' => 'summary-admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);
        Order::create([
            'customer_name' => 'Pelanggan Proses',
            'queue_number' => '01',
            'total_price' => 10000,
            'status' => 'processing',
        ]);
        Order::create([
            'customer_name' => 'Pelanggan Lama',
            'queue_number' => '02',
            'total_price' => 15000,
            'status' => 'pending',
        ]);
        Order::create([
            'customer_name' => 'Pelanggan Lunas',
            'queue_number' => '03',
            'total_price' => 25000,
            'status' => 'completed',
        ]);
        Order::create([
            'customer_name' => 'Pelanggan Batal',
            'queue_number' => '04',
            'total_price' => 50000,
            'status' => 'cancelled',
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Total Transaksi')
            ->assertSee('Total Pendapatan (Lunas)')
            ->assertSee('Sedang Diproses')
            ->assertSee('Transaksi Selesai')
            ->assertSee('Transaksi Dibatalkan')
            ->assertSee('Rp 25.000')
            ->assertViewHas('summary', [
                'total_transactions' => 4,
                'total_revenue' => 25000,
                'processing_transactions' => 2,
                'completed_transactions' => 1,
                'cancelled_transactions' => 1,
            ]);
    }

    public function test_checkout_requires_at_least_one_selected_service(): void
    {
        $this->from(route('customer.index'))
            ->post(route('customer.checkout'), [
                'customer_name' => 'Budi',
                'customer_phone' => '081234567890',
                'items' => [1 => 0],
            ])
            ->assertRedirect(route('customer.index'))
            ->assertSessionHasErrors('items');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_cashier_can_access_transactions_but_not_manage_services(): void
    {
        User::create([
            'name' => 'Kasir Laundry',
            'email' => 'kasir@example.com',
            'password' => 'password123',
            'role' => 'kasir',
        ]);

        $this->post(route('login'), [
            'email' => 'kasir@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Rekap Transaksi Laundry')
            ->assertDontSee('Total Transaksi');

        $this->get(route('laundry_items.index'))
            ->assertForbidden();
    }

    public function test_checkout_requires_a_customer_phone_number(): void
    {
        $this->from(route('customer.index'))
            ->post(route('customer.checkout'), [
                'customer_name' => 'Budi',
                'items' => [1 => 1],
            ])
            ->assertRedirect(route('customer.index'))
            ->assertSessionHasErrors('customer_phone');

        $this->assertDatabaseCount('orders', 0);
    }
}
