<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Laboratory;
use App\Models\Presentation;
use App\Models\ProductPresentation;
use Illuminate\Support\Facades\DB;
use App\Models\Batch;

class ProductWizard extends Component
 {
    // Paso actual
    public $currentStep = 1;

    // Datos del producto
    public $name, $usage, $description, $category_id, $laboratory_id;

    // Datos de presentación
    public $presentation_id, $bar_code, $content, $formula, $purchase_price, $sale_price;

    // Datos del lote
    public $batch_number, $creation_date, $expiration_date, $stock, $min_stock, $max_stock;

    // Datos auxiliares
    public $categories = [];
    public $laboratories = [];
    public $presentations = [];
    public $category_name, $laboratory_name, $presentation_name;

    public function mount()
    {
        $this->categories = Category::all();
        $this->laboratories = Laboratory::all();
        $this->presentations = Presentation::all();
        $this->creation_date = now()->format('Y-m-d');
    }

     public function render()
     {
         return view('livewire.product-wizard');
     }

    // Navegación entre pasos
    public function nextStep()
    {
        $this->validateStep();
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    // Validación por pasos
    public function validateStep()
    {
        $rules = [];
        if ($this->currentStep == 1) {
            $rules = [
                'name' => 'required|string|max:255',
                'usage' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'laboratory_id' => 'required|exists:laboratories,id',
            ];
        } elseif ($this->currentStep == 2) {
            $rules = [
                'presentation_id' => 'required|exists:presentations,id',
                'bar_code' => 'required|string|unique:product_presentations,bar_code',
                'content' => 'nullable|string|max:255',
                'formula' => 'nullable|string|max:255',
                'purchase_price' => 'required|numeric|min:0',
                'sale_price' => 'required|numeric|min:' . $this->purchase_price,
            ];
        } elseif ($this->currentStep == 3) {
            $rules = [
                'batch_number' => 'required|string|max:255',
                'creation_date' => 'required|date',
                'expiration_date' => 'required|date|after_or_equal:creation_date',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'required|integer|min:0',
                'max_stock' => 'required|integer|min:' . $this->min_stock,
            ];
        }

        $this->validate($rules);
    }

    // Crear categoría dinámicamente
    public function createCategory()
    {
        if ($this->category_name) {
            $category = Category::create([
                'name' => $this->category_name,
                'type' => 'general', // Asumiendo que el tipo es 'product'
            ]);
            $this->categories->push($category);
            $this->category_id = $category->id;
            $this->category_name = '';
            session()->flash('success', 'Categoría creada exitosamente');
        }
    }

    // Crear laboratorio dinámicamente
    public function createLaboratory()
    {
        if ($this->laboratory_name) {
            $laboratory = Laboratory::create(['name' => $this->laboratory_name]);
            $this->laboratories->push($laboratory);
            $this->laboratory_id = $laboratory->id;
            $this->laboratory_name = '';
            session()->flash('success', 'Laboratorio creado exitosamente');
        }
    }

    // Crear presentación dinámicamente
    public function createPresentation()
    {
        if ($this->presentation_name) {
            $presentation = Presentation::create(['name' => $this->presentation_name]);
            $this->presentations->push($presentation);
            $this->presentation_id = $presentation->id;
            $this->presentation_name = '';
            session()->flash('success', 'Presentación creada exitosamente');
        }
    }

    // Guardar todo
    public function save()
    {
        $this->validateStep(); // Validar último paso

        DB::beginTransaction();
        try {
            // 1. Crear producto
            $product = Product::create([
                'name' => $this->name,
                'usage' => $this->usage,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'laboratory_id' => $this->laboratory_id,
                'status' => true, // O el estado que corresponda
            ]);

            // 2. Crear presentación del producto
            $productPresentation = ProductPresentation::create([
                'product_id' => $product->id,
                'presentation_id' => $this->presentation_id,
                'bar_code' => $this->bar_code,
                'content' => $this->content,
                'formula' => $this->formula,
                'purchase_price' => $this->purchase_price,
                'sale_price' => $this->sale_price,
            ]);

            // 3. Crear lote
            Batch::create([
                'product_presentation_id' => $productPresentation->id,
                'batch_number' => $this->batch_number,
                'creation_date' => $this->creation_date,
                'expiration_date' => $this->expiration_date,
                'stock' => $this->stock,
                'min_stock' => $this->min_stock,
                'max_stock' => $this->max_stock,
            ]);

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
            /*
            session()->flash('success', 'Producto creado exitosamente con su presentación y lote');
            $this->resetForm();
            */

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    // Resetear formulario
    public function resetForm()
    {
        $this->reset();
        $this->currentStep = 1;
        $this->creation_date = now()->format('Y-m-d');
    }
}
