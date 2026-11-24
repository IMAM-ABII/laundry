<?php

namespace Database\Seeders;

use App\Models\LaundryItem;
use Illuminate\Database\Seeder;

class LaundryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Reguler',
                'price' => 7000,
                'processing_time' => '3-4 hari',
                'description' => 'Layanan laundry reguler dengan estimasi selesai 3-4 hari.',
            ],
            [
                'name' => 'Ekspres',
                'price' => 12000,
                'processing_time' => '1-2 hari',
                'description' => 'Layanan laundry ekspres dengan estimasi selesai 1-2 hari.',
            ],
            [
                'name' => 'Superkilat',
                'price' => 25000,
                'processing_time' => '5 jam',
                'description' => 'Layanan laundry superkilat dengan estimasi selesai 5 jam.',
            ],
        ] as $service) {
            LaundryItem::updateOrCreate(
                ['name' => $service['name']],
                [
                    ...$service,
                    'category' => 'Cuci',
                ],
            );
        }
    }
}
