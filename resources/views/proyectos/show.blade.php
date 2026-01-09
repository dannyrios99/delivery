<!DOCTYPE html>
<html lang="es">

<head>
    <title>Gestión de Sucursales</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .dropdown-item i {
    width: 16px;
    text-align: center;
    opacity: 0.8;
}

.dropdown-item {
    font-size: 14px;
}

    </style>
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
                        <div class="container-fluid">

                            <div class="card shadow-sm">

                                {{-- HEADER DEL PROYECTO --}}
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0 fw-bold">
                                        {{ $proyecto->nombre }}
                                    </h4>

                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalTarea">
                                        + Nueva tarea
                                    </button>
                                </div>

                                {{-- BODY DEL PROYECTO --}}
                                <div class="card-body">

                                    {{-- KANBAN --}}
                                    {{-- KANBAN DINÁMICO POR GRUPOS --}}
                                    <div class="row g-3">

                                        @foreach ($proyecto->grupos as $grupo)
                                            <div class="col-md-3">
                                                <div class="card h-100 border">

                                                    {{-- HEADER DEL GRUPO --}}
                                                    <div
                                                        class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
                                                        <span>{{ $grupo->nombre }}</span>

                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-light border-0" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                &#9776; {{-- icono hamburguesa --}}
                                                            </button>

                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                                        href="#" data-bs-toggle="modal"
                                                                        data-bs-target="#modalEditarGrupo{{ $grupo->id }}">
                                                                        <i class="fas fa-pen"></i>
                                                                        <span>Editar</span>
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <form method="POST"
                                                                        action="{{ route('grupos-tareas.destroy', $grupo->id) }}"
                                                                        onsubmit="return confirm('¿Eliminar este grupo?')">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="submit"
                                                                            class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                                                            <i class="fas fa-trash"></i>
                                                                            <span>Eliminar</span>
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                    {{-- TAREAS DEL GRUPO --}}
                                                    <div class="card-body">
                                                        @forelse ($grupo->tareas as $tarea)
                                                            <div class="card mb-2">
                                                                <div
                                                                    class="card-body p-2 d-flex justify-content-between align-items-center">
                                                                    <span>{{ $tarea->titulo }}</span>

                                                                    {{-- CAMBIAR DE GRUPO --}}
                                                                    <form method="POST"
                                                                        action="{{ route('tareas.mover', $tarea->id) }}">
                                                                        @csrf
                                                                        @method('PATCH')

                                                                        <select name="grupo_id"
                                                                            class="form-select form-select-sm"
                                                                            onchange="this.form.submit()">
                                                                            @foreach ($proyecto->grupos as $g)
                                                                                <option value="{{ $g->id }}"
                                                                                    {{ $tarea->grupo_id == $g->id ? 'selected' : '' }}>
                                                                                    {{ $g->nombre }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <small class="text-muted">No hay tareas</small>
                                                        @endforelse
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- CREAR NUEVO GRUPO --}}
                                        {{-- CREAR NUEVO GRUPO (MISMO ESTILO) --}}
                                        <div class="col-md-3">
                                            <div class="card h-100 border">

                                                {{-- BODY --}}
                                                <div class="card-body d-flex align-items-center justify-content-center">
                                                    <button class="btn btn-outline-secondary btn-lg rounded-circle"
                                                        data-bs-toggle="modal" data-bs-target="#modalGrupo"
                                                        style="width: 60px; height: 60px;">
                                                        +
                                                    </button>
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
                            <input type="text" name="titulo" class="form-control form-control"
                                placeholder="Título de la tarea" required>
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


        <div class="modal fade" id="modalGrupo" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form method="POST" action="{{ route('grupos-tareas.store') }}" style="width: 100%">
                    @csrf

                    <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Nuevo grupo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <input type="text" name="nombre" class="form-control form-control-lg"
                                placeholder="Nombre del grupo" required autofocus>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary">
                                Crear grupo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- DATATABLES -->
        <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>


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
