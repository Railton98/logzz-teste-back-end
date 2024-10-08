<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $products = Product::query()
            ->when(
                $request->boolean('has_image'),
                fn (Builder $query) => $query->whereNotNull('image_url')
                    ->where('image_url', '!=', ''),
            )
            ->when(
                $request->boolean('no_has_image'),
                fn (Builder $query) => $query->whereNull('image_url')
                    ->orWhere('image_url', '=', ''),
            )
            ->when(
                $request->query('id'),
                fn (Builder $query) => $query->where('id', '=', $request->query('id'))
            )
            ->when(
                $request->query('category_id'),
                fn (Builder $query) => $query->where('category_id', '=', $request->query('category_id'))
            )
            ->when(
                $request->query('name'),
                fn (Builder $query) => $query->where('name', 'like', '%'.$request->query('name').'%')
                    ->whereHas(
                        'category',
                        fn (Builder $query) => $query->where('name', 'like', '%'.$request->query('name').'%')
                    )
            )
            ->with('category:id,name')
            ->orderByDesc('id')
            ->paginate(10, ['id', 'name', 'price', 'description', 'image_url', 'category_id']);

        return view('products.index', [
            'categories' => Category::query()->get(['id', 'name']),
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create', [
            'categories' => Category::query()->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        Product::query()->create([
            'name' => $request->string('name'),
            'price' => $request->float('price'),
            'description' => $request->string('description'),
            'image_url' => $request->string('image_url'),
            'category_id' => $request->integer('category_id'),
        ]);

        return to_route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $product->load('category:id,name');

        return view('products.show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Category::query()->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update([
            'name' => $request->string('name'),
            'price' => $request->float('price'),
            'description' => $request->string('description'),
            'image_url' => $request->string('image_url'),
            'category_id' => $request->integer('category_id'),
        ]);

        return to_route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('products.index');
    }
}
