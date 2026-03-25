<!DOCTYPE html>
<html lang="es">

<head>
    <title>Compensaciones</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    {{-- ICONOS / BOOTSTRAP --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- DATATABLES --}}
    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">

    <style>
        .btn-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

            {{-- HEADER --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">

                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ url()->previous() }}" class="btn-back">
                            <i class="fas fa-arrow-left"></i>
                        </a>

                        <div>
                            <h5 class="mb-0 fw-semibold">Compensaciones</h5>
                            <small class="text-muted">
                                Carga y control de compensaciones por plataforma
                            </small>
                        </div>
                    </div>

                    {{-- Botón Importar --}}
                   <div class="d-flex gap-2">

                        {{-- Descargar plantilla --}}
                        <a
                            href="{{ route('compensaciones.plantilla') }}"
                            class="btn btn-outline-dark"
                        >
                            <i class="fas fa-download me-2"></i>
                            Plantilla
                        </a>

                        {{-- Importar --}}
                        <button
                            class="btn btn-dark"
                            data-bs-toggle="modal"
                            data-bs-target="#modalImportarCompensaciones"
                        >
                            <i class="fas fa-file-excel me-2"></i>
                            Importar
                        </button>

                    </div>

                </div>
            </div>


            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold mb-0">Compensaciones registradas</h6>

                        <div class="text-end">
                            <small class="text-muted">Total compensado</small>
                            <div class="fw-bold">
                                ${{ number_format($totalMonto ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tablaCompensaciones" class="table table-bordered">
                            <thead class="text-muted">
                                <tr>
                                    <th>Orden</th>
                                    <th>Fecha</th>
                                    <th>Razón</th>
                                    <th class="text-end">Monto</th>
                                    <th>ID de paidlots relacionados</th>
                                    <th>Productos</th>
                                    <th>IDs Productos</th>
                                    <th>Comentarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compensaciones as $c)
                                    <tr>
                                        <td>{{ $c->orden_id ?? '-' }}</td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($c->fecha)->format('Y-m-d') }}
                                        </td>

                                        <td>{{ $c->razon ?? '-' }}</td>

                                        <td class="text-end fw-semibold">
                                            ${{ number_format($c->monto, 0, ',', '.') }}
                                        </td>

                                        <td>{{ $c->paidlots_ids ?? '-' }}</td>

                                        <td style="max-width: 250px;">
                                                {{ $c->productos ?? '-' }}
                                        </td>

                                        <td style="max-width: 200px;">
                                                {{ $c->productos_ids ?? '-' }}
                                        </td>

                                        <td style="max-width: 250px;">
                                                {{ $c->comentarios ?? '-' }}
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


    <div
        class="modal fade"
        id="modalImportarCompensaciones"
        tabindex="-1"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header">
                    <h6 class="modal-title fw-semibold">
                        Importar compensaciones
                    </h6>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>
                </div>

                <form
                    action="{{ route('compensaciones.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Archivo Excel</label>
                            <input
                                type="file"
                                name="archivo"
                                class="form-control"
                                required
                            >
                            <small class="text-muted">
                                Plantilla: plataforma, orden_id, fecha, razon, monto, moneda, referencia
                            </small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancelar
                        </button>

                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-upload me-2"></i>
                            Importar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>


</div>

{{-- ==========================
        SCRIPTS
=========================== --}}
<script>
    $(document).ready(function () {
        $('#tablaCompensaciones').DataTable({
            pageLength: 25,
            order: [[2, 'desc']],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            }
        });
    });
</script>


{{-- jQuery OBLIGATORIO --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>

</body>
</html>