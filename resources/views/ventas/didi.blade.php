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
                                    <h4 class="mb-0">Gestión Didi</h4>
                                    <div class="d-flex justify-content-end gap-2">

                                        <!-- Botón Importar -->
                                        <button type="button" class="btn px-4"
                                            style="background-color:#e06d2a; color:#fff; border-radius: 8px;"
                                            data-bs-toggle="modal" data-bs-target="#importarDidiModal">
                                            <i class="fas fa-upload me-1"></i> Importar Datos
                                        </button>

                                        <!-- Botón Exportar -->
                                        <a href="{{ route('didi.template') }}" class="btn px-4"
                                            style="background-color:#e06d2a; color:#fff; border-radius: 8px;">
                                            <i class="fas fa-download me-1"></i> Exportar Plantilla
                                        </a>

                                    </div>

                                </div>

                                <div class="table-responsive mt-4">
                                    <table id="didiTable" class="table table-striped table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Restaurante</th>
                                                <th>Total</th>
                                                <th>Comisión</th>
                                                <th>Ganancia Viaje</th>
                                                <th>Método Pago</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>{{ $order->order_id }}</td>
                                                    <td>{{ $order->store_name }}</td>
                                                    <td>${{ number_format($order->billing_amount, 2) }}</td>
                                                    <td>${{ number_format($order->commission_fee, 2) }}</td>
                                                    <td>${{ number_format($order->trip_earnings, 2) }}</td>
                                                    <td>{{ $order->payment_method }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($order->billing_time)->format('d/m/Y H:i') }}
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

        <!-- MODAL IMPORTAR DIDI -->
        <div class="modal fade" id="importarDidiModal" tabindex="-1" aria-labelledby="importarDidiLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 12px; overflow: hidden;">

                    <!-- HEADER -->
                    <div class="modal-header" style="background-color:#e06d2a; color:white; padding: 18px 24px;">
                        <h5 class="modal-title" id="importarDidiLabel">
                            <i class="fas fa-file-import me-2"></i> Importar Archivo DiDi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            style="filter: brightness(0) invert(1);"></button>
                    </div>

                    <!-- BODY -->
                    <div class="modal-body" style="padding: 28px 30px;">
                        <form action="{{ route('didi.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-bold">Seleccionar archivo Excel (.xlsx)</label>
                                <input type="file" name="file" class="form-control" required>
                                <small class="text-muted">Sube el reporte de DiDi Billing Report.</small>
                            </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer d-flex justify-content-end"
                        style="border-top: 1px solid #eee; padding: 16px 24px;">
                        <button type="submit" class="btn px-4"
                            style="background-color:#e06d2a; color:#fff; border-radius: 8px;">
                            <i class="fas fa-upload me-1"></i> Importar Datos
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
                $('#didiTable').DataTable({
                    "order": [
                        [0, 'desc']
                    ],
                    "pageLength": 25,
                    "language": {
                        "lengthMenu": "Mostrar _MENU_ registros",
                        "zeroRecords": "No se encontraron resultados",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        "infoEmpty": "No hay registros",
                        "infoFiltered": "(filtrado de _MAX_ registros)",
                        "search": "Buscar:",
                        "paginate": {
                            "next": "Siguiente",
                            "previous": "Anterior"
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
                    timer: 3000
                });
            </script>
        @endif

    </div>

</body>

</html>
