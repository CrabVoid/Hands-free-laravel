<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->productValidationRules());

        $product = Product::create($validated);

        return redirect()->route('products.show', $product)
            ->with('status', 'Produkts veiksmīgi izveidots.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $product->load('tags');

        return view('products.show', compact('product'));
    }

    public function adjustQuantity(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'adjustment' => ['required', 'integer', 'not_in:0'],
        ]);

        $adjustment = (int) $validated['adjustment'];
        $quantityWasUpdated = $adjustment > 0
            ? $product->increaseQuantity($adjustment)
            : $product->decreaseQuantity(abs($adjustment));

        if (! $quantityWasUpdated) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Daudzums noliktavā nevar būt mazāks par nulli.',
                ], 422);
            }

            return redirect()->route('products.show', $product)
                ->withErrors(['adjustment' => 'Daudzums noliktavā nevar būt mazāks par nulli.']);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Produkta daudzums veiksmīgi atjaunināts.',
                'quantity' => $product->quantity,
            ]);
        }

        return redirect()->route('products.show', $product)
            ->with('status', 'Produkta daudzums veiksmīgi atjaunināts.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate($this->productValidationRules());

        $product->update($validated);

        return redirect()->route('products.show', $product)
            ->with('status', 'Produkta izmaiņas veiksmīgi saglabātas.');
    }

    public function updateTags(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['required', 'string', 'max:50'],
        ]);

        $tagNames = collect($validated['tags'] ?? [])
            ->map(fn (string $name): string => trim($name))
            ->filter()
            ->unique(fn (string $name): string => mb_strtolower($name));

        $tagIds = $tagNames->map(function (string $name): int {
            $tag = Tag::query()
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                ->first();

            return ($tag ?? Tag::create(['name' => $name]))->getKey();
        });

        $product->tags()->sync($tagIds);

        return redirect()->route('products.show', $product)
            ->with('status', 'Produkta birkas veiksmīgi atjauninātas.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('status', 'Produkts veiksmīgi izdzēsts.');
    }

    /**
     * @return array<string, array<int, string|In>>
     */
    private function productValidationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],
            'expiration_date' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(Product::STATUSES)],
        ];
    }
}
