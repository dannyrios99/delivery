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

        @section('horas')
            class="active-page"
        @endsection

        @include('layouts.sidebar')

        <div class="page-content">
            <div class="main-wrapper">
                <div class="container-fluid">

                    {{-- CARD: Información de la hora extra --}}
                    <div class="card shadow-sm mb-3">

                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock-history me-2"></i>
                                <strong>Detalle de Hora Extra</strong>
                            </div>

                            <a href="{{ route('horas-extras.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                <!-- Empleado -->
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <small class="text-muted">Empleado</small>
                                        <div class="fw-semibold fs-6">
                                            {{ $horaExtra->nombre }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Total horas -->
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100 text-center">
                                        <small class="text-muted d-block">Total horas</small>
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            {{ $horaExtra->total_horas }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Fecha -->
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <small class="text-muted">Fecha</small>
                                        <div class="fw-semibold">
                                            {{ $horaExtra->fecha }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Horario -->
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <small class="text-muted">Horario</small>
                                        <div class="fw-semibold">
                                            {{ $horaExtra->hora_inicio }} – {{ $horaExtra->hora_fin }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Observación -->
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <small class="text-muted">Observación</small>
                                        <div>
                                            {{ $horaExtra->observacion ?? 'Sin observaciones' }}
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                    {{-- CARD: Actividades --}}
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>Actividades realizadas</strong>

                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalActividadSoporte">
                                <i class="bi bi-plus-circle"></i> Agregar
                            </button>
                        </div>

                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table id="tabla-actividades"
                                    class="table table-bordered table-striped align-middle w-100">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tipo de soporte</th>
                                            <th>Sistema</th>
                                            <th>Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($horaExtra->actividadesSoporte as $actividad)
                                            <tr>
                                                <td>{{ $actividad->tipo_soporte }}</td>
                                                <td>{{ $actividad->sistema }}</td>
                                                <td>{{ $actividad->descripcion }}</td>
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

        <div class="modal fade" id="modalActividadSoporte" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <form method="POST" action="{{ route('actividades-soporte.store', $horaExtra->id) }}">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Registrar actividad realizada</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="row g-3">

                                <!-- Tipo de soporte -->
                                <div class="col-md-6">
                                    <label class="form-label">Tipo de soporte</label>
                                    <select name="tipo_soporte" class="form-select" required>
                                        <option value="">Seleccione</option>
                                        <option>Soporte a usuario</option>
                                        <option>Corrección de error</option>
                                        <option>Ajuste de sistema</option>
                                        <option>Incidente en producción</option>
                                        <option>Verificación</option>
                                        <option>Configuración</option>
                                        <option>Otro</option>
                                    </select>
                                </div>

                                <!-- Sistema -->
                                <div class="col-md-6">
                                    <label class="form-label">Sistema</label>
                                    <input type="text" name="sistema" class="form-control" required>
                                </div>

                                <!-- Descripción -->
                                <div class="col-12">
                                    <label class="form-label">Descripción</label>
                                    <textarea name="descripcion" class="form-control" rows="3"
                                        placeholder="Describe brevemente la actividad realizada" required></textarea>
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Guardar actividad
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>



    </div>


    <!-- DATATABLES -->
    <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#tabla-actividades').DataTable({
                "order": [
                    [0, 'asc']
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



</body>

</html>
