@extends('adminlte::page')

@section('title', 'Corte de Caja')

@section('content_header')
    <h1><b>Corte de caja</b></h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <div class="row mt-4">
                @can('cashcuts.create')
                    <a href="{{ route('cashcuts.create') }}" class="btn btn-primary mb-3">
                        <i class="fas fa-plus"></i> Crear Corte de Caja
                    </a>
                @endcan

                <div class="table-responsive">
                    <table id="balanceTable" class="table table-striped text-center" style="width:100%">
                        <thead class="custom-header">
                            <tr>
                                <th></th>
                                <th>Usuario</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Ventas (Cant.)</th>
                                <th>Monto inicial</th>
                                <th>Monto Final</th>
                                <th>Diferiencia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="balanceTableBody">
                            @foreach ($cashCuts as $cut)
                                @php
                                    $salesTotal = $cut->sales->sum('amountTotal');
                                    $purchasesTotal = $cut->purchases->sum('amountTotal'); 
                                    $expectedTotal = $cut->initial_amount + $salesTotal - $purchasesTotal;
                                    
                                    $jsonDetails = json_encode([
                                        'sales' => $salesTotal,
                                        'purchases' => $purchasesTotal,
                                        'expectedTotal' => $expectedTotal
                                    ]);
                                @endphp
                                <tr data-details="{{ $jsonDetails }}">
                                    <td class="dt-control"></td>
                                    <td>{{ $cut->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cut->start_time)->format('d/m/Y H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cut->end_time)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $cut->sales->count() }}</td>
                                    <td>${{ number_format($cut->initial_amount, 2) }}</td>
                                    <td>${{ number_format($cut->final_amount, 2) }}</td>

                                    <td class="{{ $cut->diference < 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                        ${{ number_format($cut->diference, 2) }}
                                    </td>
                                    
                                    <td>
                                        @can('cashcuts.edit')
                                            <a href="{{ route('cashcuts.edit', $cut->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('cashcuts.destroy')
                                            <form action="{{ route('cashcuts.destroy', $cut->id) }}" method="POST"
                                                class="d-inline" id="formEliminar{{ $cut->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmarEliminacion({{ $cut->id }})">
                                                    <i class="fas fa-trash  "></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <style>
        .content-wrapper {
            background-color: #f1f1f1;
        }

        .card-body {
            background-color: #ffffff;
        }

        .custom-header {
            background-color: #0077B6;
            color: white;
        }

        .custom-header th {
            white-space: nowrap;
        }

        .card-body {
            padding-top: 0;
            padding-bottom: 1rem;
        }

        td.dt-control {
            background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
            cursor: pointer;
            width: 30px;
        }

        tr.dt-hasChild td.dt-control {
            background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
        }
    </style>

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

    <script>
        function format(d) {
            if (!d) return '';
            let f = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });

            return `
                <div class="p-3" style="background-color: white;">
                    <table class="table table-sm table-bordered" style="width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 33%">Ingresos</th>
                                <th style="width: 33%">Retiros</th>
                                <th style="width: 33%">Cantidad esperada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${f.format(d.sales)}</td>
                                <td>${f.format(d.purchases)}</td>
                                <td class="fw-bold">${f.format(d.expectedTotal)}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
        }

        $(document).ready(function() {
            const table = $('#balanceTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                layout: {
                    topStart: 'search',
                    topEnd: null,
                    bottomStart: [{
                            paging: {
                                div: {
                                    className: 'layout-start',
                                    text: 'top2 A'
                                }
                            }
                        },
                        {
                            info: {
                                div: {
                                    className: 'layout-full',
                                    text: 'top2 B'
                                }
                            }
                        }
                    ],
                    bottomEnd: null
                }
            });

            $('#balanceTable tbody').on('click', 'td.dt-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('dt-hasChild');
                } else {
                    var data = tr.data('details');
                    row.child(format(data)).show();
                    tr.addClass('dt-hasChild');
                }
            });
        });
    </script>

    <script>
        function confirmarEliminacion(id) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Esta acción no se puede deshacer!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formEliminar' + id).submit();
                }
            });
        }
    </script>

    <script>
        @if (session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif
    </script>
@stop
