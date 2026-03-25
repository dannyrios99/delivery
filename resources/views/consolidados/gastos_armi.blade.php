<!DOCTYPE html>
<html lang="es">

<head>
    <title>Resumen de Domicilios</title>
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

        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #ffffff;
            color: #495057;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            color: #333;
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
                            <h5 class="mb-0 fw-semibold">Resumen de Domicilios</h5>
                            <small class="text-muted">
                                Consolidado por sede y rango de distancia
                            </small>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="d-flex gap-2">

                        <a
                            href="#"
                            class="btn btn-outline-dark"
                        >
                            <i class="fas fa-download me-2"></i>
                            Plantilla
                        </a>

                        <button
                            class="btn btn-dark"
                            data-bs-toggle="modal"
                            data-bs-target="#modalImportarResumenDomicilios"
                        >
                            <i class="fas fa-file-excel me-2"></i>
                            Importar
                        </button>

                    </div>

                </div>
            </div>

            {{-- TABLA --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold mb-0">Listado consolidado</h6>

                        <div class="text-end">
                            <small class="text-muted">Valor total domicilios</small>
                            <div class="fw-bold">
                                ${{ number_format($totalMonto ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tablaResumenDomicilios" class="table table-bordered table-striped align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Sede</th>
                                    <th>Rango KM</th>
                                    <th># Entregas</th>
                                    <th>Valor Venta</th>
                                    <th>Domicilio Hot</th>
                                    <th>Domicilio Armi</th>
                                    <th>Recargo KM</th>
                                    <th>Recargo Nocturno</th>
                                    <th>Recargo Domingo</th>
                                    <th>Valor Final</th>
                                    <th>Inversión Hot</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($registros as $row)
                                <tr class="text-end">
                                    <td class="text-start">{{ $row->sede }}</td>
                                    <td class="text-center">{{ $row->rango_km }}</td>
                                    <td>{{ $row->numero_entregas }}</td>

                                    <td>${{ number_format($row->valor_venta, 0, ',', '.') }}</td>
                                    <td>${{ number_format($row->domicilio_hot, 0, ',', '.') }}</td>
                                    <td>${{ number_format($row->domicilio_armi, 0, ',', '.') }}</td>

                                    <td>${{ number_format($row->recargo_km, 0, ',', '.') }}</td>
                                    <td>${{ number_format($row->recargo_nocturno, 0, ',', '.') }}</td>
                                    <td>${{ number_format($row->recargo_domingo, 0, ',', '.') }}</td>

                                    <td class="fw-bold">
                                        ${{ number_format($row->valor_final, 0, ',', '.') }}
                                    </td>

                                    <td class="fw-bold text-danger">
                                        ${{ number_format($row->inversion_hot, 0, ',', '.') }}
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

    {{-- MODAL IMPORTAR --}}
    <div
        class="modal fade"
        id="modalImportarResumenDomicilios"
        tabindex="-1"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header">
                    <h6 class="modal-title fw-semibold">
                        Importar Resumen de Domicilios
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form
                    action="{{ route('gastos-armi.importar') }}"
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

{{-- SCRIPTS --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#tablaResumenDomicilios').DataTable({
            pageLength: 25,
            order: [[9, 'desc']],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            }
        });
    });
</script>

</body>
</html>
