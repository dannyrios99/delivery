<!DOCTYPE html>
<html lang="es">

<head>
    <title>Gráficas — Ventas InOut</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    {{-- CSS / ICONOS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .card-soft {
            border-radius: 14px;
            border: 1px solid rgba(224, 109, 42, 0.08);
        }

        .card-section-title {
            font-size: 15px;
            font-weight: 600;
            color: #2477ff;
            margin-bottom: 8px;
        }

        .section-subtitle {
            font-size: 12px;
            color: #777;
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
                        <h4 class="fw-semibold mb-0" style="font-size:18px;">Gráficas — Ventas InOut</h4>
                        <span class="text-muted small">Análisis visual basado en órdenes finalizadas.</span>
                    </div>

                    <a href="{{ route('ventas.inout') }}"
                       class="btn btn-outline-secondary btn-circle"
                       title="Volver">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                </div>
            </div>

            {{-- ======================================
                 DISTRIBUCIÓN DE ÓRDENES
               ====================================== --}}
            <div class="card card-soft shadow-sm mb-4">
                <div class="card-body">

                    <div class="card-section-title">Distribución de órdenes</div>
                    <div class="section-subtitle mb-2">Por punto de venta (tipo C93, AVL, etc.)</div>

                    <div style="height: 320px;">
                        <canvas id="chartDistribucionPuntos"></canvas>
                    </div>

                </div>
            </div>

            {{-- ======================================
                 FORMA DE PAGO + ENTREGA
               ====================================== --}}
            <div class="row mb-4">

                {{-- FORMA DE PAGO --}}
                <div class="col-lg-6">
                    <div class="card card-soft shadow-sm h-100">
                        <div class="card-body">
                            <div class="card-section-title">Forma de pago</div>
                            <div style="height: 300px;">
                                <canvas id="chartFormasPago"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ENTREGA --}}
                <div class="col-lg-6">
                    <div class="card card-soft shadow-sm h-100">
                        <div class="card-body">
                            <div class="card-section-title">Entrega</div>
                            <div style="height: 300px;">
                                <canvas id="chartEntrega"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ======================================
                 HISTÓRICO DE ÓRDENES
               ====================================== --}}
            <div class="card card-soft shadow-sm mb-4">
                <div class="card-body">
                    <div class="card-section-title mb-3">Histórico de órdenes</div>

                    {{-- DIARIAS --}}
                    <h6>Órdenes diarias</h6>
                    <div style="height: 260px;" class="mb-4">
                        <canvas id="chartDiarias"></canvas>
                    </div>

                    {{-- SEMANALES --}}
                    <h6>Órdenes semanales</h6>
                    <div style="height: 260px;" class="mb-4">
                        <canvas id="chartSemanales"></canvas>
                    </div>

                    {{-- MENSUALES --}}
                    <h6>Órdenes mensuales</h6>
                    <div style="height: 260px;">
                        <canvas id="chartMensuales"></canvas>
                    </div>

                </div>
            </div>

            {{-- ======================================
                 ÓRDENES CANCELADAS
               ====================================== --}}
            <div class="card card-soft shadow-sm mb-5" style="border-color:#ff4da6;">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="card-section-title" style="color:#ff1493;">Órdenes canceladas</div>
                            <span class="section-subtitle">Distribución por punto de venta</span>
                        </div>

                        <div class="fw-bold" style="font-size:22px; color:#ff1493;">
                            {{ number_format($canceladasTotal) }}
                        </div>
                    </div>

                    <div style="height: 260px;">
                        <canvas id="chartCanceladas"></canvas>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

{{-- ======================
     SCRIPTS
   ====================== --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Paleta de colores exacta estilo dashboard
    const palette = [
        '#007bff', '#00c2c7', '#c2185b', '#f39c12', '#00b35a',
        '#9b59b6', '#ff5722', '#34495e', '#e91e63', '#8bc34a'
    ];

    // ==============================
    // DISTRIBUCIÓN DE ÓRDENES
    // ==============================
    new Chart(document.getElementById('chartDistribucionPuntos'), {
        type: 'bar',
        data: {
            labels: @json($distribucionPuntos->pluck('pointSaleCode')),
            datasets: [{
                data: @json($distribucionPuntos->pluck('total')),
                backgroundColor: palette,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // ==============================
    // FORMAS DE PAGO
    // ==============================
    new Chart(document.getElementById('chartFormasPago'), {
        type: 'pie',
        data: {
            labels: @json($formasPago->pluck('paymentMethod')),
            datasets: [{
                data: @json($formasPago->pluck('total')),
                backgroundColor: palette
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // ==============================
    // ENTREGA
    // ==============================
    new Chart(document.getElementById('chartEntrega'), {
        type: 'pie',
        data: {
            labels: @json($entrega->pluck('type')),
            datasets: [{
                data: @json($entrega->pluck('total')),
                backgroundColor: palette
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // ==============================
    // HISTÓRICO DIARIO
    // ==============================
    new Chart(document.getElementById('chartDiarias'), {
        type: 'line',
        data: {
            labels: @json($ordenesDiarias->pluck('fecha')),
            datasets: [{
                label: 'Órdenes',
                data: @json($ordenesDiarias->pluck('total')),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.08)',
                tension: .3,
                borderWidth: 2,
                pointRadius: 2
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // ==============================
    // HISTÓRICO SEMANAL
    // ==============================
    new Chart(document.getElementById('chartSemanales'), {
        type: 'line',
        data: {
            labels: @json($ordenesSemanales->pluck('semana')),
            datasets: [{
                label: 'Órdenes',
                data: @json($ordenesSemanales->pluck('total')),
                borderColor: '#00c2c7',
                backgroundColor: 'rgba(0,194,199,0.08)',
                tension: .3,
                borderWidth: 2,
                pointRadius: 2
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // ==============================
    // HISTÓRICO MENSUAL
    // ==============================
    new Chart(document.getElementById('chartMensuales'), {
        type: 'line',
        data: {
            labels: @json($ordenesMensuales->pluck('mes')),
            datasets: [{
                label: 'Órdenes',
                data: @json($ordenesMensuales->pluck('total')),
                borderColor: '#c2185b',
                backgroundColor: 'rgba(194,24,91,0.08)',
                tension: .3,
                borderWidth: 2,
                pointRadius: 2
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // ==============================
    // CANCELADAS
    // ==============================
    new Chart(document.getElementById('chartCanceladas'), {
        type: 'bar',
        data: {
            labels: @json($canceladasPorPunto->pluck('pointSaleCode')),
            datasets: [{
                data: @json($canceladasPorPunto->pluck('total')),
                backgroundColor: palette
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>

</body>
</html>