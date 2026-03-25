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
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Listado de Horas Extras</h5>

                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalHoraExtra">
                                        <i class="bi bi-plus-circle"></i> Registrar Horas
                                    </button>

                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="tabla-horas-extras"
                                            class="table table-bordered table-striped align-middle w-100">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Empleado</th>
                                                    <th>Hora inicio</th>
                                                    <th>Hora fin</th>
                                                    <th>Total horas</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($horasExtras as $hora)
                                                    <tr>
                                                        <td>{{ $hora->fecha }}</td>
                                                        <td>{{ $hora->nombre }}</td>
                                                        <td>{{ $hora->hora_inicio }}</td>
                                                        <td>{{ $hora->hora_fin }}</td>
                                                        <td>
                                                            <span class="badge bg-success">
                                                                {{ $hora->total_horas }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-warning"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEditar{{ $hora->id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <a href="{{ route('horas-extras.show', $hora->id) }}"
                                                                class="btn btn-sm btn-info">
                                                                Ver detalle
                                                            </a>
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

        <div class="modal fade" id="modalHoraExtra" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <form action="{{ route('horas-extras.store') }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Registrar Hora Extra</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Empleado</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}"
                                        readonly>
                                </div>

                                <input type="hidden" name="empleado_id" value="{{ auth()->id() }}">

                                <div class="col-md-6">
                                    <label class="form-label">Fecha</label>
                                    <input type="date" name="fecha" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hora inicio</label>
                                    <input type="time" name="hora_inicio" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hora fin</label>
                                    <input type="time" name="hora_fin" class="form-control" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Área / Sistema</label>
                                    <input type="text" name="area" class="form-control">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Observación</label>
                                    <textarea name="observacion" class="form-control" rows="3"></textarea>
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Guardar
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        @foreach ($horasExtras as $hora)
            {{-- MODAL EDITAR --}}
            <div class="modal fade" id="modalEditar{{ $hora->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">

                        <form method="POST" action="{{ route('horas-extras.update', $hora->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="modal-header">
                                <h5 class="modal-title">Editar Hora Extra</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Empleado</label>
                                        <input type="text" class="form-control" value="{{ $hora->nombre }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Fecha</label>
                                        <input type="date" name="fecha" class="form-control"
                                            value="{{ $hora->fecha }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Hora inicio</label>
                                        <input type="time" name="hora_inicio" class="form-control"
                                            value="{{ $hora->hora_inicio }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Hora fin</label>
                                        <input type="time" name="hora_fin" class="form-control"
                                            value="{{ $hora->hora_fin }}" required>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Área / Sistema</label>
                                        <input type="text" name="area" class="form-control"
                                            value="{{ $hora->area }}">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Observación</label>
                                        <textarea name="observacion" class="form-control" rows="3">{{ $hora->observacion }}</textarea>
                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Actualizar
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <!-- DATATABLES -->
    <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>
    @push('scripts')
        <script>
            $('.btn-editar').on('click', function() {

                let id = $(this).data('id');

                $('#formEditarHoraExtra').attr('action', `/horas-extras/${id}`);

                $('#edit-fecha').val($(this).data('fecha'));
                $('#edit-hora-inicio').val($(this).data('hora_inicio'));
                $('#edit-hora-fin').val($(this).data('hora_fin'));
                $('#edit-area').val($(this).data('area'));
                $('#edit-observacion').val($(this).data('observacion'));

                let modal = new bootstrap.Modal(document.getElementById('modalEditarHoraExtra'));
                modal.show();
            });
        </script>
    @endpush

    <script>
        $(document).ready(function() {
            $('#tabla-horas-extras').DataTable({
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

    </div>

</body>

</html>
