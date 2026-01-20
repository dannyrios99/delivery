<!DOCTYPE html>
<html lang="es">

<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>{{ $proyecto->nombre }}</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        body { background-color: #f0f2f5; }
        .kanban-wrapper {
            display: flex;
            gap: 1.5rem;
            padding: 20px;
            align-items: flex-start;
        }
        /* Estilo de la Columna (Lista) */
        .kanban-column {
            width: 320px;
            min-width: 320px;
            background: transparent;
            border: none;
        }

        .column-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #172b4d;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Estilo de la Tarjeta (Card) */
        .task-card {
            position: relative;
            transition:
                top 0.6s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.6s cubic-bezier(0.22, 1, 0.36, 1);
        }


        .task-card:hover {
            top: -2px;
            box-shadow: 0 14px 28px rgba(0,0,0,0.14);
        }

        /* Tags/Etiquetas */
        .tag {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 10px;
            display: inline-block;
            margin-bottom: 12px;
        }
        
        .tag-copywriting { background: #fdf2ff; color: #d633ff; }
        .tag-design { background: #eef2ff; color: #4338ca; }
        .tag-illustration { background: #f0fdf4; color: #15803d; }

        .task-text {
            color: #334155;
            font-size: 0.95rem;
            line-height: 1.5;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .task-footer {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #94a3b8;
            font-size: 0.8rem;
        }

        .task-footer i { font-size: 0.9rem; }

        /* Botón añadir invisible */
        .btn-add-list {
            background: rgba(255,255,255,0.5);
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            color: #64748b;
            padding: 15px;
            font-weight: 600;
        }

        /* Botón circular de la parte superior derecha */
        .btn-add-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px dashed #cbd5e1;
            background-color: transparent;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-add-circle:hover {
            background-color: #ffffff;
            border-color: #818cf8;
            color: #818cf8;
            transform: scale(1.1);
        }

        /* Estilo para los círculos decorativos (Avatares) */
        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 3px solid #f0f2f5; /* Color del fondo para el efecto de traslape */
            margin-left: -10px; /* Hace que se encimen un poco */
        }

        /* Ajuste para que el kanban-wrapper no tenga el botón flotando al final */
        .kanban-wrapper {
            display: flex;
            
            gap: 2rem;
            padding: 10px;
            align-items: flex-start;
        }

        .hover-opacity-100 {
            transition: all 0.2s ease;
        }
        .hover-opacity-100:hover {
            opacity: 1 !important;
            transform: scale(1.15); /* Crece ligeramente al pasar el mouse */
        }
        .transition-all {
            transition: all 0.3s ease;
        }

        .select2-container .select2-selection--multiple {
            min-height: 45px;
            border-radius: 12px;
            border: 1px solid #dee2e6;
        }

        .avatar-group .avatar-circle:first-child {
            margin-left: 0 !important;
        }
      
        .task-card .dropdown,
        .task-card .dropdown * {
            pointer-events: auto;
        }

        .card {
            overflow: visible !important;
        }

        .modal-content {
            animation: modalFadeUp 0.25s ease;
        }

        @keyframes modalFadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal input:focus {
            box-shadow: none;
        }

        /* Entrada más suave del offcanvas */
        .offcanvas {
            transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* Quitar sensación pesada del backdrop */
        .offcanvas-backdrop {
            background-color: rgba(0,0,0,0.35);
        }

        .nueva-columna {
            opacity: 0;
            transform: translateX(30px);
        }

        .nueva-columna.mostrar {
            opacity: 1;
            transform: translateX(0);
            transition:
                opacity 0.4s ease,
                transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .offcanvas-grupo {
            width: 420px;
            border-radius: 28px 0 0 28px;
        }

        .input-grupo {
            width: 100%;
            font-size: 1.2rem;
            font-weight: 600;
            padding: 14px 18px;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-grupo:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.15);
        }


        /* Botón cancelar sin ruido */
        .btn-link {
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="page-container">


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

                                    <div class="d-flex align-items-center gap-3">
                                        {{-- Aquí podrías poner avatares como en la imagen --}}
                                        <div class="d-flex -space-x-2">
                                            <div class="avatar-circle" style="background-color: #818cf8;"></div>
                                            <div class="avatar-circle" style="background-color: #6366f1;"></div>
                                            <div class="avatar-circle" style="background-color: #4f46e5;"></div>
                                        </div>

                                        {{-- Botón Estilo Opción 2 --}}
                                        <button class="btn-add-circle"
                                                data-bs-toggle="offcanvas"
                                                data-bs-target="#offcanvasGrupo"
                                                title="Añadir otra lista">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- BODY DEL PROYECTO --}}
                                <div class="card-body">
                                    {{-- KANBAN DINÁMICO POR GRUPOS --}}
                                    <div class="kanban-wrapper">
                                        @foreach ($proyecto->grupos as $grupo)
                                            <div class="kanban-column">
                                                {{-- Título de la columna --}}
                                                <div class="column-title px-2 d-flex justify-content-between align-items-center">
                                                    <span>{{ $grupo->nombre }}</span>

                                                    <div class="dropdown">
                                                        <i class="fas fa-ellipsis-h text-muted small"
                                                        data-bs-toggle="dropdown"
                                                        style="cursor:pointer; padding:4px;"></i>

                                                        <ul class="dropdown-menu dropdown-menu-end shadow rounded-4 border-0 py-2">
                                                            <li>
                                                                <button class="dropdown-item text-danger small d-flex align-items-center"
                                                                        onclick="confirmarEliminarGrupo({{ $grupo->id }}, this)">
                                                                    <i class="fas fa-trash-alt me-2"></i>
                                                                    Eliminar grupo
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                {{-- Contenedor de Cards --}}
                                                <div class="column-body">
                                                    @foreach ($grupo->tareas as $tarea)
                                                        <div class="task-card p-3 mb-3 border-0 shadow-sm bg-white rounded-4" 
                                                            role="button"
                                                            style="cursor: pointer;"
                                                            onclick="
                                                            if (
                                                                !event.target.closest('.dropdown') &&
                                                                !event.target.closest('.dropdown-menu')
                                                            ) {
                                                                abrirTarea({{ $tarea->load(['responsables', 'checklist'])->toJson() }});
                                                                (new bootstrap.Offcanvas(document.getElementById('panelTarea'))).show();
                                                            }">

                                                            {{-- Header: Prioridad y Menú Mover --}}
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <span class="badge rounded-pill bg-{{ $tarea->prioridad == 'alta' ? 'danger' : ($tarea->prioridad == 'media' ? 'warning' : 'info') }} px-2 py-1" style="font-size: 0.65rem; text-transform: uppercase;">
                                                                    {{ $tarea->prioridad ?? 'Normal' }}
                                                                </span>
                                                                
                                                                <div class="dropdown position-relative">
                                                                   <i class="fas fa-ellipsis-h text-muted small"
                                                                        data-bs-toggle="dropdown"
                                                                        style="cursor: pointer; padding: 5px; position: relative; z-index: 1;">
                                                                    </i>
                                                                        
                                                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 py-2"
                                                                        style="z-index: 1055;">

                                                                        <li class="dropdown-header pb-1 small fw-bold text-uppercase text-danger">Acciones</li>
                                                                        <li>
                                                                            <button type="button" class="dropdown-item small text-danger d-flex align-items-center" 
                                                                                    onclick="confirmarEliminarTarea({{ $tarea->id }})">
                                                                                <i class="fas fa-trash-alt me-2"></i> Eliminar Tarea
                                                                            </button>
                                                                        </li>
                                                                        <li class="dropdown-divider"></li>
                                                                        <li class="dropdown-header pb-1 small fw-bold text-uppercase">Mover a:</li>
                                                                        @foreach ($proyecto->grupos as $g)
                                                                            @if($g->id !== $grupo->id)
                                                                                <li>
                                                                                    <form action="{{ route('tareas.mover', $tarea->id) }}" method="POST">
                                                                                        @csrf @method('PATCH')
                                                                                        <input type="hidden" name="grupo_id" value="{{ $g->id }}">
                                                                                        <button type="submit" class="dropdown-item small d-flex align-items-center">
                                                                                            <i class="fas fa-arrow-right me-2 text-muted" style="font-size: 0.7rem;"></i>
                                                                                            {{ $g->nombre }}
                                                                                        </button>
                                                                                    </form>
                                                                                </li>
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>

                                                            {{-- Título --}}
                                                            <div class="task-text fw-bold text-dark mb-2" style="font-size: 0.85rem; line-height: 1.3;">
                                                                {{ $tarea->titulo }}
                                                            </div>

                                                            {{-- NUEVA SECCIÓN: Responsables como Etiquetas (Chips) --}}
                                                            @if($tarea->responsables->count() > 0)
                                                                <div class="d-flex flex-wrap gap-1 mb-3">
                                                                    @foreach($tarea->responsables as $resp)
                                                                        <div class="d-inline-flex align-items-center bg-light rounded-pill pe-2" style="border: 1px solid #eee;">
                                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                                                                style="width: 20px; height: 20px; font-size: 0.55rem;">
                                                                                {{ strtoupper(substr($resp->name, 0, 1)) }}
                                                                            </div>
                                                                            <span class="ms-1 text-dark" style="font-size: 0.65rem; white-space: nowrap;">{{ $resp->name }}</span>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            {{-- Footer: Metadata (Fecha, Checklist, Comentarios) --}}
                                                            <div class="task-footer d-flex justify-content-start align-items-center gap-3 pt-2 border-top border-light text-muted" style="font-size: 0.7rem;">
                                                                <span title="Fecha límite">
                                                                    <i class="far fa-calendar-alt me-1"></i> 
                                                                    {{ $tarea->fecha_limite ? \Carbon\Carbon::parse($tarea->fecha_limite)->format('d M') : 'Sin fecha' }}
                                                                </span>
                                                                <span title="Checklist">
                                                                    <i class="fas fa-tasks me-1"></i>
                                                                    {{ $tarea->checklist->where('completado', 1)->count() }}/{{ $tarea->checklist->count() }}
                                                                </span>
                                                                <span title="Comentarios">
                                                                    <i class="far fa-comment me-1"></i>
                                                                    {{ $tarea->comentarios->count() }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    {{-- Botón para añadir tarea dentro de la lista --}}
                                                    <button class="btn w-100 text-muted small fw-bold mt-2 border-0" 
                                                            data-bs-toggle="offcanvas" 
                                                            data-bs-target="#panelTarea" 
                                                            onclick="abrirTarea(null, {{ $grupo->id }})">
                                                        + Añadir tarea
                                                    </button>
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

        <div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="panelTarea" style="width: 600px; border-radius: 24px 0 0 24px;">
            <div class="offcanvas-header p-4 border-bottom bg-light">
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary-subtle text-primary me-2 px-3 py-2 rounded-pill" id="badge_grupo">Estado</span>
                    <h5 class="offcanvas-title fw-bold m-0" id="panelTareaLabel">Detalles de Tarea</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>

            <div class="offcanvas-body p-4">
                <form id="formTareaPrincipal" action="{{ route('tareas.store') }}" method="POST">
                    @csrf
                    <div id="method_field"></div> {{-- Se llenará con PUT al editar --}}
                    <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">
                    <input type="hidden" name="grupo_id" id="panel_grupo_id">

                    <input type="text" name="titulo" id="panel_titulo" 
                        class="form-control form-control-lg border-0 bg-transparent fw-bold p-0 mb-3" 
                        style="font-size: 1.8rem;" placeholder="INGRESE NOMBRE DE LA TAREA">

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold mb-2">Responsables</label>
                                <select name="responsables[]" id="panel_responsables" class="form-control select2-multiple" multiple="multiple" style="width: 100%">
                                    @foreach($usuarios as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-2">Prioridad</label>
                            <select name="prioridad" id="panel_prioridad" class="form-select border-0 bg-light shadow-none">
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-2">Límite</label>
                            <input type="date" name="fecha_limite" id="panel_fecha" class="form-control border-0 bg-light shadow-none">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase d-block mb-2">
                            <i class="fas fa-align-left me-2"></i>Descripción
                        </label>
                        <textarea name="descripcion" id="panel_descripcion" 
                                class="form-control border-0 bg-light shadow-none" 
                                rows="5" 
                                style="border-radius: 16px; resize: none; padding: 15px;" 
                                placeholder="Añade detalles sobre esta tarea..."></textarea>
                    </div>

                    <hr class="opacity-25">

                    <div class="section-checklist mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold m-0"><i class="fas fa-check-double me-2 text-success"></i>Checklist</h6>
                            <button type="button" class="btn btn-sm btn-light rounded-pill px-3" onclick="addChecklistItem()">+ Agregar item</button>
                        </div>
                        <div id="panel-checklist-items">
                            {{-- Los items se inyectan aquí --}}
                        </div>
                    </div>

                    <input type="hidden" id="panel_tarea_id" name="tarea_id">

                    <div id="seccion_comentarios" class="mt-4" style="display: none;">
                        <h6 class="fw-bold mb-3"><i class="far fa-comments me-2 text-info"></i>Comentarios</h6>
                        <div class="chat-container bg-light p-3 rounded-4 mb-3" id="lista_comentarios" style="max-height: 200px; overflow-y: auto;">
                            {{-- Los comentarios se cargan vía AJAX --}}
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control border-0 shadow-sm" placeholder="Escribe un comentario..." id="nuevo_comentario">
                            <button class="btn btn-primary px-3" type="button" onclick="enviarComentario()"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>

                    <div class="sticky-bottom bg-white pt-4 mt-5">
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" id="btn_guardar" style="border-radius: 14px;">
                            Guardar Tarea
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="offcanvas offcanvas-end offcanvas-grupo"
            tabindex="-1"
            id="offcanvasGrupo">

            <div class="offcanvas-body">

                <!-- Cerrar -->
                <div class="d-flex justify-content-end mb-3">
                    <button type="button"
                            class="btn-close opacity-50"
                            data-bs-dismiss="offcanvas"></button>
                </div>

                <!-- Contenido -->
                <div class="pt-3">

                    <h4 class="fw-bold mb-1">Nuevo grupo</h4>
                    <p class="text-muted small mb-4">
                        Crea una nueva columna para organizar tus tareas
                    </p>

                    <form id="formGrupo">
                        @csrf
                        <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">

                        <input type="text"
                            name="nombre"
                            id="nombreGrupo"
                            class="input-grupo mb-4"
                            placeholder="Nombre del grupo"
                            required
                            autofocus>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button"
                                    class="btn btn-link text-muted px-2"
                                    data-bs-dismiss="offcanvas">
                                Cancelar
                            </button>

                            <button type="submit"
                                    class="btn btn-primary rounded-pill px-4">
                                Crear
                            </button>
                        </div>
                    </form>

                </div>
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
            $(document).ready(function() {
                // Inicializamos sobre el ID del select
                $('#panel_responsables').select2({
                    placeholder: "Selecciona responsables...",
                    allowClear: true,
                    width: '100%',
                    // Esto asegura que el buscador funcione dentro de paneles flotantes
                    dropdownParent: $('#formTareaPrincipal').parent() 
                });
            });
            
            document.addEventListener('show.bs.dropdown', function (event) {
                // Cierra todos los dropdowns abiertos
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    if (!menu.contains(event.target)) {
                        const dropdown = bootstrap.Dropdown.getInstance(menu.previousElementSibling);
                        if (dropdown) dropdown.hide();
                    }
                });
            });
        </script>

        <script>
        //Crear grupo + Cerrar offcanvas
        document.getElementById('formGrupo').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = e.target;
            const nombre = document.getElementById('nombreGrupo').value.trim();
            if (!nombre) return;

            const response = await fetch("{{ route('grupos-tareas.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    proyecto_id: {{ $proyecto->id }},
                    nombre: nombre
                })
            });

            const data = await response.json();

            if (data.success) {
                cerrarOffcanvasGrupo();
                insertarColumna(data.grupo);
                form.reset();
            }
        });

        function cerrarOffcanvasGrupo() {
            const offcanvasEl = document.getElementById('offcanvasGrupo');
            const instance = bootstrap.Offcanvas.getInstance(offcanvasEl);
            if (instance) instance.hide();
        }

        function insertarColumna(grupo) {
            const wrapper = document.querySelector('.kanban-wrapper');

            const col = document.createElement('div');
            col.className = 'kanban-column nueva-columna';
            col.innerHTML = `
                <div class="column-title px-2 d-flex justify-content-between align-items-center">
                    <span>${grupo.nombre}</span>

                    <div class="dropdown">
                        <i class="fas fa-ellipsis-h text-muted small"
                        data-bs-toggle="dropdown"
                        style="cursor:pointer; padding:4px;"></i>

                        <ul class="dropdown-menu dropdown-menu-end shadow rounded-4 border-0 py-2">
                            <li>
                                <button class="dropdown-item text-danger small d-flex align-items-center"
                                        onclick="confirmarEliminarGrupo(${grupo.id}, this)">
                                    <i class="fas fa-trash-alt me-2"></i>
                                    Eliminar grupo
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="column-body">
                    <button class="btn w-100 text-muted small fw-bold mt-2 border-0">
                        + Añadir tarea
                    </button>
                </div>
            `;

            wrapper.appendChild(col);

            // animación
            requestAnimationFrame(() => col.classList.add('mostrar'));

            // 🔥 INICIALIZAR DROPDOWN
            inicializarDropdowns(col);

            // scroll
            wrapper.scrollTo({
                left: wrapper.scrollWidth,
                behavior: 'smooth'
            });
        }

        document.getElementById('offcanvasGrupo')
        .addEventListener('shown.bs.offcanvas', () => {
            document.getElementById('nombreGrupo').focus();
        });

        function inicializarDropdowns(container = document) {
            container.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
                // Evita duplicar instancias
                if (!bootstrap.Dropdown.getInstance(el)) {
                    new bootstrap.Dropdown(el);
                }
            });
        }
        </script>

        <script>
            //Eliminar grupo
        function confirmarEliminarGrupo(grupoId, btn) {
            event.stopPropagation();

            Swal.fire({
                title: '¿Eliminar grupo?',
                text: 'Se eliminarán todas las tareas de este grupo.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminar'
            }).then(async (result) => {
                if (!result.isConfirmed) return;

                try {
                    const response = await fetch(`/grupos-tareas/${grupoId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        eliminarColumnaConAnimacion(btn);
                    }
                } catch (e) {
                    Swal.fire('Error', 'No se pudo eliminar el grupo', 'error');
                }
            });
        }

        function eliminarColumnaConAnimacion(btn) {
            const column = btn.closest('.kanban-column');

            column.style.transition = 'all 0.35s ease';
            column.style.opacity = '0';
            column.style.transform = 'translateX(30px)';

            setTimeout(() => column.remove(), 350);
        }
        </script>

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
            const authUserId = {{ auth()->id() }};
        </script>

        <script>
            function confirmarEliminarTarea(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción eliminará la tarea, sus comentarios y el checklist permanentemente.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Creamos un formulario dinámico para enviar el DELETE
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/tareas/${id}`;
                        form.innerHTML = `
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        </script>

        <script>
            // 1. Declaraciones globales únicas
            if (typeof checklistIndex === 'undefined') {
                window.checklistIndex = 0;
            }

            function abrirTarea(tarea = null, grupoId = null) {
                const form = document.getElementById('formTareaPrincipal');
                const methodField = document.getElementById('method_field');
                const panelTituloLabel = document.getElementById('panelTareaLabel');
                const selectResponsables = document.getElementById('panel_responsables');
                const checklistContainer = document.getElementById('panel-checklist-items');
                const listaComentarios = document.getElementById('lista_comentarios');

                // Limpieza previa de contenedores
                checklistContainer.innerHTML = '';
                window.checklistIndex = 0; 
                if (listaComentarios) listaComentarios.innerHTML = ''; 
                
                // Limpieza de Select2
                if (selectResponsables) {
                    $(selectResponsables).val(null).trigger('change');
                }

                if (tarea) {
                    // ================= MODO EDICIÓN =================
                    panelTituloLabel.innerText = "Actualizar Tarea";
                    form.action = `/tareas/${tarea.id}`; 
                    methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                    
                    if(document.getElementById('panel_tarea_id')) {
                        document.getElementById('panel_tarea_id').value = tarea.id;
                    }

                    document.getElementById('panel_titulo').value = tarea.titulo || '';
                    document.getElementById('panel_prioridad').value = tarea.prioridad || 'media';
                    document.getElementById('panel_fecha').value = tarea.fecha_limite || '';
                    document.getElementById('panel_descripcion').value = tarea.descripcion || '';

                    // Cargar Responsables en Select2
                    if (tarea.responsables && selectResponsables) {
                        const responsableIds = tarea.responsables.map(resp => resp.id);
                        $(selectResponsables).val(responsableIds).trigger('change');
                    }

                    // Cargar Checklist existente
                    if (tarea.checklist && tarea.checklist.length > 0) {
                        tarea.checklist.forEach(item => {
                            addChecklistItem(item.texto, item.completado, item.id);
                        });
                    }

                    // Cargar Comentarios
                    const secComentarios = document.getElementById('seccion_comentarios');
                    if(secComentarios) {
                        secComentarios.style.display = "block";
                        if (tarea.comentarios && tarea.comentarios.length > 0) {
                            tarea.comentarios.forEach(com => {
                                listaComentarios.insertAdjacentHTML('beforeend', renderizarComentarioHtml(
                                    com.user ? com.user.name : 'Usuario', 
                                    com.contenido, 
                                    com.fecha_formateada || 'Reciente',
                                    com.id,
                                    com.user_id
                                ));
                            });
                        } else {
                            listaComentarios.innerHTML = '<p class="text-muted small text-center my-3">No hay comentarios aún.</p>';
                        }
                    }
                    document.getElementById('btn_guardar').innerText = "Actualizar Cambios";

                } else {
                    // ================= MODO CREACIÓN =================
                    panelTituloLabel.innerText = "Nueva Tarea";
                    form.action = "{{ route('tareas.store') }}";
                    methodField.innerHTML = '';
                    form.reset(); 
                    if(document.getElementById('panel_tarea_id')) {
                        document.getElementById('panel_tarea_id').value = '';
                    }
                    document.getElementById('panel_grupo_id').value = grupoId;
                    document.getElementById('btn_guardar').innerText = "Crear Tarea";
                    
                    const secComentarios = document.getElementById('seccion_comentarios');
                    if(secComentarios) secComentarios.style.display = "none";
                }
            }

            /**
             * Esta función va FUERA de abrirTarea para que el botón "+ Agregar ítem" 
             * del HTML también pueda llamarla.
             */
            function addChecklistItem(texto = '', completado = 0, id = null) {
                const container = document.getElementById('panel-checklist-items');
                const itemId = `item-wrapper-${window.checklistIndex}`;
                
                // Input oculto fundamental para que el controlador reconozca el ID al actualizar
                const inputId = id ? `<input type="hidden" name="checklist[${window.checklistIndex}][id]" value="${id}">` : '';

                const html = `
                    <div class="input-group mb-2 shadow-sm border-0 align-items-center bg-white p-1" id="${itemId}" style="border-radius: 12px;">
                        ${inputId}
                        <div class="input-group-text border-0 bg-transparent">
                            <input type="hidden" name="checklist[${window.checklistIndex}][completado]" value="0">
                            <input class="form-check-input mt-0" type="checkbox" 
                                name="checklist[${window.checklistIndex}][completado]" 
                                value="1" ${completado == 1 ? 'checked' : ''} style="cursor:pointer;">
                        </div>
                        <input type="text" name="checklist[${window.checklistIndex}][texto]" 
                            class="form-control border-0 py-2 bg-transparent" 
                            value="${texto}" placeholder="Tarea secundaria..." style="font-size: 0.9rem;">
                        <button class="btn btn-link text-danger border-0 p-2" type="button" 
                            onclick="document.getElementById('${itemId}').remove()" style="text-decoration: none;">
                            <i class="fas fa-times-circle fa-lg"></i>
                        </button>
                    </div>
                `;
                
                container.insertAdjacentHTML('beforeend', html);
                window.checklistIndex++;
            }

            function renderizarComentarioHtml(nombre, texto, fecha, comentarioId, autorId) {
                const botonEliminar = (authUserId == autorId) 
                    ? `<button type="button" class="btn btn-link text-danger p-0 border-0 ms-3 opacity-50 hover-opacity-100 transition-all" 
                            onclick="eliminarComentario(event, ${comentarioId})" 
                            style="text-decoration: none; display: flex; align-items: center;">
                            <i class="fas fa-times-circle fa-lg"></i>
                    </button>` 
                    : '';

                return `
                    <div class="mb-3 p-3 bg-white rounded-4 shadow-sm border-start border-info border-4 transition-all" id="comentario-${comentarioId}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <span class="fw-bold text-dark me-2" style="font-size: 0.95rem;">${nombre}</span>
                                <span class="text-muted" style="font-size: 0.75rem;">${fecha}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                ${botonEliminar}
                            </div>
                        </div>
                        <p class="mb-0 text-secondary" style="font-size: 0.9rem; line-height: 1.5;">${texto}</p>
                    </div>
                `;
            }
        </script>

        <script>
            async function enviarComentario() {
                const input = document.getElementById('nuevo_comentario');
                const contenido = input.value.trim();
                const tareaId = document.getElementById('panel_tarea_id').value;
                const lista = document.getElementById('lista_comentarios');

                if (!contenido || !tareaId) return;

                try {
                    const response = await fetch('/comentarios-tareas', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ tarea_id: tareaId, contenido: contenido })
                    });

                    const data = await response.json();

                    if (data.success) {
                        const mensajeVacio = lista.querySelector('.text-muted');
                        if (mensajeVacio && mensajeVacio.textContent.includes('No hay')) {
                            lista.innerHTML = ''; 
                        }

                        // Usamos el ID devuelto por el servidor para que el botón de borrar funcione de inmediato
                        const nuevoHtml = renderizarComentarioHtml(data.nombre, data.contenido, 'Ahora mismo', data.id, authUserId);
                        
                        lista.insertAdjacentHTML('afterbegin', nuevoHtml);
                        input.value = '';
                        lista.scrollTop = 0; 
                    }
                } catch (error) {
                    console.error("Error:", error);
                }
            }

            async function eliminarComentario(e, id) {
                // Detener cualquier acción del formulario padre
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                Swal.fire({
                    title: '¿Eliminar comentario?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, borrar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-4 shadow'
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/comentarios-tareas/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                const elemento = document.getElementById(`comentario-${id}`);
                                if (elemento) {
                                    elemento.style.transform = 'translateX(20px)';
                                    elemento.style.opacity = '0';
                                    setTimeout(() => elemento.remove(), 300);
                                }
                                
                                // Notificación pequeña de éxito
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Eliminado',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        } catch (error) {
                            console.error("Error:", error);
                            Swal.fire('Error', 'No se pudo eliminar el comentario', 'error');
                        }
                    }
                });
            }
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