<?php

use App\Console\Commands\ImportProductsCommand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

test('should to be able import products', function () {
    Http::fake([
        'https://fakestoreapi.com/products' => Http::response([
            [
                'id' => 1,
                'title' => 'Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops',
                'price' => 109.95,
                'description' => 'Your perfect pack for everyday use and walks in the forest. Stash your laptop (up to 15 inches) in the padded sleeve, your everyday',
                'category' => "men's clothing",
                'image' => 'https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg',
            ],
            [
                'id' => 5,
                'title' => "John Hardy Women's Legends Naga Gold & Silver Dragon Station Chain Bracelet",
                'price' => 695,
                'description' => "From our Legends Collection, the Naga was inspired by the mythical water dragon that protects the ocean's pearl. Wear facing inward to be bestowed with love and abundance, or outward for protection.",
                'category' => 'jewelery',
                'image' => 'https://fakestoreapi.com/img/71pWzhdJNwL._AC_UL640_QL65_ML3_.jpg',
            ],
        ]),
    ]);

    artisan(ImportProductsCommand::class)
        ->assertSuccessful();

    assertDatabaseCount(Category::class, 2);
    assertDatabaseCount(Product::class, 2);
});

test('should to be able import specific product', function () {
    $productId = 1;
    Http::fake([
        'https://fakestoreapi.com/products/'.$productId => Http::response([
            'id' => 1,
            'title' => 'Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops',
            'price' => 109.95,
            'description' => 'Your perfect pack for everyday use and walks in the forest. Stash your laptop (up to 15 inches) in the padded sleeve, your everyday',
            'category' => "men's clothing",
            'image' => 'https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg',
        ]),
    ]);

    artisan(ImportProductsCommand::class, ['--id' => $productId])
        ->assertSuccessful();

    assertDatabaseCount(Category::class, 1);
    assertDatabaseHas(Category::class, [
        'name' => "men's clothing",
    ]);
    assertDatabaseCount(Product::class, 1);
    assertDatabaseHas(Product::class, [
        'name' => 'Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops',
        'price' => 109.95,
        'description' => 'Your perfect pack for everyday use and walks in the forest. Stash your laptop (up to 15 inches) in the padded sleeve, your everyday',
    ]);
});
