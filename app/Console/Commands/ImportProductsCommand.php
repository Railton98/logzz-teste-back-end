<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\info;

class ImportProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import
                            {--id= : Import a single Product from external API, identified by ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Products (with categories) from external API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $apiUrl = 'https://fakestoreapi.com/products';
        if ($id = $this->option('id')) {
            $apiUrl .= '/'.$id;
        }

        $categoriesImported = 0;
        $productsImported = 0;

        $response = Http::acceptJson()->throw()->get($apiUrl);
        if ($response->successful() && ($data = $response->json()) !== null) {
            if (array_key_exists('id', $data)) {
                $data = [$data];
            }

            foreach ($data as $productData) {
                $category = Category::query()->firstOrCreate(['name' => $productData['category']]);
                if ($category->wasRecentlyCreated) {
                    $categoriesImported++;
                }

                $product = Product::query()->firstOrCreate([
                    'name' => $productData['title'],
                ], [
                    'price' => $productData['price'],
                    'description' => $productData['description'],
                    'image_url' => $productData['image'],
                    'category_id' => $category->id,
                ]);
                if ($product->wasRecentlyCreated) {
                    $productsImported++;
                }
            }
        }

        info("Registers Imported: $categoriesImported Categories | $productsImported Products");
    }
}
