@extends('adminlte::page')

@section('title', 'Crear corte de caja')

@section('content_header')
    <h1><b>Crear Nuevo corte de caja</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            @can('cashcuts.store')
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
                            <label class="form-label text-success">Total de Ventas (Ingresos)</label>
                            <input type="text" class="form-control" value="${{ number_format($salesTotal, 2) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-danger">Total de Compras (Retiros)</label>
                            <input type="text" class="form-control text-danger fw-bold" value="-${{ number_format($purchasesTotal, 2) }}" readonly>
                        </div>
                    </div>
                    <hr>
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
                            <label for="realAmount" class="form-label">Monto esperado</label>
                            <input type="text" name="real_amount" id="real_amount" class="form-control" required
                                value=" {{ $realAmount }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="diference" class="form-label">Diferencia</label>
                            <input type="number" name="diference" id="diference" class="form-control" step="0.01" readonly required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('cashcuts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="button" class="btn btn-primary" id="btnSave">
                            <i class="fas fa-save"></i> Guardar corte de caja
                        </button>
                    </div>
                </form>
            @endcan
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const realAmount = document.getElementById('real_amount');
            const finalAmount = document.getElementById('final_amount');
            const diference = document.getElementById('diference');

            function calculateDifference() {
                const real = parseFloat(realAmount.value.replace(/[^0-9.-]/g, '')) || 0;
                const final = parseFloat(finalAmount.value) || 0;
                //const diff = real - final;
                const diff = final - real; // Es más intuitivo ver números rojos (negativos) cuando falta dinero
                diference.value = diff.toFixed(2);

                // Cambia el color del texto si es negativo
                if(diff < 0) {
                    diference.style.color = 'red';
                    diference.style.fontWeight = 'bold';
                } else {
                    diference.style.color = 'green';
                }
            }

            finalAmount.addEventListener('input', calculateDifference);
            calculateDifference();
        });
    </script>

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
