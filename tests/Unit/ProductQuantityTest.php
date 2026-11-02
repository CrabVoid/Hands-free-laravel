<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductQuantityTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_increases_the_product_quantity(): void
    {
        $product = Product::factory()->create(['quantity' => 8]);

        $result = $product->increaseQuantity(5);

        $this->assertTrue($result);
        $this->assertSame(13, $product->quantity);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 13]);
    }

    public function test_it_decreases_quantity_without_going_below_zero(): void
    {
        $product = Product::factory()->create(['quantity' => 8]);

        $result = $product->decreaseQuantity(3);

        $this->assertTrue($result);
        $this->assertSame(5, $product->quantity);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 5]);
    }

    public function test_it_rejects_a_decrease_that_would_make_quantity_negative(): void
    {
        $product = Product::factory()->create(['quantity' => 2]);

        $result = $product->decreaseQuantity(3);

        $this->assertFalse($result);
        $this->assertSame(2, $product->fresh()->quantity);
    }

    public function test_it_rejects_non_positive_quantity_increases(): void
    {
        $product = Product::factory()->create(['quantity' => 2]);

        $this->assertFalse($product->increaseQuantity(0));
        $this->assertFalse($product->increaseQuantity(-1));

        $this->assertSame(2, $product->fresh()->quantity);
    }

    public function test_it_rejects_non_positive_quantity_decreases(): void
    {
        $product = Product::factory()->create(['quantity' => 2]);

        $this->assertFalse($product->decreaseQuantity(0));
        $this->assertFalse($product->decreaseQuantity(-1));

        $this->assertSame(2, $product->fresh()->quantity);
    }
}
