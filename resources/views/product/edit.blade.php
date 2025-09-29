@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
    <h1><b>Editar Producto</b></h1>
@stop

@section('content')
<form id="formEditProduct" action="{{ route('products.update', $product) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Enviamos los IDs de los modelos que estamos editando de forma oculta --}}
    @if($presentation)
        <input type="hidden" name="presentation_to_edit_id" value="{{ $presentation->id }}">
    @endif
    @if($batch)
        <input type="hidden" name="batch_to_edit_id" value="{{ $batch->id }}">
    @endif

    {{-- Tarjeta 1: Información del Producto --}}
    <div class="card shadow mb-4">
        <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">1. Datos del Producto</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"><label>Nombre</label><input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required></div>
                <div class="col-md-4"><label>Categoría</label><select name="category_id" class="form-control" required>@foreach($categories as $cat)<option value="{{$cat->id}}" {{$cat->id == $product->category_id ? 'selected' : ''}}>{{$cat->name}}</option>@endforeach</select></div>
                <div class="col-md-4"><label>Laboratorio</label><select name="laboratory_id" class="form-control" required>@foreach($laboratories as $lab)<option value="{{$lab->id}}" {{$lab->id == $product->laboratory_id ? 'selected' : ''}}>{{$lab->name}}</option>@endforeach</select></div>
            </div>
        </div>
    </div>

    {{-- Tarjeta 2: Información de la Presentación --}}
    <div class="card shadow mb-4">
        <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">2. Datos de la Presentación y Precio</h6></div>
        <div class="card-body">
            @if($presentation)
                <div class="row">
                    <div class="col-md-4"><label>Presentación</label><select name="presentation_id" class="form-control" required>@foreach($presentations as $pres)<option value="{{$pres->id}}" {{$pres->id == $presentation->presentation_id ? 'selected' : ''}}>{{$pres->name}}</option>@endforeach</select></div>
                    <div class="col-md-4"><label>Código de Barras</label><input type="text" name="bar_code" class="form-control" value="{{ old('bar_code', $presentation->bar_code) }}" required></div>
                    <div class="col-md-2"><label>Precio Compra</label><input type="number" name="purchase_price" class="form-control" step="0.01" min="0" value="{{ old('purchase_price', $presentation->purchase_price) }}" required></div>
                    <div class="col-md-2"><label>Precio Venta</label><input type="number" name="sale_price" class="form-control" step="0.01" min="0" value="{{ old('sale_price', $presentation->sale_price) }}" required></div>
                </div>
            @else
                <div class="alert alert-warning">Este producto no tiene una presentación registrada para editar.</div>
            @endif
        </div>
    </div>

    {{-- Tarjeta 3: Información del Lote Inicial --}}
    <div class="card shadow mb-4">
        <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">3. Datos del Lote</h6></div>
        <div class="card-body">
            @if($batch)
                <div class="row">
                    <div class="col-md-4"><label>Número de Lote</label><input type="text" name="batch_number" class="form-control" value="{{ old('batch_number', $batch->batch_number) }}" required></div>
                    <div class="col-md-4"><label>Fecha de Fabricación</label><input type="date" name="creation_date" class="form-control" value="{{ old('creation_date', $batch->creation_date->format('Y-m-d')) }}" required></div>
                    <div class="col-md-4"><label>Fecha de Expiración</label><input type="date" name="expiration_date" class="form-control" value="{{ old('expiration_date', $batch->expiration_date->format('Y-m-d')) }}" required></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4"><label>Stock</label><input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', $batch->stock) }}" required></div>
                    <div class="col-md-4"><label>Stock Mínimo</label><input type="number" name="min_stock" class="form-control" min="0" value="{{ old('min_stock', $batch->min_stock) }}" required></div>
                    <div class="col-md-4"><label>Stock Máximo</label><input type="number" name="max_stock" class="form-control" min="0" value="{{ old('max_stock', $batch->max_stock) }}" required></div>
                </div>
            @else
                <div class="alert alert-warning">Esta presentación no tiene un lote registrado para editar.</div>
            @endif
        </div>
    </div>
    
    <div class="d-flex justify-content-between my-4">
        <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancelar</a>
        <button type="button" id="btnUpdate" class="btn btn-success"><i class="fas fa-sync-alt"></i> Guardar Cambios</button>
    </div>
</form>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btnUpdate').addEventListener('click', function() {
        const form = document.getElementById('formEditProduct');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        Swal.fire({
            title: '¿Guardar Cambios?',
            text: 'Se actualizará la información del producto.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@stop