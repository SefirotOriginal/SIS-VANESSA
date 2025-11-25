@extends('adminlte::page')

@section('title', 'Crear corte de caja')

@section('content_header')
    <h1><b>Crear Nuevo corte de caja</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form id="formLabs" action="{{ route('cashcuts.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="user_id" class="form-label">User</label>
                        <input type="text" name="name" id="name" class="form-control" required
                            value="{{ $user->id }}" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="start_time" class="form-label">Hora de inicio</label>
                        <input type="datetime-local" name="start_time" id="start_time" class="form-control"
                            value="{{ $startTime }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="end_time" class="form-label">Hora de fin</label>
                        <input type="datetime-local" name="end_time" id="end_time" class="form-control"
                            value="{{ $endTime }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="startAmount" class="form-label">Monto inicial</label>
                        <input type="number" name="initial_amount" id="initial_amount" class="form-control"
                            value="{{ $initialAmount }}" step="0.01" required readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="finalAmount" class="form-label">Monto Final</label>
                        <input type="number" name="final_amount" id="final_amount" class="form-control" step="0.01"
                            required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="realAmount" class="form-label">Monto real</label>
                        <input type="text" name="real_amount" id="real_amount" class="form-control" required
                            value=" {{ $realAmount }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="diference" class="form-label">Diferiencia</label>
                        <input type="number" name="diference" id="diference" class="form-control" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('laboratories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="button" class="btn btn-primary" id="btnSave">
                        <i class="fas fa-save"></i> Guardar corte de caja
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formLabs');
            const btnSave = document.getElementById('btnSave');

            btnSave.addEventListener('click', function() {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: "¿Crear el corte de caja?",
                    text: "Se registrará un nuevo corte de caja en el sistema.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: "Sí, crear",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    {{-- Script para calcular la diferiencia entre el monto real y el monto final --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const realAmount = document.getElementById('real_amount');
            const finalAmount = document.getElementById('final_amount');
            const diference = document.getElementById('diference');

            function calculateDifference() {
                const real = parseFloat(realAmount.value.replace(/[^0-9.-]/g, '')) || 0;
                const final = parseFloat(finalAmount.value) || 0;
                const diff = real - final;
                diference.value = diff.toFixed(2);
            }

            finalAmount.addEventListener('input', calculateDifference);
            calculateDifference(); // Calculate on load
        });
    </script>

    {{-- Script para mostrar errores de validación del backend --}}
    <script>
        @if ($errors->any())
            Swal.fire({
                title: 'Error al crear el corte de caja',
                icon: 'error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Cerrar'
            });
        @endif
    </script>
@stop
