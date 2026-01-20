<!DOCTYPE html>
<html lang="es">

<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Mapas</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .card iframe {
            pointer-events: none;
        }

        .card:hover iframe {
            pointer-events: auto;
        }

        iframe {
            box-shadow: 0 6px 18px rgba(0, 0, 0, .12);
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
            color: #fff;
            border-color: #e06d2a !important;
            background-color: #e06d2a !important;
            box-shadow: 0 7px 23px -8px #e06d2a !important;
        }
    </style>
</head>

<body>

    <div class="page-container">
        @section('mapas')
            class="active-page"
        @endsection

        @include('layouts.sidebar')

        <div class="page-content">
            <div class="main-wrapper">

                <div class="row">
                    <div class="col">
                        <div class="container-fluid">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="mb-0">Mapas por sucursal</h4>
                            </div>

                            <div class="row">
                                @forelse($sucursales as $sucursal)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card shadow-sm h-100">

                                            <div class="card-body">
                                                <h5 class="card-title mb-1">
                                                    {{ $sucursal->nombre }}
                                                </h5>

                                                <p class="card-text text-muted mb-2">
                                                    {{ $sucursal->ciudad ?? 'Ciudad no definida' }}
                                                </p>

                                                <!-- BOTÓN ASIGNAR MAPA -->
                                                <button class="btn btn-outline-primary btn-sm w-100"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#mapaModal{{ $sucursal->id }}">
                                                    <i class="fas fa-map-marked-alt me-1"></i>
                                                    {{ $sucursal->mapaEmbebido ? 'Editar mapa' : 'Asignar mapa' }}
                                                </button>
                                            </div>

                                            @if ($sucursal->mapaEmbebido)
                                                <div class="px-3 pb-3" style="margin-top: -40px;">
                                                    <iframe
                                                        src="https://www.google.com/maps/d/embed?mid={{ $sucursal->mapaEmbebido->google_map_id }}"
                                                        width="100%" height="300"
                                                        style="border-radius: 12px; border:0;" loading="lazy"
                                                        referrerpolicy="no-referrer-when-downgrade">
                                                    </iframe>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center justify-content-center text-muted px-3 pb-3"
                                                    style="height:250px;">
                                                    <small>Sin mapa asignado</small>
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                    <!-- MODAL ASIGNAR MAPA -->
                                    <div class="modal fade" id="mapaModal{{ $sucursal->id }}" tabindex="-1"
                                        aria-labelledby="mapaModalLabel{{ $sucursal->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form method="POST" action="{{ route('mapas.store') }}"
                                                class="modal-content">
                                                @csrf

                                                <input type="hidden" name="sucursal_id" value="{{ $sucursal->id }}">

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="mapaModalLabel{{ $sucursal->id }}">
                                                        Asignar mapa – {{ $sucursal->nombre }}
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">ID del mapa (Google My Maps)</label>
                                                        <input type="text" name="google_map_id" class="form-control"
                                                            placeholder="Ej: 1Jz4UuG0kt3M9QwQpSBbYi2sg5_e287w"
                                                            value="{{ $sucursal->mapaEmbebido->google_map_id ?? '' }}"
                                                            required>
                                                        <small class="text-muted">
                                                            Pega solo el <b>ID</b>, no el iframe completo.
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary"
                                                        style="background-color:#e06d2a; color:#fff;">
                                                        Guardar mapa
                                                    </button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>

                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-warning text-center">
                                            No hay sucursales registradas.
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- DATATABLES -->
    <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>
</body>

</html>
