<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_product_can_be_created_and_viewed(): void
    {
        $response = $this->post(route('products.store'), [
            'name' => 'Pupiņas',
            'quantity' => 12,
            'description' => 'Sarkanās pupiņas',
            'expiration_date' => '2027-03-15',
            'status' => 'active',
        ]);

        $product = Product::firstOrFail();

        $response->assertRedirect(route('products.show', $product));
        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Pupiņas')
            ->assertSee('12');
    }

    public function test_a_product_can_be_updated(): void
    {
        $product = Product::factory()->create(['name' => 'Vecais nosaukums']);

        $response = $this->put(route('products.update', $product), [
            'name' => 'Jaunais nosaukums',
            'quantity' => 25,
            'description' => 'Atjaunināts apraksts',
            'expiration_date' => '2027-06-20',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('products.show', $product));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Jaunais nosaukums',
            'quantity' => 25,
        ]);
    }

    public function test_product_quantity_can_be_increased_and_decreased_from_the_show_page(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee(route('products.quantity.adjust', $product));

        $this->patch(route('products.quantity.adjust', $product), ['adjustment' => 7])
            ->assertRedirect(route('products.show', $product))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 17]);

        $this->patch(route('products.quantity.adjust', $product), ['adjustment' => -4])
            ->assertRedirect(route('products.show', $product))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 13]);
    }

    public function test_quantity_cannot_be_reduced_below_zero(): void
    {
        $product = Product::factory()->create(['quantity' => 2]);

        $this->from(route('products.show', $product))
            ->patch(route('products.quantity.adjust', $product), ['adjustment' => -3])
            ->assertRedirect(route('products.show', $product))
            ->assertSessionHasErrors('adjustment');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 2]);
    }

    public function test_quantity_adjustment_must_be_a_nonzero_integer(): void
    {
        $product = Product::factory()->create(['quantity' => 2]);

        $this->from(route('products.show', $product))
            ->patch(route('products.quantity.adjust', $product), ['adjustment' => 0])
            ->assertSessionHasErrors('adjustment');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 2]);
    }

    public function test_ajax_quantity_adjustment_returns_the_updated_quantity_as_json(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);

        $this->patchJson(route('products.quantity.adjust', $product), ['adjustment' => 4])
            ->assertOk()
            ->assertJson([
                'message' => 'Produkta daudzums veiksmīgi atjaunināts.',
                'quantity' => 14,
            ]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 14]);
    }

    public function test_ajax_quantity_adjustment_returns_json_when_quantity_would_be_negative(): void
    {
        $product = Product::factory()->create(['quantity' => 2]);

        $this->patchJson(route('products.quantity.adjust', $product), ['adjustment' => -3])
            ->assertUnprocessable()
            ->assertJson([
                'message' => 'Daudzums noliktavā nevar būt mazāks par nulli.',
            ]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 2]);
    }
}
