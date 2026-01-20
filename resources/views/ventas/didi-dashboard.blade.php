<!DOCTYPE html>
<html lang="es">

<head>
    <title>Gestión DiDi</title>
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
            border-radius: 12px;
            /* Bordes suavizados estilo iOS/SaaS moderno */
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
            color: #333;
            /* El color naranja de tu marca */
            border-color: #ced4da;
            transform: translateY(-2px);
        }

        .btn-back:hover i {
            transform: translateX(-4px);
            /* Pequeño rebote hacia la izquierda */
        }

        .btn-modern-back:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .card-rounded {
            border-radius: 16px;
            overflow: hidden;
        }

        .btn-outline-primary.focus,
        .btn-outline-primary:focus,
        .btn-outline-primary:hover,
        .btn-outline-primary:not(:disabled):not(.disabled).active,
        .btn-outline-primary:not(:disabled):not(.disabled):active {
            color: #fff !important;
            border-color: #e06d2a !important;
            background-color: #e06d2a !important;
            box-shadow: 0 7px 23px -8px #e06d2a !important;
        }

        .btn-outline-primary {
            color: #e06d2a !important;
            border-color: #e06d2a !important;
        }
    </style>
</head>

<body>

    <div class="page-container">

        @section('sucursales')
            {{-- Mantener la lógica de sección si es necesaria --}}
        @endsection

        @include('layouts.sidebar')

        <div class="page-content">
            <div class="main-wrapper">

                {{-- HEADER UNIFICADO --}}
                <div class="card shadow-sm mb-4 border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h4 class="fw-semibold mb-1" style="font-size:18px;">
                                Dashboard de Ventas DiDi
                            </h4>
                            <span class="text-muted small">
                                Análisis de ventas, ganancias y comisiones a partir de los pedidos facturados en DiDi
                                Food.
                            </span>
                        </div>

                        <div class="d-flex align-items-center" style="gap:12px;">
                            <a href="{{ route('ventas.didi') }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                <i class="fa-solid fa-list me-1"></i>
                                Ver órdenes
                            </a>

                            <a href="{{ route('ventas.index') }}" class="btn-back rounded-pill">
                                <i class="fa-solid fa-arrow-left-long"></i>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="row mb-4">
                    <form method="GET" action="{{ route('didi.dashboard') }}" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Fecha inicio</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date', $startDate->toDateString()) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Fecha fin</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ request('end_date', $endDate->toDateString()) }}">
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary w-100" style="background-color:#e06d2a; color:#fff;">
                                Filtrar
                            </button>
                        </div>
                    </form>

                    <div class="col-md-3">
                        <div class="card text-center shadow">
                            <div class="card-body">
                                <h6>Total Ventas</h6>
                                <h4 class="text-primary">$ {{ number_format($totals['billing'], 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-center shadow">
                            <div class="card-body">
                                <h6>Ganancia</h6>
                                <h4 class="text-success">$ {{ number_format($totals['earnings'], 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-center shadow">
                            <div class="card-body">
                                <h6>Comisión DiDi</h6>
                                <h4 class="text-danger">$ {{ number_format($commissionTotal, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-center shadow">
                            <div class="card-body">
                                <h6>Pedidos</h6>
                                <h4>{{ $totals['orders'] }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Ventas del dia</h6>
                            <small class="text-muted">Distribución por hora</small>
                        </div>
                        <div class="card-body">
                            <canvas id="dailySalesChart" height="120"></canvas>
                        </div>
                    </div>


                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Ventas de la Semana</h6>
                            <small class="text-muted">Resumen diario</small>
                        </div>
                        <div class="card-body">
                            <canvas id="weeklySalesChart" height="120"></canvas>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Ventas del Mes</h6>
                            <small class="text-muted">Tendencia mensual</small>
                        </div>
                        <div class="card-body">
                            <canvas id="monthlySalesChart" height="120"></canvas>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>

    {{-- MODAL IMPORTAR --}}

    {{-- SCRIPTS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const rawHours = @json($dailySales->pluck('hour'));
        const ordersByHour = @json($dailySales->pluck('total_orders'));

        const hourLabels = rawHours.map(h => String(h).padStart(2, '0') + ':00');

        new Chart(document.getElementById('dailySalesChart'), {
            type: 'bar',
            data: {
                labels: hourLabels,
                datasets: [{
                    label: 'Órdenes',
                    data: ordersByHour,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>


    <script>
        /* 📈 Ventas de la Semana */
        new Chart(document.getElementById('weeklySalesChart'), {
            type: 'line',
            data: {
                labels: @json($weeklySales->pluck('date')),
                datasets: [{
                    label: 'Ventas',
                    data: @json($weeklySales->pluck('total')),
                    borderWidth: 2,
                    tension: 0.4
                }]
            }
        });

        /* 📊 Ventas del Mes */
        new Chart(document.getElementById('monthlySalesChart'), {
            type: 'line',
            data: {
                labels: @json($monthlySales->pluck('date')),
                datasets: [{
                    label: 'Ventas',
                    data: @json($monthlySales->pluck('total')),
                    borderWidth: 2,
                    tension: 0.4
                }]
            }
        });
    </script>




    {{-- NOTIFICACIONES --}}
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
