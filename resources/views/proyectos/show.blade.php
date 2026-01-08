<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestión de Usuarios</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">
    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>

<div class="page-container">
    @section('usuarios')
        class="active-page"
    @endsection

    @include('layouts.sidebar')

    <div class="page-content">
        <div data-aos="zoom-in-down">
            <div class="main-wrapper">

<div class="container-fluid">

    <div class="card shadow-sm">

        {{-- HEADER DEL PROYECTO --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">
                {{ $proyecto->nombre }}
            </h4>

            <button class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalTarea">
                + Nueva tarea
            </button>
        </div>

        {{-- BODY DEL PROYECTO --}}
        <div class="card-body">

            {{-- KANBAN --}}
            <div class="row g-3">

                {{-- PENDIENTE --}}
                <div class="col-md-4">
                    <div class="card h-100 border">
                        <div class="card-header bg-light fw-semibold">
                            Pendiente
                        </div>

                        <div class="card-body">
                            @foreach ($tareas->where('estado', 'pendiente') as $tarea)
                                <div class="card mb-2">
                                    <div class="card-body p-2">
                                        {{ $tarea->titulo }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- EN PROGRESO --}}
                <div class="col-md-4">
                    <div class="card h-100 border">
                        <div class="card-header bg-light fw-semibold">
                            En progreso
                        </div>

                        <div class="card-body">
                            @foreach ($tareas->where('estado', 'en_progreso') as $tarea)
                                <div class="card mb-2">
                                    <div class="card-body p-2">
                                        {{ $tarea->titulo }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- HECHO --}}
                <div class="col-md-4">
                    <div class="card h-100 border">
                        <div class="card-header bg-light fw-semibold">
                            Hecho
                        </div>

                        <div class="card-body">
                            @foreach ($tareas->where('estado', 'hecho') as $tarea)
                                <div class="card mb-2 opacity-75">
                                    <div class="card-body p-2 text-decoration-line-through">
                                        {{ $tarea->titulo }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>



            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTarea" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="{{ route('tareas.store') }}" style="width: 100%">
            @csrf

            <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva tarea</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input
                        type="text"
                        name="titulo"
                        class="form-control form-control"
                        placeholder="Título de la tarea"
                        required
                    >
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">
                        Crear tarea
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>



{{-- ========== SCRIPTS ========== --}}
<script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/datatables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#usuariosTable').DataTable({
                "order": [[0, 'asc']],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });
    </script>
    
    <script>
    function togglePassword(fieldId, button) {
        const input = document.getElementById(fieldId);
        const icon = button.querySelector("i");
    
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }
    </script>

    <script>
        function confirmarEliminacion(userId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formEliminar' + userId).submit();
                }
            });
        }
    </script>
@if (Session::has('success'))
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: "{{ session('success') }}",
    showConfirmButton: false,
    timer: 3000,
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
    timer: 3000,
});
</script>
@endif

</body>
</html>
