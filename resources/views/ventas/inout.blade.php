<!DOCTYPE html>
<html lang="es">

<head>
    <title>Ventas InOut</title>
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
                    <div>
                        <h4 class="fw-semibold mb-0" style="font-size:18px;">Ventas InOut</h4>
                        <span class="text-muted small">Resumen de órdenes finalizadas desde InOut.</span>
                    </div>

                    <div class="d-flex" style="gap:12px;">
                        {{-- VER GRÁFICAS --}}
                        <a href="{{ route('ventas.inout.dashboard') }}"
                           class="btn btn-orange btn-sm d-flex align-items-center"
                           style="gap:6px;">
                            <i class="fa-solid fa-chart-line"></i> Ver gráficas
                        </a>

                        {{-- VOLVER --}}
                        <a href="{{ route('ventas.index') }}"
                           class="btn btn-outline-secondary btn-circle"
                           title="Volver">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ========================
                TABLA SERVER-SIDE
            ======================== --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Detalle de órdenes finalizadas</h6>

                    <div class="table-responsive">
                        <table id="tablaInout"
                               class="table table-bordered align-middle"
                               style="width: 100%;">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Plataforma</th>
                                    <th>Punto de venta</th>
                                    <th>Sucursal</th>
                                    <th>Ciudad</th>
                                    <th>Tipo</th>
                                    <th>Método de pago</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

{{-- ==========================
        SCRIPTS
=========================== --}}

{{-- jQuery OBLIGATORIO --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>

<script>
    $(document).ready(function () {

        $('#tablaInout').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('ventas.inout.data') }}", // 🔥 AQUI SE TRAE LA DATA

            columns: [
                { data: 'createdAt', name: 'createdAt' },
                { data: 'platform', name: 'platform' },
                {
                    data: null,
                    render: function (data) {
                        return data.pointSaleCode + ' - ' + data.pointSale;
                    }
                },
                { data: 'business', name: 'business' },
                { data: 'city', name: 'city' },
                { data: 'type', name: 'type' },
                { data: 'paymentMethod', name: 'paymentMethod' },
                { data: 'total', name: 'total' },
                { data: 'stateCurrent', name: 'stateCurrent' }
            ],

            pageLength: 25,
            order: [[0, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            }
        });

    });
</script>

</body>
</html>
