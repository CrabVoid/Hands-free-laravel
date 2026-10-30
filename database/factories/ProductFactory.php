<?php

namespace Database\Factories;

use App\Models\Product;
use Bezhanov\Faker\Provider\Commerce;
use DateTimeInterface;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker();
        $status = $faker->randomElement(Product::STATUSES);
        $name = $faker->productName();
        $category = $faker->category();

        return [
            'name' => $name,
            'quantity' => $faker->numberBetween(0, 250),
            'description' => "{$name} is a practical product from the {$category} collection, made for everyday use.",
            'expiration_date' => $this->expirationDateForStatus($status)->format('Y-m-d'),
            'status' => $status,
        ];
    }

    public function active(): static
    {
        return $this->forStatus('active');
    }

    public function inactive(): static
    {
        return $this->forStatus('inactive');
    }

    public function expired(): static
    {
        return $this->forStatus('expired');
    }

    private function forStatus(string $status): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => $status,
            'expiration_date' => $this->expirationDateForStatus($status)->format('Y-m-d'),
        ]);
    }

    private function expirationDateForStatus(string $status): DateTimeInterface
    {
        $faker = $this->faker();

        if ($status === 'expired') {
            return $faker->dateTimeBetween('-2 years', '-1 day');
        }

        return $faker->dateTimeBetween('+1 day', '+2 years');
    }

    private function faker(): Generator
    {
        $faker = fake();
        $hasCommerceProvider = false;

        foreach ($faker->getProviders() as $provider) {
            if ($provider instanceof Commerce) {
                $hasCommerceProvider = true;

                break;
            }
        }

        if (! $hasCommerceProvider) {
            $faker->addProvider(new Commerce($faker));
        }

        return $faker;
    }
}
