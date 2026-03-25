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
                    <div>
                        <h4 class="fw-semibold mb-0" style="font-size:18px;">Consolidado de ordenes canceladas</h4>
                        <span class="text-muted small">Resumen de órdenes canceladas.</span>
                    </div>

                    <div class="d-flex" style="gap:12px;">
                        {{-- VER GRÁFICAS --}}
                        <a href="{{ route('ventas.inout.dashboard') }}"
                           class="btn btn-orange btn-sm d-flex align-items-center"
                           style="gap:6px;">
                            <i class="fa-solid fa-chart-line"></i> Ver gráficas
                        </a>

                        {{-- VOLVER --}}
                        <a href="{{ route('ventas.index') }}" class="btn-back">
                            <i class="fa-solid fa-arrow-left-long"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- ========================
                TABLA SERVER-SIDE
            ======================== --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">

                    <h6 class="fw-semibold mb-4">
                        Detalle de órdenes finalizadas
                    </h6>

                    <div class="row g-4">

                        {{-- Canceladas --}}
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-white">
                                <div class="card-body">
                                    <p class="text-muted small mb-1">Canceladas</p>

                                    <h2 class="fw-bold mb-0">
                                        {{ $inout['canceladas']['cantidad'] }}
                                        <span class="fs-6 fw-normal text-muted">órdenes</span>
                                    </h2>

                                    <div class="mt-3">
                                        <p class="text-muted small mb-0">Impacto económico</p>
                                        <h6 class="fw-semibold mb-0">
                                            ${{ number_format($inout['canceladas']['monto'], 0, ',', '.') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Con novedad --}}
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-white">
                                <div class="card-body">
                                    <p class="text-muted small mb-1">Con novedad</p>

                                    <h2 class="fw-bold mb-0">
                                        {{ $inout['novedades']['cantidad'] }}
                                        <span class="fs-6 fw-normal text-muted">órdenes</span>
                                    </h2>

                                    <div class="mt-3">
                                        <p class="text-muted small mb-0">Ajustes parciales</p>
                                        <h6 class="fw-semibold mb-0">
                                            ${{ number_format($inout['novedades']['monto'], 0, ',', '.') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Impacto total --}}
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-white shadow-sm">
                                <div class="card-body">
                                    <p class="text-muted small mb-1">Impacto total</p>

                                    <h2 class="fw-bold mb-3">
                                        ${{ number_format($inout['impacto_total'], 0, ',', '.') }}
                                    </h2>

                                    <p class="text-muted small mb-0">
                                        Canceladas + ajustes
                                    </p>
                                </div>
                            </div>
                        </div>

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



</body>
</html>