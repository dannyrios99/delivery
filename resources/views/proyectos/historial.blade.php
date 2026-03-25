<!DOCTYPE html>
<html lang="es">

<head>
    <title>Historial - {{ $proyecto->nombre }}</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f0f2f5; font-family: system-ui, -apple-system, sans-serif; }
        .page-content { padding-top: 20px; }
        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 3px solid #f0f2f5; 
            margin-left: -10px; 
        }
        .task-row:hover { background-color: #f8f9fa; }
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

                            <div class="card shadow-sm border-0 rounded-4">

                                {{-- HEADER DEL PROYECTO --}}
                                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0 fw-bold d-flex align-items-center">
                                        <a href="{{ route('proyectos.show', $proyecto->id) }}" class="text-muted text-decoration-none me-3" title="Volver al proyecto">
                                            <i class="fas fa-arrow-left"></i>
                                        </a>
                                        <i class="fas fa-history text-primary me-2" style="color: #e06d2a !important;"></i> 
                                        Historial de Tareas: {{ $proyecto->nombre }}
                                    </h4>
                                </div>

                                {{-- BODY DEL HISTORIAL --}}
                                <div class="card-body px-4 pb-4">
                                    
                                    @if($tareas->count() > 0)
                                        <div class="table-responsive">
                                            <table id="historialTable" class="table table-hover table-bordered align-middle text-nowrap mt-3" style="width:100%">
                                                <thead class="table-light text-center small fw-bold text-uppercase">
                                                    <tr>
                                                        <th>Tarea</th>
                                                        <th>Grupo Original</th>
                                                        <th>Prioridad</th>
                                                        <th>Responsables</th>
                                                        <th class="text-center">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($tareas as $tarea)
                                                    <tr class="task-row border-bottom border-light">
                                                        <td class="py-3">
                                                            <div class="fw-bold text-dark mb-1">{{ $tarea->titulo }}</div>
                                                            <small class="text-muted">
                                                                Archivada el {{ $tarea->updated_at->locale('es')->translatedFormat('d M Y, H:i') }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            {{ $tarea->grupo->nombre ?? 'Sin grupo' }}
                                                        </td>
                                                        <td>
                                                            {{ ucfirst($tarea->prioridad ?? 'Normal') }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @forelse($tarea->responsables as $resp)
                                                                    <div class="d-inline-flex align-items-center bg-light border rounded-pill pe-2 shadow-sm" style="height: 28px;">
                                                                        <div class="d-flex align-items-center justify-content-center fw-bold text-white rounded-circle me-2" 
                                                                            style="width: 28px; height: 28px; font-size: 0.7rem; background-color: {{ '#' . substr(md5($resp->name), 0, 6) }};">
                                                                            {{ strtoupper(substr($resp->name, 0, 1)) }}
                                                                        </div>
                                                                        <small class="fw-medium text-dark" style="font-size: 0.75rem;">
                                                                            {{ explode(' ', trim($resp->name))[0] }} </small>
                                                                    </div>
                                                                @empty
                                                                    <span class="badge rounded-pill bg-light text-secondary border-0">Sin asignar</span>
                                                                @endforelse
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex justify-content-center gap-2">
                                                                {{-- Restaurar --}}
                                                                <form action="{{ route('tareas.restaurar', $tarea->id) }}" method="POST">
                                                                    @csrf @method('PATCH')
                                                                    <button type="submit" class="btn" style="background-color: #e06d2a; color: white; width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: none; box-shadow: 0 2px 4px rgba(224, 109, 42, 0.2); transition: all 0.2s ease;" title="Restaurar tarea al tablero" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(224, 109, 42, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(224, 109, 42, 0.2)';">
                                                                        <i class="fas fa-undo"></i>
                                                                    </button>
                                                                </form>

                                                                {{-- Eliminar Definitivamente --}}
                                                                <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar definitivamente esta tarea? Esta acción no se puede deshacer.');">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="btn btn-outline-danger" style="width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;" title="Eliminar permanentemente" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="fas fa-box-open text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                                            </div>
                                            <h5 class="text-muted fw-bold">El historial está vacío</h5>
                                            <p class="text-muted small">Aún no hay tareas archivadas en este proyecto.</p>
                                        </div>
                                    @endif
                                    
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            $('#historialTable').DataTable({
                "order": [[0, 'desc']],
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
