<!DOCTYPE html>
<html lang="es">

<head>
    <title>Gestión de Sucursales</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div class="page-container">

        @section('sucursales')
        @endsection

        @include('layouts.sidebar')

        <div class="page-content">
            <div class="main-wrapper">

                <div class="row">
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="mb-0">Gestión Rippi</h4>
                                    <div class="d-flex justify-content-end gap-2">

                                        <!-- Botón Importar -->
                                        <button type="button" class="btn px-4"
                                            style="background-color:#e06d2a; color:#fff; border-radius: 8px;"
                                            data-bs-toggle="modal" data-bs-target="#modalImportar">
                                            <i class="fas fa-upload me-1"></i> Importar Datos
                                        </button>

                                        <!-- Botón Exportar -->
                                        <a href="{{ route('rappi.plantilla') }}" class="btn px-4"
                                            style="background-color:#e06d2a; color:#fff; border-radius: 8px;">
                                            <i class="fas fa-download me-1"></i> Exportar Plantilla
                                        </a>

                                    </div>

                                </div>

                                <div class="table-responsive mt-4">
                                    <div class="table-responsive">
                                        <table id="tablaRappi" class="table table-striped table-hover w-100">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>ID Orden</th>
                                                    <th>Tienda</th>
                                                    <th>Estado</th>
                                                    <th class="text-end">Venta Bruta</th>
                                                    <th class="text-end">A Transferir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pagos as $pago)
                                                    <tr>
                                                        <td
                                                            data-sort="{{ $pago->fecha_creacion_orden ? $pago->fecha_creacion_orden->timestamp : 0 }}">
                                                            {{ $pago->fecha_creacion_orden ? $pago->fecha_creacion_orden->format('d/m/Y H:i') : 'N/A' }}
                                                        </td>

                                                        <td class="fw-bold">{{ $pago->id_orden }}</td>
                                                        <td>{{ $pago->nombre_tienda }}</td>

                                                        <td>
                                                            @if (in_array($pago->estado_orden, ['delivered', 'finished']))
                                                                <span class="badge bg-success">Entregado</span>
                                                            @elseif($pago->estado_orden == 'canceled')
                                                                <span class="badge bg-danger">Cancelado</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-secondary">{{ $pago->estado_orden }}</span>
                                                            @endif
                                                        </td>

                                                        <td class="text-end">
                                                            $ {{ number_format($pago->venta_bruta, 0, ',', '.') }}
                                                        </td>

                                                        <td
                                                            class="text-end fw-bold {{ $pago->valor_a_transferir < 0 ? 'text-danger' : 'text-success' }}">
                                                            $
                                                            {{ number_format($pago->valor_a_transferir, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- MODAL IMPORTAR RAPPI -->
        <div class="modal fade" id="modalImportar" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Subir Archivo de Rappi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <form action="{{ route('rappi.upload') }}" method="POST" enctype="multipart/form-data" id="formImportar">
                        @csrf
                        <div class="modal-body">
                            
                            <div class="mb-3">
                                <label for="archivoInput" class="form-label">Selecciona el archivo (.xlsx o .csv)</label>
                                <input class="form-control" type="file" id="archivoInput" name="archivo" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                <div class="form-text text-muted">
                                    Asegúrate de que el archivo tenga los encabezados correctos.
                                </div>
                            </div>

                            <div id="loadingMessage" class="alert alert-info d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Procesando datos, por favor espera...
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success" id="btnSubir" style="background-color:#e06d2a; color:#fff; border-radius: 8px;">
                                Subir e Importar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <!-- DATATABLES -->
        <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('#tablaRappi').DataTable({
                    "order": [
                        [0, "desc"]
                    ], // Ordenar por Fecha (Columna 0) Descendente
                    "pageLength": 25, // Mostrar 25 registros por defecto
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });
        </script>


        <!-- NOTIFICACIONES SWEETALERT -->
        @if (Session::has('success'))
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000
                });
            </script>
        @endif

        @if (Session::has('error'))
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 13000
                });
            </script>
        @endif

    </div>

</body>

</html>
