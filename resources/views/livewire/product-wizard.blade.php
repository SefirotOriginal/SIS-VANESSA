<div>
    {{-- Mostramos los mensajes de error/éxito de la sesión --}}
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Paso 1: Datos del Producto --}}
    @if ($currentStep == 1)
        <div class="card shadow">
            <div class="card-header"><h5 class="m-0 font-weight-bold text-primary">Paso 1: Datos del Producto</h5></div>
            <div class="card-body">
                {{-- INICIO DEL CONTENIDO DEL FORMULARIO (AHORA SÍ INCLUIDO) --}}
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" wire:model.defer="name" class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Uso</label>
                    <input type="text" wire:model.defer="usage" class="form-control @error('usage') is-invalid @enderror">
                    @error('usage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea wire:model.defer="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoría</label>
                    <div class="input-group">
                        <select wire:model.defer="category_id" class="form-control @error('category_id') is-invalid @enderror">
                            <option value="">Seleccione una categoría</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" wire:model.defer="category_name" class="form-control" placeholder="O crear nueva categoría">
                        <button wire:click="createCategory" class="btn btn-outline-secondary" type="button">Crear</button>
                    </div>
                    @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Laboratorio</label>
                    <div class="input-group">
                        <select wire:model.defer="laboratory_id" class="form-control @error('laboratory_id') is-invalid @enderror">
                            <option value="">Seleccione un laboratorio</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" wire:model.defer="laboratory_name" class="form-control" placeholder="O crear nuevo laboratorio">
                        <button wire:click="createLaboratory" class="btn btn-outline-secondary" type="button">Crear</button>
                    </div>
                    @error('laboratory_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                {{-- FIN DEL CONTENIDO DEL FORMULARIO --}}
            </div>
        </div>
    @endif

    {{-- Paso 2: Presentación y Precios --}}
    @if ($currentStep == 2)
        <div class="card shadow">
            <div class="card-header"><h5 class="m-0 font-weight-bold text-primary">Paso 2: Presentación y Precios</h5></div>
            <div class="card-body">
                {{-- INICIO DEL CONTENIDO DEL FORMULARIO --}}
                <div class="mb-3">
                    <label class="form-label">Presentación</label>
                    <div class="input-group">
                        <select wire:model.defer="presentation_id" class="form-control @error('presentation_id') is-invalid @enderror">
                            <option value="">Seleccione una presentación</option>
                            @foreach($presentations as $pres)
                                <option value="{{ $pres->id }}">{{ $pres->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" wire:model.defer="presentation_name" class="form-control" placeholder="O crear nueva presentación">
                        <button wire:click="createPresentation" class="btn btn-outline-secondary" type="button">Crear</button>
                    </div>
                     @error('presentation_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Código de Barras</label>
                    <input type="text" wire:model.defer="bar_code" class="form-control @error('bar_code') is-invalid @enderror">
                    @error('bar_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Precio de Compra</label>
                        <input type="number" wire:model.defer="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" min="0" step="0.01">
                        @error('purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Precio de Venta</label>
                        <input type="number" wire:model.defer="sale_price" class="form-control @error('sale_price') is-invalid @enderror" min="0" step="0.01">
                        @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                {{-- FIN DEL CONTENIDO DEL FORMULARIO --}}
            </div>
        </div>
    @endif

    {{-- Paso 3: Lote y Stock --}}
    @if ($currentStep == 3)
        <div class="card shadow">
            <div class="card-header"><h5 class="m-0 font-weight-bold text-primary">Paso 3: Lote y Stock</h5></div>
            <div class="card-body">
                {{-- INICIO DEL CONTENIDO DEL FORMULARIO --}}
                <div class="mb-3">
                    <label class="form-label">Número de Lote</label>
                    <input type="text" wire:model.defer="batch_number" class="form-control @error('batch_number') is-invalid @enderror">
                    @error('batch_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de Creación</label>
                        <input type="date" wire:model.defer="creation_date" class="form-control @error('creation_date') is-invalid @enderror">
                        @error('creation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de Vencimiento</label>
                        <input type="date" wire:model.defer="expiration_date" class="form-control @error('expiration_date') is-invalid @enderror">
                        @error('expiration_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stock Inicial</label>
                        <input type="number" wire:model.defer="stock" class="form-control @error('stock') is-invalid @enderror" min="0">
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stock Mínimo</label>
                        <input type="number" wire:model.defer="min_stock" class="form-control @error('min_stock') is-invalid @enderror" min="0">
                        @error('min_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stock Máximo</label>
                        <input type="number" wire:model.defer="max_stock" class="form-control @error('max_stock') is-invalid @enderror" min="0">
                        @error('max_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                {{-- FIN DEL CONTENIDO DEL FORMULARIO --}}
            </div>
        </div>
    @endif

    {{-- Botones de Navegación --}}
    <div class="d-flex justify-content-between mt-4">
        @if ($currentStep > 1)
            <button wire:click="previousStep" type="button" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Anterior
            </button>
        @else
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
        @endif

        @if ($currentStep < 3)
            <button wire:click="nextStep" type="button" class="btn btn-primary">
                Siguiente <i class="fas fa-arrow-right"></i>
            </button>
        @else
            <button wire:click="save" type="button" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar Producto
            </button>
        @endif
    </div>
</div>