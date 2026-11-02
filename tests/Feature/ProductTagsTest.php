<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTagsTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_and_new_tags_can_be_attached_to_a_product_and_shared(): void
    {
        $firstProduct = Product::factory()->create();
        $secondProduct = Product::factory()->create();
        $sharedTag = Tag::factory()->create(['name' => 'Seasonal']);
        $firstProduct->tags()->attach($sharedTag);

        $this->put(route('products.tags.update', $secondProduct), [
            'tags' => ['Seasonal', 'Local harvest'],
        ])->assertRedirect(route('products.show', $secondProduct));

        $this->assertDatabaseCount('tags', 2);
        $this->assertSame(
            ['Local harvest', 'Seasonal'],
            $secondProduct->fresh()->tags()->orderBy('name')->pluck('name')->all(),
        );
        $this->assertTrue($firstProduct->fresh()->tags->contains($sharedTag));
        $this->assertTrue($secondProduct->fresh()->tags->contains($sharedTag));
    }

    public function test_tag_updates_remove_tags_that_are_no_longer_selected(): void
    {
        $product = Product::factory()->create();
        $tag = Tag::factory()->create(['name' => 'Clearance']);
        $product->tags()->attach($tag);

        $this->put(route('products.tags.update', $product), ['tags' => []])
            ->assertRedirect(route('products.show', $product));

        $this->assertDatabaseMissing('product_tag', [
            'product_id' => $product->id,
            'tag_id' => $tag->id,
        ]);
        $this->assertDatabaseHas('tags', ['id' => $tag->id]);
    }

    public function test_tag_search_returns_existing_partial_matches(): void
    {
        $matchingTag = Tag::factory()->create(['name' => 'Green apples']);
        Tag::factory()->create(['name' => 'Coffee']);

        $this->getJson(route('tags.search', ['query' => 'app']))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['id' => $matchingTag->id, 'name' => 'Green apples']);
    }

    public function test_the_product_show_page_renders_existing_tags_and_autocomplete_form(): void
    {
        $product = Product::factory()->create();
        $product->tags()->attach(Tag::factory()->create(['name' => 'Organic']));

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Organic')
            ->assertSee(route('tags.search'))
            ->assertSee(route('products.tags.update', $product));
    }
}
