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
        class="active-page"
    @endsection

    @include('layouts.sidebar')

    <div class="page-content">
        <div class="main-wrapper">

            <div class="row">
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">Gestión de Sucursales</h4>

                                <button type="button"
                                        class="btn"
                                        style="background-color:#e06d2a; color:#fff;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#crearSucursalModal">
                                    <i class="fas fa-plus me-1"></i> Nueva Sucursal
                                </button>
                            </div>

                            <div class="table-responsive mt-4">
                                <table id="sucursalesTable"
                                       class="table table-hover table-bordered align-middle"
                                       style="width:100%">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Ciudad</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($sucursales as $sucursal)
                                            <tr>
                                                <td>{{ $sucursal->nombre }}</td>
                                                <td>{{ $sucursal->ciudad ?? '—' }}</td>

                                                <td class="text-center">
                                                    <div class="d-flex justify-content-start gap-2">

                                                        <!-- Editar -->
                                                        <button type="button"
                                                                class="btn btn-warning btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editarSucursalModal{{ $sucursal->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <!-- Eliminar -->
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEliminar{{ $sucursal->id }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>

                                                    </div>
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


    <!-- MODAL CREAR SUCURSAL -->
    <div class="modal fade" id="crearSucursalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <form method="POST" action="{{ route('sucursales.store') }}">
                    @csrf

                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-semibold">Registrar Nueva Sucursal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre</label>
                            <input type="text" name="nombre" class="form-control rounded-3"
                                   required placeholder="Ej: 51B, Cartagena, Prado">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ciudad</label>
                            <input type="text" name="ciudad" class="form-control rounded-3"
                                   placeholder="Ej: Barranquilla, Cartagena, etc.">
                        </div>
                    </div>

                    <div class="modal-footer border-top px-4">
                        <button type="submit" class="btn" style="background-color:#e06d2a;color:#fff;">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- MODALES EDITAR SUCURSAL -->
    @foreach($sucursales as $sucursal)
    <div class="modal fade" id="editarSucursalModal{{ $sucursal->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <form method="POST" action="{{ route('sucursales.update', $sucursal->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-semibold">Editar Sucursal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body px-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre</label>
                            <input type="text" name="nombre" value="{{ $sucursal->nombre }}"
                                   class="form-control rounded-3" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ciudad</label>
                            <input type="text" name="ciudad" value="{{ $sucursal->ciudad }}"
                                   class="form-control rounded-3">
                        </div>

                    </div>

                    <div class="modal-footer border-top px-4">
                        <button type="submit" class="btn" style="background-color:#e06d2a;color:#fff;">
                            Actualizar
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    @endforeach


    <!-- MODALES ELIMINAR -->
    @foreach($sucursales as $sucursal)
    <div class="modal fade" id="modalEliminar{{ $sucursal->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('sucursales.destroy', $sucursal->id) }}">
                @csrf
                @method('DELETE')

                <div class="modal-content border-0 shadow-sm">
                    <div class="modal-header p-2">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">
                        <i class="fas fa-triangle-exclamation text-danger" style="font-size:48px;"></i>
                        <p class="mt-3 mb-0">
                            ¿Deseas eliminar la sucursal <strong>{{ $sucursal->nombre }}</strong>?
                        </p>
                        <small class="text-muted">Esta acción no se puede deshacer.</small>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>

                </div>

            </form>
        </div>
    </div>
    @endforeach


    <!-- DATATABLES -->
    <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#sucursalesTable').DataTable({
                "order": [[0, 'asc']],
                "pageLength": 25,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "No hay registros",
                    "infoFiltered": "(filtrado de _MAX_ registros)",
                    "search": "Buscar:",
                    "paginate": { "next": "Siguiente", "previous": "Anterior" }
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