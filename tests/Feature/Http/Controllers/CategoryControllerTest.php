<?php

use App\Models\Category;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('CategoryController => index', function () {
    test('should not be able to list categories without being authenticated', function () {
        get(route('categories.index'))
            ->assertRedirectToRoute('login');
    });

    test('it should be possible to list categories', function () {
        $category = Category::factory()->create();

        actingAs($this->user)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSeeText($category->name);
    });

    test('it should be possible to list categories with pagination', function () {
        $category = Category::factory()->create();
        Category::factory()->count(10)->create();

        actingAs($this->user)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertDontSeeText($category->name);

        actingAs($this->user)
            ->get(route('categories.index', ['page' => 2]))
            ->assertOk()
            ->assertSeeText($category->name);
    });
});

describe('CategoryController => create', function () {
    test('should not be able to access create categories screen without being authenticated', function () {
        get(route('categories.create'))
            ->assertRedirectToRoute('login');
    });

    test('should be able to access create categories screen', function () {
        actingAs($this->user)
            ->get(route('categories.create'))
            ->assertOk()
            ->assertViewIs('categories.create')
            ->assertSeeTextInOrder([__('Name'), __('Save')]);
    });
});

describe('CategoryController => store', function () {
    test('should not be able to create categories without being authenticated', function () {
        post(route('categories.store'))
            ->assertRedirectToRoute('login');
    });

    test('should not be able to create categories without providing required fields or providing invalid fields', function () {
        actingAs($this->user)
            ->post(route('categories.store'))
            ->assertRedirect()
            ->assertSessionHasErrors(['name']);

        actingAs($this->user)
            ->post(route('categories.store'), [
                'name' => 123,
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['name']);
    });

    test('should not be able to create a category with existing name', function () {
        $category = Category::factory()->create();

        actingAs($this->user)
            ->post(route('categories.store'), [
                'name' => $category->name,
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['name']);
    });

    test('should be able to create categories', function () {
        actingAs($this->user)
            ->post(route('categories.store'), [
                'name' => 'my category test',
            ])
            ->assertRedirectToRoute('categories.index');

        assertDatabaseCount('categories', 1);
        assertDatabaseHas('categories', [
            'name' => 'my category test',
        ]);
    });
});

describe('CategoryController => edit', function () {
    test('should not be able to access edit categories screen without being authenticated', function () {
        get(route('categories.edit', -1))
            ->assertRedirectToRoute('login');
    });

    test('should be able to access edit categories screen', function () {
        $category = Category::factory()->create();

        actingAs($this->user)
            ->get(route('categories.edit', $category))
            ->assertOk()
            ->assertViewIs('categories.edit')
            ->assertSeeInOrder([__('Name'), $category->name, __('Save')]);
    });
});

describe('CategoryController => update', function () {
    test('should not be able to update categories without being authenticated', function () {
        put(route('categories.update', -1))
            ->assertRedirectToRoute('login');
    });

    test('should not be able to update categories without providing required fields or providing invalid fields', function () {
        $category = Category::factory()->create();

        actingAs($this->user)
            ->put(route('categories.update', $category))
            ->assertRedirect()
            ->assertSessionHasErrors(['name']);

        actingAs($this->user)
            ->put(route('categories.update', $category), [
                'name' => 123,
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['name']);
    });

    test('should be able to update categories', function () {
        $category = Category::factory()->create();

        actingAs($this->user)
            ->put(route('categories.update', $category), [
                'name' => 'my category test',
            ])
            ->assertRedirectToRoute('categories.index');

        expect($category->fresh()->name)->toBe('my category test');
    });
});
