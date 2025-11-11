<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\Category;
use App\Models\Laboratory;
use App\Models\Presentation;
use App\Models\Batch;
use App\Models\ProductPresentation;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Crear categorías
        $categories = Category::factory()->count(5)->create();
        // Crear laboratorios
        $laboratories = Laboratory::factory()->count(5)->create();
        // Crear presentaciones
        $presentations = Presentation::factory()->count(5)->create();
        // Crear lotes
        $batches = Batch::factory()->count(10)->create();
        // Crear productos y asociar presentaciones y lotes
        Product::factory()->count(10)->create()->each(function ($product) use ($presentations, $batches) {
            // Asociar una presentación aleatoria
            $presentation = $presentations->random();

            $productPresentation = ProductPresentation::factory()->create([
                'product_id' => $product->id,
                'presentation_id' => $presentation->id,
            ]);
            // Asociar un lote aleatorio a la presentación del producto
            $batch = $batches->random();
            $batch->product_presentation_id = $productPresentation->id;
            $batch->save();
        });
    }
}
