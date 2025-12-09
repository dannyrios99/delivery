<!DOCTYPE html>
<html lang="es">

<head>
    <title>Gestionar Usuarios</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">
    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <style>
        .text-primary {
            color: #4a90e2 !important;
        }

        .text-success {
            color: #4BC0C0 !important;
        }

        .text-warning {
            color: #e06d2a !important;
        }
    </style>
    <div class="page-container">
        @section('dashboard')
            class="active-page"
        @endsection

        @include('layouts.sidebar')

        <div class="page-content">
            <div class="main-wrapper">
                <div class="row" data-aos="zoom-in-right">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <p class="lead">Bienvenido,
                                    <strong>{{ Auth::user()->name ?? 'Administrador' }}</strong></p>
                                <hr>

                                <div class="row text-center">
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <i class="fas fa-users fa-2x text-primary mb-2"
                                                    style="color: #4a90e2 !important; font-size: 65px;"></i>
                                                <h5 class="card-title">Usuarios</h5>
                                                <p class="card-text">Gestión de usuarios registrados.</p>
                                                <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-primary"
                                                    style="    background-color: #4a90e2;
                                                                                                                            border-color: #4a90e2;
                                                                                                                            color: #fff;">Ver más</a>
                                            </div>
                                        </div>
                                    </div>

                                   
                                    <div class="col-md-4 mb-3">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <i class="fas fa-cogs fa-2x text-warning mb-2"
                                                    style="color: #e06d2a !important; font-size: 65px;"></i>
                                                <h5 class="card-title">Configuración</h5>
                                                <p class="card-text">Opciones generales del sistema.</p>
                                                <a href="#"
                                                    class="btn btn-sm btn-warning text-white" style="background-color: #e06d2a;
                                                                                                border-color: #e06d2a;
                                                                                                color: #fff;">Configurar</a>
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

    <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/datatables.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#usuariosTable').DataTable({
                "order": [
                    [0, 'asc']
                ],
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

    @if (Session::has('error'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        </script>
    @endif

    @if (Session::has('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        </script>
    @endif

</body>

</html>
