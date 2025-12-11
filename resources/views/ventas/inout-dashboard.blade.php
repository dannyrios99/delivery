<!DOCTYPE html>
<html lang="es">

<head>
    <title>Dashboard InOut</title>

    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
            border-top-color: #6c63ff;
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

        .dropdown-item {
            border-radius: 6px;
            padding: 8px 12px;
        }
        .dropdown-item:hover {
            background-color: #f3f4f6;
        }
        .dropdown-menu {
            border: 1px solid #e5e7eb;
        }
        .btn-light {
            background-color: #fff;
        }
        .dropdown-header {
            font-size: 0.78rem;
            color: #6b7280;
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

                <div class="card shadow-sm mb-4">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">

                        {{-- Texto del rango seleccionado --}}
                        <div>
                            <span class="text-muted small d-block">Mostrando</span>
                            <span id="label-rango" class="fw-semibold">
                                {{-- Esto se actualiza por JS --}}
                            </span>
                        </div>

                        {{-- Dropdown de filtros --}}
                        <div class="dropdown">

                            <button class="btn btn-light border d-flex align-items-center gap-2 px-3 py-2"
                                    type="button"
                                    id="btnDropdownRango"
                                    data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside"
                                    aria-expanded="false"
                                    style="border-radius: 999px;">
                                <i class="fa-regular fa-calendar"></i>
                                <span>Filtrar por fecha</span>
                                <i class="fa-solid fa-chevron-down small ms-1"></i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end shadow-sm p-3"
                                aria-labelledby="btnDropdownRango"
                                style="width: 320px; border-radius: 16px;">

                                <h6 class="dropdown-header fw-semibold text-muted small">
                                    Rangos rápidos
                                </h6>

                                <div class="d-grid gap-2 mb-3">
                                    <button type="button" class="btn btn-sm btn-outline-secondary text-start rango-rapido" data-range="today">
                                        Hoy
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary text-start rango-rapido" data-range="last7">
                                        Últimos 7 días
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary text-start rango-rapido" data-range="month">
                                        Este mes
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary text-start rango-rapido" data-range="prevMonth">
                                        Mes anterior
                                    </button>
                                </div>

                                <hr class="dropdown-divider">

                                <h6 class="dropdown-header fw-semibold text-muted small">
                                    Rango personalizado
                                </h6>

                                <div class="px-1">
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="form-label small mb-1">Desde</label>
                                            <input type="date" id="filtro-from" class="form-control form-control-sm"
                                                value="{{ $defaultFrom }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small mb-1">Hasta</label>
                                            <input type="date" id="filtro-to" class="form-control form-control-sm"
                                                value="{{ $defaultTo }}">
                                        </div>
                                    </div>

                                    <button class="btn btn-primary btn-sm w-100" type="button" id="btn-aplicar-rango">
                                        Aplicar
                                    </button>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>



                {{-- ===================== --}}
                {{-- RESUMEN CANAL + SUCURSALES --}}
                {{-- ===================== --}}
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
    <script>
    document.addEventListener('DOMContentLoaded', function () {

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
            document.querySelectorAll('.loading-overlay').forEach(o => o.style.display = 'none');
            const btn = document.getElementById('btn-aplicar-rango');
            btn.disabled = false;
            btn.innerText = "Aplicar";
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
            chart: { type: 'pie', height: 260 },
            labels: [],
            series: [],
            legend: { position: 'bottom' }
        });
        chartCanal.render();

        const chartSucursales = new ApexCharts(document.querySelector("#chart-sucursales"), {
            chart: { type: 'bar', height: 300 },
            plotOptions: { bar: { horizontal: true }},
            xaxis: { categories: [] },
            series: [{ name: 'Órdenes', data: [] }]
        });
        chartSucursales.render();

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

                    chartSucursales.updateOptions({ xaxis: { categories: data.sucursales.map(i => i.sucursal) }});
                    chartSucursales.updateSeries([{ data: data.sucursales.map(i => Number(i.total)) }]);

                    chartFormasPago.updateOptions({ labels: data.formasPago.map(i => i.forma_pago) });
                    chartFormasPago.updateSeries(data.formasPago.map(i => Number(i.total)));

                    chartEntrega.updateOptions({ labels: data.entrega.map(i => i.tipo_entrega) });
                    chartEntrega.updateSeries(data.entrega.map(i => Number(i.total)));

                    chartHistDiario.updateOptions({ xaxis: { categories: data.historico.diario.map(i => i.fecha) }});
                    chartHistDiario.updateSeries([{ data: data.historico.diario.map(i => Number(i.total)) }]);

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

        // ===============================
        // 5) CARGAR AL ENTRAR
        // ===============================

        actualizarLabelRango();
        loadGraficas();

    });
    </script>

</body>
</html>
