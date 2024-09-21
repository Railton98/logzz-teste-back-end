<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('ProductController => index', function () {
    test('should not be able to list products without being authenticated', function () {
        get(route('products.index'))
            ->assertRedirectToRoute('login');
    });

    test('it should be possible to list products', function () {
        $product = Product::factory()->create();

        actingAs($this->user)
            ->get(route('products.index'))
            ->assertOk()
            ->assertSeeText($product->name);
    });

    test('it should be possible to list products with pagination', function () {
        $product = Product::factory()->create();
        Product::factory()->count(10)->create();

        actingAs($this->user)
            ->get(route('products.index'))
            ->assertOk()
            ->assertDontSeeText($product->name);

        actingAs($this->user)
            ->get(route('products.index', ['page' => 2]))
            ->assertOk()
            ->assertSeeText($product->name);
    });
});

describe('ProductController => create', function () {
    test('should not be able to access create products screen without being authenticated', function () {
        get(route('products.create'))
            ->assertRedirectToRoute('login');
    });

    test('should be able to access create products screen', function () {
        actingAs($this->user)
            ->get(route('products.create'))
            ->assertOk()
            ->assertViewIs('products.create')
            ->assertSeeTextInOrder([
                __('Name'),
                __('Price'),
                __('Description'),
                __('Image URL'),
                __('Category'),
                __('Save'),
            ]);
    });
});

describe('ProductController => store', function () {
    test('should not be able to create products without being authenticated', function () {
        post(route('products.store'))
            ->assertRedirectToRoute('login');
    });

    test('should not be able to create products without providing required fields or providing invalid fields', function () {
        actingAs($this->user)
            ->post(route('products.store'))
            ->assertRedirect()
            ->assertSessionHasErrors(['name', 'price', 'description', 'category_id']);

        actingAs($this->user)
            ->post(route('products.store'), [
                'name' => 123,
                'price' => -1,
                'image_url' => 'wrong-url',
                'category_id' => 'wrong-category',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['name', 'price', 'description', 'category_id']);
    });

    test('should not be able to create a product with existing name', function () {
        $category = Category::factory()->create();
        $data = [
            'name' => 'my product test',
            'price' => 87.55,
            'description' => 'my product test description',
            'category_id' => $category->id,
            'image_url' => fake()->imageUrl,
        ];
        $product = Product::factory()->create();

        actingAs($this->user)
            ->post(route('products.store'), [
                ...$data,
                'name' => $product->name,
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['name']);
    });

    test('should be able to create products', function () {
        $category = Category::factory()->create();
        $data = [
            'name' => 'my product test',
            'price' => 87.55,
            'description' => 'my product test description',
            'category_id' => $category->id,
            'image_url' => fake()->imageUrl,
        ];

        actingAs($this->user)
            ->post(route('products.store'), $data)
            ->assertRedirectToRoute('products.index');

        assertDatabaseCount('products', 1);
        assertDatabaseHas('products', $data);
    });
});

describe('ProductController => show', function () {
    test('should not be able to access show products screen without being authenticated', function () {
        get(route('products.show', -1))
            ->assertRedirectToRoute('login');
    });

    test('should not be able to show a non existing product', function () {
        actingAs($this->user)
            ->get(route('products.show', -1))
            ->assertNotFound();
    });

    test('should be able to access show products screen', function () {
        $product = Product::factory()->create();

        actingAs($this->user)
            ->get(route('products.show', $product))
            ->assertOk()
            ->assertViewIs('products.show')
            ->assertSeeTextInOrder([
                __('Edit'),
                __('Delete'),
                $product->name,
                __('Category'),
                $product->category->name,
                __('Price'),
                $product->price,
                __('Product Description'),
                $product->description,
            ]);
    });
});

describe('ProductController => edit', function () {
    test('should not be able to access edit products screen without being authenticated', function () {
        get(route('products.edit', -1))
            ->assertRedirectToRoute('login');
    });

    test('should not be able to edit a non existing product', function () {
        actingAs($this->user)
            ->get(route('products.edit', -1))
            ->assertNotFound();
    });

    test('should be able to access edit products screen', function () {
        $product = Product::factory()->create();

        actingAs($this->user)
            ->get(route('products.edit', $product))
            ->assertOk()
            ->assertViewIs('products.edit')
            ->assertSeeInOrder([
                __('Name'),
                $product->name,
                __('Price'),
                $product->price,
                __('Description'),
                $product->description,
                __('Image URL'),
                $product->image_url,
                __('Category'),
                $product->category->name,
                __('Save'),
            ]);
    });
});

describe('ProductController => update', function () {
    test('should not be able to update products without being authenticated', function () {
        put(route('products.update', -1))
            ->assertRedirectToRoute('login');
    });

    test('should not be able to update a non existing product', function () {
        actingAs($this->user)
            ->put(route('products.update', -1))
            ->assertNotFound();
    });

    test('should not be able to update products without providing required fields or providing invalid fields', function () {
        $product = Product::factory()->create();

        actingAs($this->user)
            ->put(route('products.update', $product))
            ->assertRedirect()
            ->assertSessionHasErrors(['name', 'price', 'description', 'category_id']);

        actingAs($this->user)
            ->put(route('products.update', $product), [
                'name' => 123,
                'price' => -1,
                'image_url' => 'wrong-url',
                'category_id' => 'wrong-category',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['name']);
    });

    test('should be able to update products', function () {
        $imageUrl = fake()->imageUrl;
        $category = Category::factory()->create();
        $product = Product::factory()->create();

        actingAs($this->user)
            ->put(route('products.update', $product), [
                'name' => 'my product test',
                'price' => 87.55,
                'description' => 'my product test description',
                'image_url' => $imageUrl,
                'category_id' => $category->id,
            ])
            ->assertRedirectToRoute('products.index');

        expect($product->fresh())
            ->name->toBe('my product test')
            ->price->toBe(87.55)
            ->description->toBe('my product test description')
            ->category_id->toBe($category->id)
            ->image_url->toBe($imageUrl);
    });
});

describe('ProductController => destroy', function () {
    test('should not be able to delete products without being authenticated', function () {
        delete(route('products.destroy', -1))
            ->assertRedirectToRoute('login');
    });

    test('should not be able to delete a non existing product', function () {
        actingAs($this->user)
            ->delete(route('products.destroy', -1))
            ->assertNotFound();
    });

    test('should be able to delete products', function () {
        $product = Product::factory()->create();

        actingAs($this->user)
            ->delete(route('products.destroy', $product))
            ->assertRedirectToRoute('products.index');
    });
});
