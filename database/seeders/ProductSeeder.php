<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productsToCreate = max(0, 20 - Product::count());
        $statusCount = count(Product::STATUSES);
        $statusCounts = array_fill_keys(
            Product::STATUSES,
            intdiv($productsToCreate, $statusCount),
        );
        $remainder = $productsToCreate % $statusCount;

        foreach (Product::STATUSES as $index => $status) {
            if ($index < $remainder) {
                $statusCounts[$status]++;
            }
        }

        foreach ($statusCounts as $status => $count) {
            if ($count === 0) {
                continue;
            }

            $factory = Product::factory()->count($count);
            $factory = match ($status) {
                'active' => $factory->active(),
                'inactive' => $factory->inactive(),
                'expired' => $factory->expired(),
            };

            $factory->create();
        }
    }
}
