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
            'description' => 'Layanan reguler.',
        ]);

        $this->get(route('customer.index'))
            ->assertOk()
            ->assertSee('Cuci Reguler')
            ->assertSee('Pilih Layanan');
    }

    public function test_checkout_uses_server_prices_and_saves_order_details(): void
    {
        $item = LaundryItem::create([
            'name' => 'Cuci Reguler',
            'category' => 'Cuci',
            'price' => 25000,
            'description' => 'Layanan reguler.',
        ]);

        $this->post(route('customer.checkout'), [
            'customer_name' => 'Budi',
            'queue_number' => '05',
            'items' => [$item->id => 2],
        ])->assertRedirect(route('customer.index'));

        $order = Order::query()->firstOrFail();

        $this->assertSame('05', $order->queue_number);
        $this->assertSame(50000, $order->total_price);
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
            ->assertSee('Daftar Pesanan Masuk');

        $this->actingAs($admin)
            ->get(route('laundry_items.create'))
            ->assertOk()
            ->assertSee('Tambah Layanan');

        $this->actingAs($admin)
            ->post(route('laundry_items.store'), [
                'name' => 'Setrika Reguler',
                'category' => 'Setrika',
                'price' => 5000,
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
            'status' => 'pending',
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

    public function test_checkout_requires_at_least_one_selected_service(): void
    {
        $this->from(route('customer.index'))
            ->post(route('customer.checkout'), [
                'customer_name' => 'Budi',
                'queue_number' => '05',
                'items' => [1 => 0],
            ])
            ->assertRedirect(route('customer.index'))
            ->assertSessionHasErrors('items');

        $this->assertDatabaseCount('orders', 0);
    }
}
