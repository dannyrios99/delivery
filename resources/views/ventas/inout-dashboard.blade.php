<!DOCTYPE html>
<html lang="es">

<head>
    <title>Dashboard InOut</title>

    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-orange: #e06d2a;
            --primary-orange-light: #fbeeda;
            --text-main: rgb(91, 91, 91);
            --bg-body: rgb(243, 244, 247);
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .fw-semibold {
            font-weight: 600;
        }

        .page-container {
            display: block;
            /* Cambio de flex a block para cooperar con sidebar fixed */
            min-height: 100vh;
        }

        .page-content {
            margin-left: 250px;
            /* Ancho real del sidebar para evitar solapamiento */
            transition: margin-left 0.3s ease;
            min-width: 0;
        }

        .main-wrapper {
            padding: 1rem 2.5rem 4rem 2.5rem;
            /* Padding equilibrado a ambos lados */
            margin: 0 auto;
            max-width: 1750px;
        }

        /* Responsividad: En móviles el sidebar se oculta */
        @media (max-width: 991px) {
            .page-content {
                margin-left: 0;
            }

            .main-wrapper {
                padding: 1rem;
            }
        }

        /* Section Header Cards */
        .section-header-card {
            background: #fff;
            border-radius: 14px;
            padding: 12px 20px;
            margin-bottom: 25px;
            border-left: 5px solid var(--primary-orange);
            display: flex;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .section-header-card h5 {
            margin-bottom: 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .section-header-card i {
            margin-right: 12px;
            color: var(--primary-orange);
            font-size: 1.2rem;
        }

/* =====================================================
           TABS Y TEXTOS (Fuera del overlay)
        ===================================================== */
        .nav-pills-custom .nav-link {
            color: #64748b;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            margin-right: 0.5rem;
            transition: all 0.2s ease;
        }

        .nav-pills-custom .nav-link.active {
            background-color: var(--primary-orange);
            color: #fff;
            box-shadow: 0 4px 12px rgba(224, 109, 42, 0.2);
        }

        .nav-pills-custom .nav-link:hover:not(.active) {
            background-color: #f1f5f9;
            color: #334155;
        }

        .text-orange {
            color: var(--primary-orange);
        }

        /* =====================================================
           EL FONDO DEL LOADER
        ===================================================== */
        .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(2px);
            display: none; /* JS lo cambia a 'flex' cuando carga */
            justify-content: center;
            align-items: center;
            border-radius: 16px;
            z-index: 10;
            pointer-events: none;
        }

        /* =====================================================
           EL SPINNER (Rueda giratoria)
        ===================================================== */
        .loading-overlay .spinner {
            width: 42px;
            height: 42px;
            border: 4px solid #e2e8f0 !important; /* Gris muy claro */
            border-top: 4px solid var(--primary-orange) !important; /* Naranja */
            border-radius: 50% !important;
            background-color: transparent !important;
            animation: girar-loader 0.8s linear infinite !important;
        }

        @keyframes girar-loader {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

            .chart-wrapper {
                position: relative;
                min-height: 260px;
            }

            .apexcharts-marker {
                cursor: pointer;
            }

            /* =====================================================
   CARD HEADER MODERNO
===================================================== */

            .card-header-modern {
                border-radius: 18px;
                background: linear-gradient(145deg, #ffffff, #f9fafb);
                position: relative; /* Necesario para que el z-index funcione */
                z-index: 900;      /* Suficiente para estar sobre las cards pero bajo el sidebar */
            }

            /* =====================================================
   BOTÓN VOLVER
===================================================== */

            .btn-back {
                width: 42px;
                height: 42px;
                border-radius: 14px;
                background: #ffffff;
                border: 1px solid #e5e7eb;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #495057;
                transition: all 0.25s ease;
            }

            .btn-back:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            }

            /* =====================================================
   INFO RANGO
===================================================== */

            .rango-label {
                font-size: 0.75rem;
                letter-spacing: 0.5px;
                color: #6b7280;
            }

            .rango-fecha {
                font-weight: 600;
                font-size: 0.95rem;
                color: #111827;
            }

            /* =====================================================
   BOTÓN FILTRO
===================================================== */

            .btn-filter {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 18px;
                border-radius: 999px;
                background: #f8fafc;
                border: 1px solid #e5e7eb;
                font-weight: 500;
                transition: all 0.25s ease;
            }

            .btn-filter:hover {
                background: #f1f5f9;
                transform: translateY(-1px);
            }

            /* =====================================================
   BOTÓN VER GRÁFICAS
===================================================== */

            .btn-graph {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 18px;
                border-radius: 999px;
                background: #f97316;
                color: white;
                font-weight: 500;
                border: none;
                transition: all 0.25s ease;
            }

            .btn-graph:hover {
                background: #ea580c;
                transform: translateY(-1px);
            }

            /* =====================================================
   DROPDOWN MODERNO
===================================================== */
            .dropdown-modern {
                width: 340px;
                border-radius: 18px;
                border: 1px solid #e5e7eb;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Sombra un poco más marcada para profundidad */
                overflow: visible !important; /* Permite que el contenido respire */
                padding: 0;
                z-index: 9999 !important; /* Prioridad máxima absoluta */
            }

            /* =====================================================
   SECCIONES DEL DROPDOWN
===================================================== */

            .dropdown-section {
                padding-top: 14px;
            }

            .dropdown-section-header {
                font-size: 0.70rem;
                font-weight: 600;
                color: #6b7280;
                text-transform: uppercase;
                letter-spacing: 0.6px;
                padding: 0 16px 10px 16px;
            }

            /* Línea divisora fuerte */
            .dropdown-divider-strong {
                height: 1px;
                background: #e5e7eb;
                margin: 12px 0;
            }

            /* =====================================================
   RANGOS RÁPIDOS (AHORA SÍ PARECEN MENÚ REAL)
===================================================== */

            .quick-ranges {
                display: flex;
                flex-direction: column;
            }

            .quick-item {
                background: transparent;
                border: none;
                text-align: left;
                padding: 12px 16px;
                font-size: 0.9rem;
                font-weight: 500;
                color: #111827;
                transition: all 0.2s ease;
            }

            /* Línea sutil entre cada opción */
            .quick-item+.quick-item {
                border-top: 1px solid #f1f5f9;
            }

            .quick-item:hover {
                background: #f9fafb;
                cursor: pointer;
            }

            /* Estado activo */
            .quick-item.active {
                background: #fff7ed;
                color: #f97316;
                font-weight: 600;
            }

            /* =====================================================
   INPUTS MODERNOS
===================================================== */

            .form-control-modern {
                border-radius: 10px;
                border: 1px solid #e5e7eb;
                font-size: 0.85rem;
            }

            .form-control-modern:focus {
                border-color: #f97316;
                box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
            }

            /* =====================================================
   BOTÓN APLICAR (CTA REAL)
===================================================== */

            .btn-apply {
                width: 100%;
                border-radius: 12px;
                padding: 10px;
                background: #f97316;
                color: white;
                font-weight: 600;
                border: none;
                transition: all 0.25s ease;
            }

            .btn-apply:hover {
                background: #ea580c;
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(249, 115, 22, 0.25);
            }

            .btn-apply:active {
                transform: translateY(0);
            }
    </style>
</head>

<body>

    <div class="page-container">

        @include('layouts.sidebar')

        <div class="page-content">
            <div class="main-wrapper">

                {{-- ===================== --}}
                {{-- FILTRO DE FECHAS --}}
                {{-- ===================== --}}

                <!-- CARD HEADER SUPERIOR -->
                <!-- ==============================
     HEADER SUPERIOR MODERNO
================================= -->
                <div class="card card-header-modern shadow-sm mb-4 border-0">
                    <div class="card-body px-4 py-3">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                            <!-- IZQUIERDA -->
                            <div class="d-flex align-items-center gap-3">

                                <!-- Botón volver -->
                                <a href="{{ route('ventas.index') }}" class="btn-back">
                                    <i class="fa-solid fa-arrow-left-long"></i>
                                </a>

                                <!-- Información del rango -->
                                <div class="rango-info">
                                    <span class="rango-label">Mostrando</span>
                                    <div id="label-rango" class="rango-fecha">
                                        <!-- Se actualiza dinámicamente con JS -->
                                    </div>
                                </div>

                            </div>

                            <!-- DERECHA (FILTROS Y ACCIÓN) -->
                            <div class="d-flex align-items-center gap-3 ms-auto">

                                <!-- Grupo de Dropdowns -->
                                <div class="d-flex align-items-center gap-2">
                                    <!-- SUCURSAL -->
                                    <div class="dropdown">
                                        <button class="btn btn-filter" type="button" id="btnDropdownSucursal"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-store"></i>
                                            <span id="label-sucursal">Sucursal: Todas</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-modern p-2"
                                            style="min-width: 200px;">
                                            <li>
                                                <button class="dropdown-item rounded item-sucursal active"
                                                    data-sucursal="Todas">
                                                    Todas
                                                </button>
                                            </li>
                                            @foreach ($sucursales as $suc)
                                                <li>
                                                    <button class="dropdown-item rounded item-sucursal"
                                                        data-sucursal="{{ $suc }}">
                                                        {{ $suc }}
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <!-- FECHA -->
                                    <div class="dropdown">
                                        <button class="btn btn-filter" type="button" id="btnDropdownRango"
                                            data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                            aria-expanded="false">
                                            <i class="fa-regular fa-calendar"></i>
                                            <span>Filtrar por fecha</span>
                                            <i class="fa-solid fa-chevron-down small"></i>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-end dropdown-modern"
                                            aria-labelledby="btnDropdownRango">

                                            <div class="dropdown-section">
                                                <div class="dropdown-section-header">Rangos rápidos</div>
                                                <div class="quick-ranges">
                                                    <button type="button" class="quick-item rango-rapido"
                                                        data-range="today">Hoy</button>
                                                    <button type="button" class="quick-item rango-rapido"
                                                        data-range="last7">Últimos 7 días</button>
                                                    <button type="button" class="quick-item rango-rapido"
                                                        data-range="month">Este mes</button>
                                                    <button type="button" class="quick-item rango-rapido"
                                                        data-range="prevMonth">Mes anterior</button>
                                                </div>
                                            </div>

                                            <div class="dropdown-divider-strong"></div>

                                            <div class="dropdown-section">
                                                <div class="dropdown-section-header">Rango personalizado</div>
                                                <div class="range-custom px-3 pb-3">
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label small mb-1">Desde</label>
                                                            <input type="date" id="filtro-from"
                                                                class="form-control form-control-modern"
                                                                value="{{ $defaultFrom }}">
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label small mb-1">Hasta</label>
                                                            <input type="date" id="filtro-to"
                                                                class="form-control form-control-modern"
                                                                value="{{ $defaultTo }}">
                                                        </div>
                                                    </div>
                                                    <button class="btn-apply" type="button"
                                                        id="btn-aplicar-rango">Aplicar rango</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón de acción -->
                                <a href="{{ route('ventas.inout') }}" class="btn btn-graph shadow-sm">
                                    <i class="fa-solid fa-receipt"></i>
                                    Detalle órdenes
                                </a>

                            </div> <!-- Fin .d-flex derecha -->

                        </div> <!-- Fin .d-flex justify-content-between -->
                    </div> <!-- Fin .card-body -->
                </div> <!-- Fin .card-header-modern -->




                {{-- ===================== --}}
                {{-- RESUMEN CANAL + SUCURSALES --}}
                {{-- ===================== --}}

                {{-- ======================================================== --}}
                {{-- SECCIÓN DE KPIs RESUMEN --}}
                {{-- ======================================================== --}}
                <div class="row g-4 mb-2">

                    <!-- Venta total -->
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted fw-bold small text-uppercase mb-2">Venta total del periodo</h6>
                                <h3 id="kpi-venta-total" class="fw-bold mb-0 text-orange">$0</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Call Center -->
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted fw-bold small text-uppercase mb-2">Ventas Call Center</h6>
                                <h3 id="kpi-call" class="fw-bold mb-0 text-primary">$0</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Web -->
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted fw-bold small text-uppercase mb-2">Ventas Web/App</h6>
                                <h3 id="kpi-web" class="fw-bold mb-0 text-success">$0</h3>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ======================================================== --}}
                {{-- ANÁLISIS DE COMPORTAMIENTO (CLIENTES) --}}
                {{-- ======================================================== --}}
                <!-- Título como Card -->
                <div class="section-header-card">
                    <i class="fa-solid fa-user-check"></i>
                    <h5>Análisis de Comportamiento</h5>
                </div>
                <div class="row g-4 mb-5 align-items-stretch">

                    <!-- Frecuencia diaria -->
                    <div class="col-md-7 d-flex">
                        <div class="card shadow-sm w-100 h-100">
                            <div class="card-body d-flex flex-column">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    Frecuencia de clientes únicos
                                    <i class="fa-solid fa-circle-info ms-2 text-muted small"
                                        title="Clientes que regresan por día"></i>
                                </h6>
                                <div class="chart-wrapper flex-grow-1">
                                    <div class="loading-overlay">
                                        <div class="spinner"></div>
                                    </div>
                                    <div id="chart-frecuencia-clientes"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Frecuencia por hora -->
                    <div class="col-md-5 d-flex">
                        <div class="card shadow-sm w-100 h-100">
                            <div class="card-body d-flex flex-column">
                                <h6 class="fw-bold mb-3">
                                    Picos de retorno por hora
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">¿A qué hora
                                        prefieren pedir?</small>
                                </h6>
                                <div class="chart-wrapper flex-grow-1">
                                    <div class="loading-overlay">
                                        <div class="spinner"></div>
                                    </div>
                                    <div id="chart-frecuencia-hora"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4 mb-5 align-items-stretch">
                    <!-- Top Productos (Izquierda) -->
                    <div class="col-lg-12">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-4">
                                    <i class="fa-solid fa-star text-warning me-2"></i> Top productos del periodo
                                </h6>
                                <div class="table-responsive">
                                    <table id="tabla-top-productos" class="table table-hover align-middle"
                                        style="width:100%">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0">#</th>
                                                <th class="border-0">Producto</th>
                                                <th class="border-0 text-end">Vendidas</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ======================================================== --}}
                {{-- DISTRIBUCIÓN Y LOGÍSTICA --}}
                {{-- ======================================================== --}}
                <!-- Título como Card -->
                <div class="section-header-card">
                    <i class="fa-solid fa-truck-fast"></i>
                    <h5>Distribución y Logística</h5>
                </div>
                <div class="row g-4 mb-5">

                    <!-- Canal y Sucursales en el mismo nivel -->
                    <div class="col-md-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-4">Participación por canal</h6>
                                <div class="chart-wrapper">
                                    <div class="loading-overlay">
                                        <div class="spinner"></div>
                                    </div>
                                    <div id="chart-canal"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-4">Top sedes por volumen de órdenes</h6>
                                <div class="chart-wrapper">
                                    <div class="loading-overlay">
                                        <div class="spinner"></div>
                                    </div>
                                    <div id="chart-sucursales"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Forma de Pago y Entrega -->
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold mb-4">Métodos de pago preferidos</h6>
                                <div class="chart-wrapper">
                                    <div class="loading-overlay">
                                        <div class="spinner"></div>
                                    </div>
                                    <div id="chart-formas-pago"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold mb-4">Preferencia de entrega</h6>
                                <div class="chart-wrapper">
                                    <div class="loading-overlay">
                                        <div class="spinner"></div>
                                    </div>
                                    <div id="chart-entrega"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ======================================================== --}}
                {{-- TENDENCIAS E INVENTARIO --}}
                {{-- ======================================================== --}}
                <div class="row g-4 mb-5">

                                    <div class="col-lg-5">
                    <div class="card shadow-sm border-0 bg-white mb-5 h-100"
                        style="border-left: 5px solid #ef4444 !important;">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-4 text-danger d-flex align-items-center">
                                <i class="fa-solid fa-circle-xmark me-2"></i> Reporte de Órdenes Canceladas
                            </h6>
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-danger-subtle p-3 rounded-circle me-3">
                                            <i class="fa-solid fa-ban text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h3 id="canceladas-total-ordenes" class="mb-0 fw-bold text-danger">0</h3>
                                            <p class="text-muted small mb-0">Total órdenes canceladas</p>
                                        </div>
                                    </div>
                                    <div class="text-danger fw-600 fs-5 mt-2">
                                        Perdida estimada: <span id="canceladas-total-valor">$ 0</span>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="chart-wrapper">
                                        <div class="loading-overlay">
                                            <div class="spinner"></div>
                                        </div>
                                        <div id="chart-canceladas-sucursales"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- Histórico (Derecha) -->
                    <div class="col-lg-7">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-4">Histórico y Tendencias</h6>

                                <ul class="nav nav-pills nav-pills-custom mb-4" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pills-diario-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-diario" type="button"
                                            role="tab">Diario</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-semanal-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-semanal" type="button"
                                            role="tab">Semanal</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-mensual-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-mensual" type="button"
                                            role="tab">Mensual</button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-diario" role="tabpanel">
                                        <div class="chart-wrapper">
                                            <div class="loading-overlay">
                                                <div class="spinner"></div>
                                            </div>
                                            <div id="chart-hist-diario"></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-semanal" role="tabpanel">
                                        <div class="chart-wrapper">
                                            <div class="loading-overlay">
                                                <div class="spinner"></div>
                                            </div>
                                            <div id="chart-hist-semanal"></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-mensual" role="tabpanel">
                                        <div class="chart-wrapper">
                                            <div class="loading-overlay">
                                                <div class="spinner"></div>
                                            </div>
                                            <div id="chart-hist-mensual"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ======================================================== --}}
                {{-- ÓRDENES CANCELADAS --}}
                {{-- ======================================================== --}}

            </div>
        </div>

    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let diaSeleccionado = null;
            let fechasFrecuencia = []; // Guarda las fechas del gráfico de clientes para el clic

            const btnDropdown = document.getElementById('btnDropdownRango');

            function cerrarDropdown() {
                const instance = bootstrap.Dropdown.getInstance(btnDropdown);
                if (instance) {
                    instance.hide();
                }
            }
            // ===============================
            // 1) FUNCIONES UTILITARIAS
            // ===============================

            function formatDate(d) {
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function showLoading() {
                document.querySelectorAll('.loading-overlay').forEach(o => o.style.display = 'flex');
                const btn = document.getElementById('btn-aplicar-rango');
                if (btn) {
                    btn.disabled = true;
                    btn.innerText = "Cargando...";
                }
            }

            function hideLoading() {
                const btn = document.getElementById('btn-aplicar-rango');
                if (btn) {
                    btn.disabled = false;
                    btn.innerText = "Aplicar";
                }
                // refreshDashboard(); <-- SE ELIMINA LA LLAMADA RECURSIVA QUE CAUSA BUCLE INFINITO
            }

            // 6. CAMBIO DE SUCURSAL
            $(document).on('click', '.item-sucursal', function() {
                const suc = $(this).data('sucursal');
                selectedSucursal = suc;

                // UI
                $('.item-sucursal').removeClass('active');
                $(this).addClass('active');

                refreshDashboard();
            });

            function hideSectionLoading(selector) {
                const container = document.querySelector(selector);
                if (container) {
                    const overlay = container.closest('.card-body')?.querySelector('.loading-overlay') ||
                        container.closest('.chart-wrapper')?.querySelector('.loading-overlay');
                    if (overlay) overlay.style.display = 'none';
                }
            }

            // Activa SOLO los spinners de las 3 secciones de detalle del día (horarios, top productos)
            function showDetalleLoading() {
                ['#chart-frecuencia-hora', '#tabla-top-productos'].forEach(sel => {
                    const el = document.querySelector(sel);
                    if (el) {
                        const overlay = el.closest('.card-body')?.querySelector('.loading-overlay') ||
                            el.closest('.chart-wrapper')?.querySelector('.loading-overlay');
                        if (overlay) overlay.style.display = 'flex';
                    }
                });
            }

            // Oculta SOLO los spinners de las 3 secciones de detalle del día
            function hideDetalleLoading() {
                ['#chart-frecuencia-hora', '#tabla-top-productos'].forEach(sel => {
                    hideSectionLoading(sel);
                });
            }

            function actualizarLabelRango() {
                const from = document.getElementById('filtro-from').value;
                const to = document.getElementById('filtro-to').value;
                const label = document.getElementById('label-rango');

                if (!from || !to) {
                    label.textContent = "";
                    return;
                }

                function bonito(iso) {
                    const [y, m, d] = iso.split("-");
                    return `${d}/${m}/${y}`;
                }

                label.textContent = `${bonito(from)} – ${bonito(to)}`;
            }

            function setRange(type) {
                const today = new Date();
                let from, to;

                switch (type) {
                    case "today":
                        from = to = formatDate(today);
                        break;

                    case "last7":
                        const d = new Date();
                        d.setDate(today.getDate() - 6);
                        from = formatDate(d);
                        to = formatDate(today);
                        break;

                    case "month":
                        const first = new Date(today.getFullYear(), today.getMonth(), 1);
                        from = formatDate(first);
                        to = formatDate(today);
                        break;

                    case "prevMonth":
                        const firstPrev = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                        const lastPrev = new Date(today.getFullYear(), today.getMonth(), 0);
                        from = formatDate(firstPrev);
                        to = formatDate(lastPrev);
                        break;
                }

                document.getElementById('filtro-from').value = from;
                document.getElementById('filtro-to').value = to;

                actualizarLabelRango();
            }

            // ===============================
            // 2) LISTENERS DE RANGOS
            // ===============================

            document.querySelectorAll('.rango-rapido').forEach(btn => {
                btn.addEventListener('click', function() {
                    diaSeleccionado = null; // Reset selection
                    setRange(this.dataset.range);
                    refreshDashboard();
                });
            });

            document.getElementById('btn-aplicar-rango').addEventListener('click', function() {
                diaSeleccionado = null; // Reset selection
                actualizarLabelRango();
                refreshDashboard();
            });

            // ===============================
            // 3) INICIALIZACIÓN DE GRÁFICAS
            // ===============================

            const chartCanal = new ApexCharts(document.querySelector("#chart-canal"), {
                chart: {
                    type: 'pie',
                    height: 315
                },
                labels: [],
                series: [],
                legend: {
                    position: 'bottom'
                }
            });
            chartCanal.render();

            const chartSucursales = new ApexCharts(document.querySelector("#chart-sucursales"), {
                chart: {
                    type: 'bar',
                    height: 300
                },

                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },

                xaxis: {
                    categories: []
                },

                series: [{
                    name: 'Órdenes',
                    data: []
                }],

                tooltip: {
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {

                        const ordenes = series[seriesIndex][dataPointIndex];
                        const venta = w.config.extraData[dataPointIndex];

                        return `
                        <div style="padding:10px">
                            <strong>${w.globals.labels[dataPointIndex]}</strong><br>
                            🧾 Órdenes: ${ordenes}<br>
                            💰 Venta: ${new Intl.NumberFormat('es-CO', {
                                style: 'currency',
                                currency: 'COP'
                            }).format(venta)}
                        </div>
                    `;
                    }
                },

                extraData: [] // 🔥 aquí guardamos ventas
            });

            chartSucursales.render();

            let chartFrecuenciaClientes = null;

            const elFrecuencia = document.querySelector("#chart-frecuencia-clientes");

            if (elFrecuencia) {
                chartFrecuenciaClientes = new ApexCharts(elFrecuencia, {
                    chart: {
                        type: 'area',
                        height: 280,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        },
                        events: {
                            markerClick: function(event, chartContext, config) {
                                const dataIndex = config.dataPointIndex;
                                if (dataIndex === -1) return;

                                const fechaSeleccionada = fechasFrecuencia[dataIndex];
                                console.log("Punto seleccionado:", {
                                    index: dataIndex,
                                    fecha: fechaSeleccionada
                                });

                                if (fechaSeleccionada) {
                                    cargarDetalleDia(fechaSeleccionada);
                                }
                            }
                        }
                    },

                    series: [{
                        name: 'Clientes Únicos',
                        data: []
                    }],

                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },

                    markers: {
                        size: 5,
                        hover: {
                            size: 7
                        }
                    },

                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.05,
                            stops: [0, 100]
                        }
                    },

                    colors: ['#ff5722'],

                    grid: {
                        show: false
                    },

                    dataLabels: {
                        enabled: false
                    },

                    xaxis: {
                        categories: [],
                        labels: {
                            show: true,
                            style: {
                                fontSize: '12px'
                            }
                        },
                        axisBorder: {
                            show: true,
                            color: '#e5e7eb'
                        },
                        axisTicks: {
                            show: false
                        }
                    },

                    yaxis: {
                        show: true,
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },

                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " clientes";
                            }
                        }
                    }
                });

                chartFrecuenciaClientes.render();
            }

            let chartFrecuenciaHora = null;

            const elHora = document.querySelector("#chart-frecuencia-hora");

            if (elHora) {
                chartFrecuenciaHora = new ApexCharts(elHora, {
                    chart: {
                        type: 'bar',
                        height: 280,
                        toolbar: {
                            show: false
                        },
                        events: {
                            click: function(event, chartContext, config) {

                                if (config.dataPointIndex === -1) return;

                                const categorias = chartContext.w.config.xaxis.categories;
                                const horaTexto = categorias[config.dataPointIndex];

                                if (!horaTexto) return;

                                const horaSeleccionada = parseInt(horaTexto.replace(":00", ""));

                                if (diaSeleccionado) {
                                    cargarProductosPorHora(diaSeleccionado, horaSeleccionada);
                                } else {
                                    const from = document.getElementById('filtro-from').value;
                                    const to = document.getElementById('filtro-to').value;

                                    cargarProductosPorHoraRango(from, to, horaSeleccionada);
                                }
                            }
                        }
                    },

                    series: [{
                        name: 'Clientes Únicos',
                        data: []
                    }],

                    plotOptions: {
                        bar: {
                            borderRadius: 15,
                            columnWidth: '70%',

                        }
                    },

                    dataLabels: {
                        enabled: false
                    },

                    xaxis: {
                        categories: [],
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },

                    yaxis: {
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },

                    grid: {
                        strokeDashArray: 4,
                        borderColor: '#f1f1f1'
                    },

                    colors: ['#ff5722'],

                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " clientes";
                            }
                        }
                    }
                });

                chartFrecuenciaHora.render();
            }

            let tablaTop = $('#tabla-top-productos').DataTable({
                pageLength: 10,
                order: [
                    [2, 'desc']
                ],
                destroy: true,
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ productos",
                    paginate: {
                        previous: "Anterior",
                        next: "Siguiente"
                    }
                }
            });

            const chartFormasPago = new ApexCharts(document.querySelector("#chart-formas-pago"), {
                chart: {
                    type: 'pie',
                    height: 260
                },
                labels: [],
                series: [],
                legend: {
                    position: 'bottom'
                }
            });
            chartFormasPago.render();

            const chartEntrega = new ApexCharts(document.querySelector("#chart-entrega"), {
                chart: {
                    type: 'pie',
                    height: 260
                },
                labels: [],
                series: [],
                legend: {
                    position: 'bottom'
                }
            });
            chartEntrega.render();

            const chartHistDiario = new ApexCharts(document.querySelector("#chart-hist-diario"), {
                chart: {
                    type: 'line',
                    height: 230
                },
                series: [{
                    name: 'Órdenes',
                    data: []
                }],
                xaxis: {
                    categories: []
                },
                markers: {
                    size: 4
                }
            });
            chartHistDiario.render();

            const chartHistSemanal = new ApexCharts(document.querySelector("#chart-hist-semanal"), {
                chart: {
                    type: 'line',
                    height: 230
                },
                series: [{
                    name: 'Órdenes',
                    data: []
                }],
                xaxis: {
                    categories: []
                },
                markers: {
                    size: 4
                }
            });
            chartHistSemanal.render();

            const chartHistMensual = new ApexCharts(document.querySelector("#chart-hist-mensual"), {
                chart: {
                    type: 'line',
                    height: 230
                },
                series: [{
                    name: 'Órdenes',
                    data: []
                }],
                xaxis: {
                    categories: []
                },
                markers: {
                    size: 4
                }
            });
            chartHistMensual.render();

            const chartCanceladasSucursales = new ApexCharts(document.querySelector(
                "#chart-canceladas-sucursales"), {
                chart: {
                    type: 'bar',
                    height: 260
                },
                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },
                xaxis: {
                    categories: []
                },
                series: [{
                    name: 'Canceladas',
                    data: []
                }]
            });
            // ===============================
            // 4) FUNCIÓN QUE TRAE LOS DATOS
            // ===============================

            let selectedSucursal = "Todas"; // Variable global única

            /**
             * 1. REFRESCAR TODO EL DASHBOARD
             */
            function refreshDashboard() {
                const from = document.getElementById('filtro-from').value;
                const to = document.getElementById('filtro-to').value;
                const suc = selectedSucursal;

                if (!from || !to) return;

                // Actualizar labels
                actualizarLabelRango();
                document.getElementById('label-sucursal').textContent = "Sucursal: " + suc;

                const params = `?from=${from}&to=${to}&sucursal=${encodeURIComponent(suc)}`;

                showLoading();

                // 1. Cargar KPIs
                fetch(`{{ route('ventas.inout.api.kpis') }}${params}`).then(res => res.json()).then(data => {
                    const total = data.venta_total?.total ?? 0;
                    document.getElementById('kpi-venta-total').innerText = new Intl.NumberFormat('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    }).format(total);
                    document.getElementById('kpi-call').innerText = new Intl.NumberFormat('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    }).format(data.call || 0);
                    document.getElementById('kpi-web').innerText = new Intl.NumberFormat('es-CO', {
                        style: 'currency',
                        currency: 'COP'
                    }).format(data.web || 0);
                }).catch(e => console.error("Error KPIs:", e));

                // 2. Cargar Gráficas de Pastel/Barras sucursales
                fetch(`{{ route('ventas.inout.api.charts') }}${params}`).then(res => res.json()).then(data => {
                    chartCanal.updateOptions({
                        labels: data.canal.map(i => i.canal)
                    });
                    chartCanal.updateSeries(data.canal.map(i => Number(i.total)));
                    hideSectionLoading("#chart-canal");

                    chartSucursales.updateOptions({
                        xaxis: {
                            categories: data.sucursales.map(i => i.sucursal)
                        },
                        extraData: data.sucursales.map(i => Number(i.total_venta))
                    });
                    chartSucursales.updateSeries([{
                        data: data.sucursales.map(i => Number(i.total))
                    }]);
                    hideSectionLoading("#chart-sucursales");

                    chartFormasPago.updateOptions({
                        labels: data.formasPago.map(i => i.forma_pago)
                    });
                    chartFormasPago.updateSeries(data.formasPago.map(i => Number(i.total)));
                    hideSectionLoading("#chart-formas-pago");

                    chartEntrega.updateOptions({
                        labels: data.entrega.map(i => i.tipo_entrega)
                    });
                    chartEntrega.updateSeries(data.entrega.map(i => Number(i.total)));
                    hideSectionLoading("#chart-entrega");
                }).catch(e => console.error("Error Charts:", e));

                // 3. Cargar Históricos
                fetch(`{{ route('ventas.inout.api.historicos') }}${params}`).then(res => res.json()).then(data => {
                    chartHistDiario.updateOptions({
                        xaxis: {
                            categories: data.diario.map(i => i.fecha)
                        }
                    });
                    chartHistDiario.updateSeries([{
                        data: data.diario.map(i => Number(i.total))
                    }]);
                    hideSectionLoading("#chart-hist-diario");

                    chartHistSemanal.updateOptions({
                        xaxis: {
                            categories: data.semanal.map(i => i.semana)
                        }
                    });
                    chartHistSemanal.updateSeries([{
                        data: data.semanal.map(i => Number(i.total))
                    }]);
                    hideSectionLoading("#chart-hist-semanal");

                    const meses = ["", "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct",
                        "Nov", "Dic"
                    ];
                    chartHistMensual.updateOptions({
                        xaxis: {
                            categories: data.mensual.map(i => meses[i.mes])
                        }
                    });
                    chartHistMensual.updateSeries([{
                        data: data.mensual.map(i => Number(i.total))
                    }]);
                    hideSectionLoading("#chart-hist-mensual");
                }).catch(e => console.error("Error Históricos:", e));

                // 4. Cargar Frecuencias Clientes
                fetch(`{{ route('ventas.inout.api.frecuencias') }}${params}`).then(res => res.json()).then(
                    data => {
                        if (chartFrecuenciaClientes) {
                            const categorias = data.frecuencia_clientes.map(i => i.fecha);
                            const valores = data.frecuencia_clientes.map(i => Number(i.total_clientes));
                            fechasFrecuencia = categorias; // Guardamos las fechas para el clic
                            chartFrecuenciaClientes.updateOptions({
                                xaxis: {
                                    categories: categorias
                                },
                                series: [{
                                    name: 'Clientes Únicos',
                                    data: valores
                                }]
                            });
                            hideSectionLoading("#chart-frecuencia-clientes");
                        }
                    }).catch(e => console.error("Error Frecuencias:", e));

                // 5. Cargar Horarios
                fetch(`{{ route('ventas.inout.api.horarios') }}${params}`).then(res => res.json()).then(data => {
                    if (chartFrecuenciaHora) {
                        chartFrecuenciaHora.updateOptions({
                            xaxis: {
                                categories: data.frecuencia_hora.map(i => i.hora + ":00")
                            },
                            series: [{
                                data: data.frecuencia_hora.map(i => Number(i
                                    .total_clientes))
                            }]
                        });
                        hideSectionLoading("#chart-frecuencia-hora");
                    }
                }).catch(e => console.error("Error Horarios:", e));

                // 6. Cargar Productos Top
                fetch(`{{ route('ventas.inout.api.top-productos') }}${params}`).then(res => res.json()).then(
                    data => {
                        tablaTop.clear();
                        data.productos_top.forEach((item, index) => {
                            tablaTop.row.add([index + 1, item.product, Number(item.total_vendido)
                                .toLocaleString()
                            ]);
                        });
                        tablaTop.draw();
                        hideSectionLoading("#tabla-top-productos");
                    }).catch(e => console.error("Error Top Productos:", e));

                // 7. Cargar Canceladas
                fetch(`{{ route('ventas.inout.api.canceladas') }}${params}`).then(res => res.json()).then(data => {
                    document.getElementById('canceladas-total-ordenes').innerText = data.resumen
                        .total_ordenes ?? 0;
                    document.getElementById('canceladas-total-valor').innerText = new Intl.NumberFormat(
                        'es-CO', {
                            style: 'currency',
                            currency: 'COP'
                        }).format(data.resumen.total_valor ?? 0);
                    chartCanceladasSucursales.updateOptions({
                        xaxis: {
                            categories: data.por_sucursal.map(i => i.sucursal)
                        }
                    });
                    chartCanceladasSucursales.updateSeries([{
                        data: data.por_sucursal.map(i => Number(i.total))
                    }]);
                    hideSectionLoading("#chart-canceladas-sucursales");
                }).finally(() => {
                    hideLoading();
                }).catch(e => console.error("Error Canceladas:", e));
            }

            function cargarDetalleDia(fecha) {
                diaSeleccionado = fecha;
                showDetalleLoading();
                const suc = selectedSucursal;
                const params = `?from=${fecha}&to=${fecha}&sucursal=${encodeURIComponent(suc)}`;

                // Horarios del día
                fetch(`{{ route('ventas.inout.api.horarios') }}${params}`)
                    .then(res => res.json())
                    .then(data => {
                        const horas = data.frecuencia_hora ?? [];
                        if (chartFrecuenciaHora) {
                            chartFrecuenciaHora.updateOptions({
                                xaxis: {
                                    categories: horas.map(i => i.hora + ":00")
                                },
                                series: [{
                                    name: 'Clientes Únicos',
                                    data: horas.map(i => Number(i.total_clientes))
                                }]
                            });
                            hideSectionLoading('#chart-frecuencia-hora');
                        }
                    });

                // Productos del día
                fetch(`{{ route('ventas.inout.api.top-productos') }}${params}`)
                    .then(res => res.json())
                    .then(data => {
                        const productos = data.productos_top ?? [];
                        tablaTop.clear();
                        productos.forEach((item, index) => {
                            tablaTop.row.add([index + 1, item.product, Number(item.total_vendido)
                                .toLocaleString()
                            ]);
                        });
                        tablaTop.draw();
                        hideSectionLoading('#tabla-top-productos');
                    });
            }

            function cargarProductosPorHora(fecha, hora) {
                showLoading();
                const suc = selectedSucursal;
                fetch(
                        `{{ route('ventas.inout.api.top-productos') }}?from=${fecha}&to=${fecha}&hour=${hora}&sucursal=${encodeURIComponent(suc)}`
                        )
                    .then(res => res.json()).then(data => {
                        tablaTop.clear();
                        data.productos_top.forEach((item, index) => {
                            tablaTop.row.add([index + 1, item.product, Number(item.total_vendido)
                                .toLocaleString()
                            ]);
                        });
                        tablaTop.draw();
                    }).finally(() => hideLoading());
            }

            function cargarProductosPorHoraRango(from, to, hora) {
                showLoading();
                const suc = selectedSucursal;
                fetch(
                        `{{ route('ventas.inout.api.top-productos') }}?from=${from}&to=${to}&hour=${hora}&sucursal=${encodeURIComponent(suc)}`
                        )
                    .then(res => res.json()).then(data => {
                        tablaTop.clear();
                        data.productos_top.forEach((item, index) => {
                            tablaTop.row.add([index + 1, item.product, Number(item.total_vendido)
                                .toLocaleString()
                            ]);
                        });
                        tablaTop.draw();
                    }).finally(() => hideLoading());
            }

            // ===============================
            // 5) CARGAR AL ENTRAR
            // ===============================

            actualizarLabelRango();
            refreshDashboard();

        });
    </script>



</body>

</html>
