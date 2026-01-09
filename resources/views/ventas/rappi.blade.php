<!DOCTYPE html>
<html lang="es">

<head>
    <title>Gestión Rappi</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    {{-- ICONOS / BOOTSTRAP --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- DATATABLES --}}
    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .btn-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-orange {
            background-color: #e06d2a;
            color: #fff;
            border: none;
        }
        .btn-orange:hover {
            background-color: #c55a1f;
            color: #fff;
        }
        /* Botón de Volver Estilizado */
        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px; /* Bordes suavizados estilo iOS/SaaS moderno */
            background: #ffffff;
            color: #495057;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
        }

        .btn-back i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .btn-back:hover {
            background-color: #ffffff;
            color: #333; /* El color naranja de tu marca */
            border-color: #ced4da;
            transform: translateY(-2px);
        }

        .btn-back:hover i {
            transform: translateX(-4px); /* Pequeño rebote hacia la izquierda */
        }

        .btn-modern-back:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>

<div class="page-container">

    @section('ventas')
        class="active-page"
    @endsection

    @include('layouts.sidebar')

    <div class="page-content">
        <div class="main-wrapper">

            {{-- HEADER IGUAL A INOUT --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h4 class="fw-semibold mb-0" style="font-size:18px;">Gestión Rappi</h4>
                        <span class="text-muted small">Importación y control de órdenes desde la plataforma Rappi.</span>
                    </div>

                    <div class="d-flex" style="gap:12px;">
                        {{-- BOTÓN IMPORTAR --}}
                        <button type="button" class="btn btn-orange btn-sm d-flex align-items-center" 
                                style="gap:6px; border-radius: 8px;"
                                data-bs-toggle="modal" data-bs-target="#modalImportar">
                            <i class="fa-solid fa-upload"></i> Importar Datos
                        </button>

                        {{-- BOTÓN PLANTILLA (EXPORTAR) --}}
                        <a href="{{ route('rappi.plantilla') }}" 
                           class="btn btn-outline-secondary btn-sm d-flex align-items-center" 
                           style="gap:6px; border-radius: 8px;">
                            <i class="fa-solid fa-download"></i> Plantilla
                        </a>

                        {{-- BOTÓN VOLVER --}}
                        <a href="{{ route('ventas.index') }}" class="btn-back">
                            <i class="fa-solid fa-arrow-left-long"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- TABLA --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h6 class="mb-3 fw-bold">Detalle de órdenes Rappi</h6>

                    <div class="table-responsive">
                        <table id="tablaRappi" class="table table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>ID Orden</th>
                                    <th>Tienda</th>
                                    <th>Estado</th>
                                    <th class="text-end">Venta Bruta</th>
                                    <th class="text-end">A Transferir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ventas as $venta)
                                    <tr>
                                        <td data-sort="{{ $venta->fecha_creacion_orden ? $venta->fecha_creacion_orden->timestamp : 0 }}">
                                            {{ $venta->fecha_creacion_orden ? $venta->fecha_creacion_orden->format('d/m/Y H:i') : 'N/A' }}
                                        </td>
                                        <td class="fw-bold">{{ $venta->id_orden }}</td>
                                        <td>{{ $venta->nombre_tienda }}</td>
                                        <td>
                                            {{ $venta->estado_orden }}
                                        </td>
                                        <td class="text-end">$ {{ number_format($venta->venta_bruta, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold {{ $venta->valor_a_transferir < 0 ? 'text-danger' : 'text-success' }}">
                                            $ {{ number_format($venta->valor_a_transferir, 0, ',', '.') }}
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

{{-- MODAL IMPORTAR (ESTILO MEJORADO) --}}
<div class="modal fade" id="modalImportar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-semibold" style="font-size:16px;">Subir Archivo de Rappi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('rappi.upload') }}" method="POST" enctype="multipart/form-data" id="formImportar">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="archivoInput" class="form-label small fw-bold text-muted">SELECCIONA EL ARCHIVO (.XLSX O .CSV)</label>
                        <input class="form-control" type="file" id="archivoInput" name="archivo" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                    </div>

                    <div id="loadingMessage" class="alert alert-info d-none border-0 shadow-sm">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Procesando datos, por favor espera...
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-orange px-4" id="btnSubir">
                        Subir e Importar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#tablaRappi').DataTable({
            "order": [[0, "desc"]],
            "pageLength": 25,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            }
        });

        // Mostrar loading al enviar form
        $('#formImportar').on('submit', function() {
            $('#btnSubir').prop('disabled', true);
            $('#loadingMessage').removeClass('d-none');
        });
    });
</script>

{{-- SWEETALERTS --}}
@if (Session::has('success') || Session::has('error'))
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: "{{ Session::has('success') ? 'success' : 'error' }}",
            title: "{{ session('success') ?? session('error') }}",
            showConfirmButton: false,
            timer: 4000
        });
    </script>
@endif

</body>
</html>