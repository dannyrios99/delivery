<!DOCTYPE html>
<html lang="es">

<head>
    <title>Dashboard InOut</title>

    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">

    <style>
        .card {
            border-radius: 12px;
        }
        .fw-semibold {
            font-weight: 600;
        }

        .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 12px;
            z-index: 10;
            display: none;
        }

        .spinner {
            width: 38px;
            height: 38px;
            border: 4px solid #ddd;
            border-top-color: #e06d2a;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .chart-wrapper {
            position: relative;
            min-height: 260px;
        }

/* =====================================================
   CARD HEADER MODERNO
===================================================== */

.card-header-modern {
    border-radius: 18px;
    background: linear-gradient(145deg, #ffffff, #f9fafb);
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
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
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
    box-shadow: 0 18px 35px rgba(0,0,0,0.08);
    overflow: hidden;
    padding: 0;
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
.quick-item + .quick-item {
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
    box-shadow: 0 8px 20px rgba(249,115,22,0.25);
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

            <!-- DERECHA -->
            <div class="d-flex align-items-center gap-2 flex-wrap">

                <!-- ==============================
                     DROPDOWN FILTRO
                ================================= -->
                <div class="dropdown">

                    <button class="btn btn-filter"
                            type="button"
                            id="btnDropdownRango"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false">
                        <i class="fa-regular fa-calendar"></i>
                        <span>Filtrar por fecha</span>
                        <i class="fa-solid fa-chevron-down small"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end dropdown-modern"
                         aria-labelledby="btnDropdownRango">

                        <!-- =========================
                             RANGOS RÁPIDOS
                        ========================== -->
                        <div class="dropdown-section">

                            <div class="dropdown-section-header">
                                Rangos rápidos
                            </div>

                            <div class="quick-ranges">

                                <button type="button"
                                        class="quick-item rango-rapido"
                                        data-range="today">
                                    Hoy
                                </button>

                                <button type="button"
                                        class="quick-item rango-rapido"
                                        data-range="last7">
                                    Últimos 7 días
                                </button>

                                <button type="button"
                                        class="quick-item rango-rapido"
                                        data-range="month">
                                    Este mes
                                </button>

                                <button type="button"
                                        class="quick-item rango-rapido"
                                        data-range="prevMonth">
                                    Mes anterior
                                </button>

                            </div>

                        </div>

                        <!-- Línea divisoria fuerte -->
                        <div class="dropdown-divider-strong"></div>

                        <!-- =========================
                             RANGO PERSONALIZADO
                        ========================== -->
                        <div class="dropdown-section">

                            <div class="dropdown-section-header">
                                Rango personalizado
                            </div>

                            <div class="range-custom px-3 pb-3">

                                <div class="row g-2 mb-3">

                                    <div class="col-6">
                                        <label class="form-label small mb-1">Desde</label>
                                        <input type="date"
                                               id="filtro-from"
                                               class="form-control form-control-modern"
                                               value="{{ $defaultFrom }}">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label small mb-1">Hasta</label>
                                        <input type="date"
                                               id="filtro-to"
                                               class="form-control form-control-modern"
                                               value="{{ $defaultTo }}">
                                    </div>

                                </div>

                                <button class="btn-apply"
                                        type="button"
                                        id="btn-aplicar-rango">
                                    Aplicar rango
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ==============================
                     BOTÓN VER GRÁFICAS
                ================================= -->
                <a href="{{ route('ventas.inout') }}"
                   class="btn btn-graph">
                    <i class="fa-solid fa-receipt"></i>
                    Detalle órdenes
                </a>

            </div>

        </div>

    </div>
</div>



                {{-- ===================== --}}
                {{-- RESUMEN CANAL + SUCURSALES --}}
                {{-- ===================== --}}
                
                <div class="row mb-4">
                
                    <!-- Venta total -->
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Venta total</h6>
                                <h4 id="kpi-venta-total">$0</h4>
                            </div>
                        </div>
                    </div>
                
                    <!-- Call Center -->
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Call Center</h6>
                                <h4 id="kpi-call">$0</h4>
                            </div>
                        </div>
                    </div>
                
                    <!-- Web -->
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Web</h6>
                                <h4 id="kpi-web">$0</h4>
                            </div>
                        </div>
                    </div>
                
                </div>
                
        <div class="row mb-4 align-items-stretch">
        
            <!-- Frecuencia diaria -->
            <div class="col-md-6 d-flex">
                <div class="card shadow-sm w-100 h-100">
                    <div class="card-body d-flex flex-column">
        
                        <h6 class="fw-semibold mb-3">
                            Frecuencia de clientes únicos
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
            <div class="col-md-6 d-flex">
                <div class="card shadow-sm w-100 h-100">
                    <div class="card-body d-flex flex-column">
        
                        <h6 class="fw-semibold mb-3">
                            Clientes únicos por hora
                            <small class="text-muted">(¿A qué hora regresan?)</small>
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
        
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            Top productos del periodo
                        </h6>
            
                        <div class="table-responsive">
                            <table id="tabla-top-productos" class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Producto</th>
                                        <th class="text-end">Unidades vendidas</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
            
                    </div>
                </div>
            </div>
                <div class="row mb-4">

                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Distribución por canal</h6>
                                <div class="chart-wrapper">
                                    <div class="loading-overlay"><div class="spinner"></div></div>
                                    <div id="chart-canal"></div>
                                </div>    
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Órdenes por sucursal</h6>
                                <div class="chart-wrapper">
                                    <div class="loading-overlay"><div class="spinner"></div></div>
                                    <div id="chart-sucursales"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ===================== --}}
                {{-- FORMA DE PAGO + ENTREGA --}}
                {{-- ===================== --}}
                <div class="row mb-4">

                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Forma de pago</h6>
                                <div class="chart-wrapper">
                                    <div class="loading-overlay"><div class="spinner"></div></div>
                                    <div id="chart-formas-pago"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Entrega</h6>
                                <div class="chart-wrapper">
                                    <div class="loading-overlay"><div class="spinner"></div></div>
                                    <div id="chart-entrega"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ===================== --}}
                {{-- HISTÓRICOS --}}
                {{-- ===================== --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        <h6 class="fw-semibold mb-3">Histórico de órdenes</h6>

                        <div class="mb-4">
                            <h6 class="small text-muted mb-2">Diarias</h6>
                            <div class="chart-wrapper">
                                <div class="loading-overlay"><div class="spinner"></div></div>
                                <div id="chart-hist-diario"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="small text-muted mb-2">Semanales</h6>
                            <div class="chart-wrapper">
                                <div class="loading-overlay"><div class="spinner"></div></div>
                                <div id="chart-hist-semanal"></div>
                            </div>
                        </div>

                        <div>
                            <h6 class="small text-muted mb-2">Mensuales</h6>
                            <div class="chart-wrapper">
                                <div class="loading-overlay"><div class="spinner"></div></div>
                                <div id="chart-hist-mensual"></div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ===================== --}}
                {{-- CANCELADAS --}}
                {{-- ===================== --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        <h6 class="fw-semibold mb-3">Órdenes canceladas</h6>

                        <div class="row">
                            <div class="col-md-4">
                                <p>
                                    <strong id="canceladas-total-ordenes">0</strong> órdenes canceladas
                                </p>
                                <p>
                                    Total cancelado:
                                    <strong id="canceladas-total-valor">$ 0</strong>
                                </p>
                            </div>

                            <div class="col-md-8">
                                <div class="chart-wrapper">
                                    <div class="loading-overlay"><div class="spinner"></div></div>
                                    <div id="chart-canceladas-sucursales"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
    let diaSeleccionado = null;
    
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
            const year  = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day   = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function showLoading() {
            document.querySelectorAll('.loading-overlay').forEach(o => o.style.display = 'flex');
            const btn = document.getElementById('btn-aplicar-rango');
            btn.disabled = true;
            btn.innerText = "Cargando...";
        }

        function hideLoading() {
            document.querySelectorAll('.loading-overlay')
                .forEach(o => o.style.display = 'none');
        
            const btn = document.getElementById('btn-aplicar-rango');
            btn.disabled = false;
            btn.innerText = "Aplicar";
        
            cerrarDropdown(); // 🔥 AQUÍ se cierra cuando termina todo
        }

        function actualizarLabelRango() {
            const from = document.getElementById('filtro-from').value;
            const to   = document.getElementById('filtro-to').value;
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
                    to   = formatDate(today);
                    break;

                case "month":
                    const first = new Date(today.getFullYear(), today.getMonth(), 1);
                    from = formatDate(first);
                    to   = formatDate(today);
                    break;

                case "prevMonth":
                    const firstPrev = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    const lastPrev  = new Date(today.getFullYear(), today.getMonth(), 0);
                    from = formatDate(firstPrev);
                    to   = formatDate(lastPrev);
                    break;
            }

            document.getElementById('filtro-from').value = from;
            document.getElementById('filtro-to').value   = to;

            actualizarLabelRango();
        }

        // ===============================
        // 2) LISTENERS DE RANGOS
        // ===============================

        document.querySelectorAll('.rango-rapido').forEach(btn => {
            btn.addEventListener('click', function () {
                setRange(this.dataset.range);
                loadGraficas();
            });
        });

        document.getElementById('btn-aplicar-rango').addEventListener('click', function () {
            actualizarLabelRango();
            loadGraficas();
        });

        // ===============================
        // 3) INICIALIZACIÓN DE GRÁFICAS
        // ===============================

        const chartCanal = new ApexCharts(document.querySelector("#chart-canal"), {
            chart: { type: 'pie', height: 315 },
            labels: [],
            series: [],
            legend: { position: 'bottom' }
        });
        chartCanal.render();

        const chartSucursales = new ApexCharts(document.querySelector("#chart-sucursales"), {
            chart: { type: 'bar', height: 300 },
        
            plotOptions: { 
                bar: { horizontal: true } 
            },
        
            xaxis: { categories: [] },
        
            series: [{
                name: 'Órdenes',
                data: []
            }],
        
            tooltip: {
                custom: function({ series, seriesIndex, dataPointIndex, w }) {
        
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
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    events: {
                        markerClick: function(event, chartContext, config) {
        
                            if (config.dataPointIndex === -1) return;
        
                            const fechaSeleccionada =
                                chartContext.w.globals.categoryLabels[config.dataPointIndex];
        
                            cargarDetalleDia(fechaSeleccionada);
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
                    size: 0,
                    hover: {
                        size: 6
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
                    toolbar: { show: false },
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
                                const to   = document.getElementById('filtro-to').value;
        
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
        order: [[2, 'desc']],
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
            chart: { type: 'pie', height: 260 },
            labels: [],
            series: [],
            legend: { position: 'bottom' }
        });
        chartFormasPago.render();

        const chartEntrega = new ApexCharts(document.querySelector("#chart-entrega"), {
            chart: { type: 'pie', height: 260 },
            labels: [],
            series: [],
            legend: { position: 'bottom' }
        });
        chartEntrega.render();

        const chartHistDiario = new ApexCharts(document.querySelector("#chart-hist-diario"), {
            chart: { type: 'line', height: 230 },
            series: [{ name: 'Órdenes', data: [] }],
            xaxis: { categories: [] }
        });
        chartHistDiario.render();

        const chartHistSemanal = new ApexCharts(document.querySelector("#chart-hist-semanal"), {
            chart: { type: 'line', height: 230 },
            series: [{ name: 'Órdenes', data: [] }],
            xaxis: { categories: [] }
        });
        chartHistSemanal.render();

        const chartHistMensual = new ApexCharts(document.querySelector("#chart-hist-mensual"), {
            chart: { type: 'line', height: 230 },
            series: [{ name: 'Órdenes', data: [] }],
            xaxis: { categories: [] }
        });
        chartHistMensual.render();

        const chartCanceladasSucursales = new ApexCharts(document.querySelector("#chart-canceladas-sucursales"), {
            chart: { type: 'bar', height: 260 },
            plotOptions: { bar: { horizontal: true }},
            xaxis: { categories: [] },
            series: [{ name: 'Canceladas', data: [] }]
        });
        chartCanceladasSucursales.render();
        

        // ===============================
        // 4) FUNCIÓN QUE TRAE LOS DATOS
        // ===============================

        function loadGraficas() {
            showLoading();

            const from = document.getElementById('filtro-from').value;
            const to   = document.getElementById('filtro-to').value;

            fetch(`{{ route('ventas.inout.graficas') }}?from=${from}&to=${to}`)
                .then(res => res.json())
                .then(data => {

                    chartCanal.updateOptions({ labels: data.canal.map(i => i.canal) });
                    chartCanal.updateSeries(data.canal.map(i => Number(i.total)));

                    chartSucursales.updateOptions({
                        xaxis: { 
                            categories: data.sucursales.map(i => i.sucursal) 
                        },
                    
                        extraData: data.sucursales.map(i => Number(i.total_venta))
                    });
                    
                    chartSucursales.updateSeries([{
                        data: data.sucursales.map(i => Number(i.total))
                    }]);

                    chartFormasPago.updateOptions({ labels: data.formasPago.map(i => i.forma_pago) });
                    chartFormasPago.updateSeries(data.formasPago.map(i => Number(i.total)));

                    chartEntrega.updateOptions({ labels: data.entrega.map(i => i.tipo_entrega) });
                    chartEntrega.updateSeries(data.entrega.map(i => Number(i.total)));

                    chartHistDiario.updateOptions({ xaxis: { categories: data.historico.diario.map(i => i.fecha) }});
                    chartHistDiario.updateSeries([{ data: data.historico.diario.map(i => Number(i.total)) }]);
                    
                    const freq = data?.historico?.frecuencia_clientes ?? [];

                    chartFrecuenciaClientes.updateOptions({
                        xaxis: { categories: freq.map(i => i.fecha) }
                    });
                    
                    chartFrecuenciaClientes.updateSeries([{
                        data: freq.map(i => Number(i.total_clientes))
                    }]);
                    
                    if (chartFrecuenciaHora) {

                    const horas = data?.historico?.frecuencia_hora ?? [];
                
                    chartFrecuenciaHora.updateOptions({
                        xaxis: { categories: horas.map(i => i.hora + ":00") }
                    });
                
                    chartFrecuenciaHora.updateSeries([{
                        data: horas.map(i => Number(i.total_clientes))
                    }]);
                }
                
                    const productos = data?.historico?.productos_top ?? [];

                    // Limpiar tabla
                    tablaTop.clear();
                    
                    // Agregar nuevas filas
                    productos.forEach((item, index) => {
                        tablaTop.row.add([
                            index + 1,
                            item.product,
                            Number(item.total_vendido).toLocaleString()
                        ]);
                    });
                    
                    // ==============================
                    // KPIs
                    // ==============================
                    
                    const kpis = data?.kpis ?? {};
                    
                    const total = kpis?.venta_total?.total ?? 0;
                    
                    document.getElementById('kpi-venta-total').innerText =
                        new Intl.NumberFormat('es-CO', {
                            style: 'currency',
                            currency: 'COP'
                        }).format(total);
                    
                    // 🔥 separar canales
                    const canales = kpis?.ventas_canal ?? [];
                    
                    let call = 0;
                    let web = 0;
                    
                    canales.forEach(c => {
                        const nombre = (c.platform || '').toLowerCase();
                    
                        if (nombre.includes('call')) {
                            call = Number(c.total);
                        }
                    
                        if (nombre.includes('web')) {
                            web = Number(c.total);
                        }
                    });
                    
                    // pintar
                    document.getElementById('kpi-call').innerText =
                        new Intl.NumberFormat('es-CO', {
                            style: 'currency',
                            currency: 'COP'
                        }).format(call);
                    
                    document.getElementById('kpi-web').innerText =
                        new Intl.NumberFormat('es-CO', {
                            style: 'currency',
                            currency: 'COP'
                        }).format(web);
                    
                    // Redibujar
                    tablaTop.draw();
                    chartHistSemanal.updateOptions({ xaxis: { categories: data.historico.semanal.map(i => i.semana) }});
                    chartHistSemanal.updateSeries([{ data: data.historico.semanal.map(i => Number(i.total)) }]);

                    const meses = ["","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"];
                    chartHistMensual.updateOptions({
                        xaxis: { categories: data.historico.mensual.map(i => meses[i.mes]) }
                    });
                    chartHistMensual.updateSeries([{ data: data.historico.mensual.map(i => Number(i.total)) }]);

                    document.getElementById('canceladas-total-ordenes').innerText =
                        data.canceladas.resumen.total_ordenes ?? 0;

                    document.getElementById('canceladas-total-valor').innerText =
                        new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP' })
                        .format(data.canceladas.resumen.total_valor ?? 0);

                    chartCanceladasSucursales.updateOptions({
                        xaxis: { categories: data.canceladas.por_sucursal.map(i => i.sucursal) }
                    });
                    chartCanceladasSucursales.updateSeries([{ data: data.canceladas.por_sucursal.map(i => Number(i.total)) }]);

                    hideLoading();
                })
                .catch(err => {
                    console.error(err);
                    hideLoading();
                });
        }
        
        function cargarDetalleDia(fecha) {
            
            diaSeleccionado = fecha;

            showLoading();
        
            fetch(`{{ route('ventas.inout.graficas') }}?from=${fecha}&to=${fecha}`)
                .then(res => res.json())
                .then(data => {
        
                    // Actualizar gráfica por hora
                    const horas = data?.historico?.frecuencia_hora ?? [];
        
                    chartFrecuenciaHora.updateOptions({
                        xaxis: { categories: horas.map(i => i.hora + ":00") }
                    });
        
                    chartFrecuenciaHora.updateSeries([{
                        data: horas.map(i => Number(i.total_clientes))
                    }]);
        
                    // Actualizar tabla productos
                    const productos = data?.historico?.productos_top ?? [];
        
                    tablaTop.clear();
        
                    productos.forEach((item, index) => {
                        tablaTop.row.add([
                            index + 1,
                            item.product,
                            Number(item.total_vendido).toLocaleString()
                        ]);
                    });
        
                    tablaTop.draw();
        
                    hideLoading();
                })
                .catch(err => {
                    console.error(err);
                    hideLoading();
                });
        }
        
        
            function cargarProductosPorHora(fecha, hora) {
                console.log("Fetching:", fecha, hora);
    
        showLoading();
    
        fetch(`{{ route('ventas.inout.graficas') }}?from=${fecha}&to=${fecha}&hour=${hora}`)
            .then(res => res.json())
            .then(data => {
    
                const productos = data?.historico?.productos_top ?? [];
    
                tablaTop.clear();
    
                productos.forEach((item, index) => {
                    tablaTop.row.add([
                        index + 1,
                        item.product,
                        Number(item.total_vendido).toLocaleString()
                    ]);
                });
    
                tablaTop.draw();
    
                hideLoading();
            })
            .catch(err => {
                console.error(err);
                hideLoading();
            });
}

function cargarProductosPorHoraRango(from, to, hora) {

    showLoading();

    fetch(`{{ route('ventas.inout.graficas') }}?from=${from}&to=${to}&hour=${hora}`)
        .then(res => res.json())
        .then(data => {

            const productos = data?.historico?.productos_top ?? [];

            tablaTop.clear();

            productos.forEach((item, index) => {
                tablaTop.row.add([
                    index + 1,
                    item.product,
                    Number(item.total_vendido).toLocaleString()
                ]);
            });

            tablaTop.draw();

            hideLoading();
        })
        .catch(err => {
            console.error(err);
            hideLoading();
        });
}

        // ===============================
        // 5) CARGAR AL ENTRAR
        // ===============================

        actualizarLabelRango();
        loadGraficas();

    });
    </script>



</body>
</html>
