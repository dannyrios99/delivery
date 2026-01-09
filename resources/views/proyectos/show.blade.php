<!DOCTYPE html>
<html lang="es">

<head>
    <title>{{ $proyecto->nombre }}</title>
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

        /* Base dropdown */
        .dropdown-menu {
            padding: 6px;
            border-radius: 10px;
        }

        /* Item base */
        .dropdown-item {
            border-radius: 8px;
            padding: 8px 12px;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        /* Hover EDITAR */
        .dropdown-item:not(.text-danger):hover {
            background-color: #f1f5ff;
            color: #0d6efd;
        }

        /* Hover ELIMINAR */
        .dropdown-item.text-danger:hover {
            background-color: #ffecec;
            color: #dc3545;
        }

        /* Iconos */
        .dropdown-item i {
            width: 16px;
            text-align: center;
            opacity: 0.85;
            transition: transform 0.15s ease;
        }

        /* Micro-animación */
        .dropdown-item:hover i {
            transform: scale(1.05);
        }

        .btn-outline-primary {
            color: #e06d2a !important;
            border-color: #e06d2a !important;
        }

        .btn-outline-primary.focus,
        .btn-outline-primary:focus,
        .btn-outline-primary:hover,
        .btn-outline-primary:not(:disabled):not(.disabled).active,
        .btn-outline-primary:not(:disabled):not(.disabled):active {
            color: #fff !important;
            border-color: #e06d2a !important;
            background-color: #e06d2a !important;
            box-shadow: 0 7px 23px -8px #e06d2a !important;
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

                                    {{-- <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalTarea">
                                        + Nueva tarea
                                    </button> --}}
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
                                                                    <a class="dropdown-item d-flex align-items-center gap-2 text-danger"
                                                                        href="#" data-bs-toggle="modal"
                                                                        data-bs-target="#modalEliminarGrupo{{ $grupo->id }}">
                                                                        <i class="fas fa-trash"></i>
                                                                        <span>Eliminar</span>
                                                                    </a>
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                    {{-- TAREAS DEL GRUPO --}}
                                                    {{-- ================= CONTENIDO DEL GRUPO ================= --}}
                                                    <div class="card-body p-2">

                                                        @forelse ($grupo->tareas as $tarea)
                                                            <div class="task-card mb-2" role="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEditarTarea"
                                                                data-id="{{ $tarea->id }}"
                                                                data-titulo="{{ $tarea->titulo }}"
                                                                data-descripcion="{{ $tarea->descripcion }}"
                                                                data-prioridad="{{ $tarea->prioridad }}"
                                                                data-fecha="{{ $tarea->fecha_limite }}">
                                                                <div
                                                                    class="task-content d-flex justify-content-between align-items-center">

                                                                    {{-- TÍTULO --}}
                                                                    <span class="task-title">
                                                                        {{ $tarea->titulo }}
                                                                    </span>

                                                                    {{-- ACCIONES --}}
                                                                    <div class="task-actions"
                                                                        onclick="event.stopPropagation();">
                                                                        <form method="POST"
                                                                            action="{{ route('tareas.mover', $tarea->id) }}">
                                                                            @csrf
                                                                            @method('PATCH')

                                                                            <select name="grupo_id"
                                                                                class="form-select form-select-sm task-select"
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
                                                            </div>

                                                        @empty
                                                            <div class="text-center py-3">
                                                                <small class="text-muted">No hay tareas</small>
                                                            </div>
                                                        @endforelse

                                                    </div>

                                                    {{-- ================= FOOTER: NUEVA TAREA ================= --}}
                                                    <div class="card-footer bg-white border-top p-2">
                                                        <button class="btn btn-sm btn-outline-primary w-100"
                                                            data-bs-toggle="modal" data-bs-target="#modalTarea"
                                                            data-grupo="{{ $grupo->id }}">
                                                            + Nueva tarea
                                                        </button>
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


        <div class="modal fade" id="modalEditarTarea" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form method="POST" id="formEditarTarea" style="width: 100%">
                    @csrf
                    @method('PUT')

                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Editar tarea</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Título</label>
                                <input type="text" name="titulo" id="editTitulo"
                                    class="form-control form-control-lg" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Descripción</label>
                                <textarea name="descripcion" id="editDescripcion" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Prioridad</label>
                                    <select name="prioridad" id="editPrioridad" class="form-select">
                                        <option value="baja">Baja</option>
                                        <option value="media">Media</option>
                                        <option value="alta">Alta</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Fecha límite</label>
                                    <input type="date" name="fecha_limite" id="editFecha" class="form-control">
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                            <button class="btn btn-primary">
                                Guardar cambios
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>


        <div class="modal fade" id="modalTarea" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form method="POST" action="{{ route('tareas.store') }}" style="width: 100%">
                    @csrf

                    {{-- IDs ocultos --}}
                    <input type="hidden" name="grupo_id" id="inputGrupoId">
                    <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">

                    <div class="modal-content">

                        {{-- HEADER --}}
                        <div class="modal-header">
                            <h5 class="modal-title">Nueva tarea</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        {{-- BODY --}}
                        <div class="modal-body">

                            {{-- TÍTULO --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Título</label>
                                <input type="text" name="titulo" class="form-control form-control-lg"
                                    placeholder="Ej: Diseñar pantalla de login" required>
                            </div>

                            {{-- DESCRIPCIÓN --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3"
                                    placeholder="Describe brevemente la tarea (opcional)"></textarea>
                            </div>

                            {{-- PRIORIDAD + FECHA --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Prioridad</label>
                                    <select name="prioridad" class="form-select">
                                        <option value="baja">Baja</option>
                                        <option value="media" selected>Media</option>
                                        <option value="alta">Alta</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Fecha límite</label>
                                    <input type="date" name="fecha_limite" class="form-control">
                                </div>
                            </div>

                            {{-- CHECKLIST --}}
                            <div class="mb-2">
                                <label class="form-label fw-semibold">
                                    Checklist
                                </label>

                                <div id="checklistContainer"></div>

                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                                    onclick="agregarChecklistItem()">
                                    + Agregar ítem
                                </button>
                            </div>

                        </div>

                        {{-- FOOTER --}}
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                            <button class="btn btn-primary" style="background-color:#e06d2a; color:#fff;">
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

        @foreach ($proyecto->grupos as $grupo)
            <div class="modal fade" id="modalEliminarGrupo{{ $grupo->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title text-danger">
                                Eliminar grupo
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <p class="mb-0">
                                ¿Seguro que deseas eliminar el grupo
                                <strong>{{ $grupo->nombre }}</strong>?
                            </p>
                            <small class="text-muted">
                                Esta acción eliminará todas las tareas asociadas.
                            </small>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                            <form method="POST" action="{{ route('grupos-tareas.destroy', $grupo->id) }}">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger">
                                    Sí, eliminar
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach

        @foreach ($proyecto->grupos as $grupo)
            <div class="modal fade" id="modalEditarGrupo{{ $grupo->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form method="POST" action="{{ route('grupos-tareas.update', $grupo->id) }}"
                        style="width: 100%">
                        @csrf
                        @method('PUT')

                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Editar grupo
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <label class="form-label">Nombre del grupo</label>
                                <input type="text" name="nombre" class="form-control form-control-lg"
                                    value="{{ $grupo->nombre }}" required autofocus>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>

                                <button class="btn btn-primary">
                                    Guardar cambios
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        @endforeach


        <!-- DATATABLES -->
        <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalTarea = document.getElementById('modalTarea');

                if (!modalTarea) {
                    console.warn('⚠️ modalTarea no encontrado');
                    return;
                }

                modalTarea.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    if (!button) return;

                    const grupoId = button.getAttribute('data-grupo');

                    document.getElementById('inputGrupoId').value = grupoId;
                });
            });
        </script>

        <script>
            let checklistIndex = 0;

            function agregarChecklistItem() {
                const container = document.getElementById('checklistContainer');

                const item = document.createElement('div');
                item.classList.add('d-flex', 'align-items-center', 'mb-2');

                item.innerHTML = `
        <input type="hidden" name="checklist[${checklistIndex}][completado]" value="0">

        <input type="checkbox"
               class="form-check-input me-2"
               onchange="this.previousElementSibling.value = this.checked ? 1 : 0">

        <input type="text"
               name="checklist[${checklistIndex}][texto]"
               class="form-control form-control-sm me-2"
               placeholder="Ej: Crear wireframes">

        <button type="button"
                class="btn btn-sm btn-outline-danger"
                onclick="this.parentElement.remove()">
            ✕
        </button>
    `;

                container.appendChild(item);
                checklistIndex++;
            }
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const modal = document.getElementById('modalEditarTarea');

                modal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    const tareaId = button.getAttribute('data-id');

                    document.getElementById('formEditarTarea')
                        .action = `/tareas/${tareaId}`;

                    document.getElementById('editTitulo').value =
                        button.getAttribute('data-titulo') ?? '';

                    document.getElementById('editDescripcion').value =
                        button.getAttribute('data-descripcion') ?? '';

                    document.getElementById('editPrioridad').value =
                        button.getAttribute('data-prioridad') ?? 'media';

                    document.getElementById('editFecha').value =
                        button.getAttribute('data-fecha') ?? '';
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
