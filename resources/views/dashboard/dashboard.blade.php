<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Delivery</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">
    
    <!-- CSS Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Plugins CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            color: var(--text-main);
            line-height: 1.6;
            margin: 0;
        }

        .main-wrapper {
            padding: 2rem;
            margin: 0 auto;
        }

        /* KPI Cards */
        .kpi-card {
            background: #fff;
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
            overflow: hidden;
            height: 100%;
        }

        .kpi-card:hover {
            transform: translateY(-5px);
        }

        .kpi-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            background-color: var(--primary-orange-light);
            color: var(--primary-orange);
            font-size: 1.5rem;
        }

        .kpi-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0;
        }

        .kpi-label {
            font-size: 0.875rem;
            color: #888;
            margin-bottom: 0;
        }

        /* Charts Section */
        .chart-container {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 0.75rem;
            color: var(--primary-orange);
        }

        /* Table Styling */
        .custom-table {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #eee;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #666;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .badge-status {
            padding: 0.4em 0.75em;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* Buttons */
        .btn-orange {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
            color: #fff;
        }

        .btn-orange:hover {
            background-color: #c95b1e;
            border-color: #c95b1e;
            color: #fff;
        }

        .btn-sm-custom {
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
            border-radius: 6px;
        }

        /* Hero Card Header */
        .header-hero-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 2.5rem;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .avatar-circle {
            width: 48px;
            height: 48px;
            background-color: var(--primary-orange-light);
            color: var(--primary-orange);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.2rem;
            margin-right: 1.25rem;
            box-shadow: inset 0 0 0 2px rgba(224, 109, 42, 0.1);
        }

        .header-title-main {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
            color: #2c3e50;
        }

        .header-subtitle {
            font-size: 0.95rem;
            color: #7f8c8d;
        }

        .btn-premium {
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            border: none;
        }

        .btn-premium-orange {
            background-color: var(--primary-orange);
            color: #fff;
        }

        .btn-premium-orange:hover {
            background-color: #c95b1e;
            transform: translateY(-2px);
            color: #fff;
        }

        .btn-premium-outline {
            background-color: #f8f9fa;
            color: #555;
            border: 1px solid #e0e0e0;
        }

        .btn-premium-outline:hover {
            background-color: #fff;
            border-color: var(--primary-orange);
            color: var(--primary-orange);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .btn-premium i {
            font-size: 1rem;
        }

        .rotate-icon:hover i {
            animation: spin 0.8s ease;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        hr {
            border-top: 1px solid #eee;
            margin: 2rem 0;
        } 
    </style>
</head>

<body>
    <div class="page-container">
        @section('dashboard')
            class="active-page"
        @endsection

        @include('layouts.sidebar')

        <div class="page-content">
            <div class="main-wrapper">
                
                <!-- Welcome & Header -->
                <!-- Welcome & Header Hero Card -->
                <div class="header-hero-card d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="avatar-circle">
                            {{ mb_substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <h2 class="fw-bold header-title-main">Dashboard Operativo</h2>
                            <p class="header-subtitle mb-0">
                                Bienvenido, <span class="fw-semibold text-dark">{{ Auth::user()->name ?? 'Administrador' }}</span> 
                                <span class="mx-2 text-muted opacity-50">|</span> 
                                <span class="badge bg-light text-muted border fw-normal py-1 px-2" style="font-size: 0.75rem;">
                                    <i class="far fa-calendar-alt me-1"></i> {{ date('d M, Y') }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <button class="btn btn-premium btn-premium-outline rotate-icon" onclick="location.reload()">
                            <i class="fas fa-sync-alt me-2"></i> Actualizar
                        </button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-premium btn-premium-outline">
                            <i class="fas fa-users-cog me-2"></i> Gestión Usuarios
                        </a>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="kpi-card p-3 d-flex align-items-center">
                            <div class="kpi-icon">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                            <div>
                                <h4 class="kpi-value" id="kpi-ventas-semana">
                                    <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                </h4>
                                <p class="kpi-label">Ventas de la Semana</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-card p-3 d-flex align-items-center">
                            <div class="kpi-icon">
                                <i class="fas fa-shopping-basket"></i>
                            </div>
                            <div>
                                <h4 class="kpi-value" id="kpi-pedidos-semana">
                                    <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                </h4>
                                <p class="kpi-label">Pedidos de la Semana</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-card p-3 d-flex align-items-center">
                            <div class="kpi-icon" style="background-color: #fff0f0; color: #dc3545;">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div>
                                <h4 class="kpi-value" id="kpi-cancelados-semana">
                                    <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                </h4>
                                <p class="kpi-label">Cancelados (Semana)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-card p-3 d-flex align-items-center">
                            <div class="kpi-icon" style="background-color: #e7f3ff; color: #0d6efd;">
                                <i class="fas fa-motorcycle"></i>
                            </div>
                            <div>
                                <h4 class="kpi-value" id="kpi-reparto-hoy">
                                    <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                </h4>
                                <p class="kpi-label">En Reparto (Hoy)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="kpi-card p-3 d-flex align-items-center">
                            <div class="kpi-icon">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                            <div>
                                <h4 class="kpi-value">{{ $proyectosActivos }}</h4>
                                <p class="kpi-label">Proyectos Activos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="kpi-card p-3 d-flex align-items-center">
                            <div class="kpi-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div>
                                <h4 class="kpi-value">{{ $tareasPendientes }}</h4>
                                <p class="kpi-label">Tareas Pendientes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analysis Section -->
                <div class="row g-4 mb-5">
                    <!-- Weekly Volume Chart -->
                    <div class="col-lg-8">
                        <div class="chart-container h-100">
                            <h5 class="section-title"><i class="fas fa-chart-line"></i> Volumen de Pedidos Semanal</h5>
                            <div style="height: 300px; position: relative;">
                                <div id="spinner-volumen" class="position-absolute top-50 start-50 translate-middle">
                                    <div class="spinner-border text-secondary" role="status"></div>
                                </div>
                                <canvas id="chartVolumenSemanal"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- Top Products -->
                    <div class="col-lg-4">
                        <div class="chart-container h-100">
                            <h5 class="section-title"><i class="fas fa-trophy"></i> Top 5 Productos (Semana)</h5>
                            <div id="top-productos-list">
                                <div class="text-center py-5">
                                    <div class="spinner-border text-secondary" role="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Se eliminó la Tendencia de Ventas (7 Días) y el Mix de Plataformas para optimizar el rendimiento --}}

                <div class="row mt-2">
                    <!-- Recent Movements -->
                    <div class="col-12">
                        <div class="chart-container p-0">
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <h5 class="section-title mb-0"><i class="fas fa-history"></i> Últimos Movimientos Inout</h5>
                                <a href="{{ route('ventas.inout') }}" class="btn btn-link btn-sm text-decoration-none" style="color: var(--primary-orange);">Ver todos</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Plataforma</th>
                                            <th>Estado</th>
                                            <th class="text-end">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla-movimientos">
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status"></div>
                                                <div class="mt-2 text-muted">Cargando movimientos...</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Formateador de moneda
            const formatter = new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0
            });

            // 1. Cargar KPIs asíncronamente
            fetch("{{ route('dashboard.kpi-stats') }}")
                .then(response => response.json())
                .then(data => {
                    document.getElementById('kpi-ventas-semana').innerText = formatter.format(data.totalVentasSemana);
                    document.getElementById('kpi-pedidos-semana').innerText = data.totalPedidosSemana;
                    document.getElementById('kpi-cancelados-semana').innerText = data.canceladosSemana;
                    document.getElementById('kpi-reparto-hoy').innerText = data.repartoHoy;
                })
                .catch(error => {
                    console.error('Error cargando KPIs:', error);
                    ['kpi-ventas-semana', 'kpi-pedidos-semana', 'kpi-cancelados-semana', 'kpi-reparto-hoy'].forEach(id => {
                        document.getElementById(id).innerText = '0';
                    });
                });

            // 2. Cargar Insights (Gráfico y Top Productos)
            fetch("{{ route('dashboard.weekly-insights') }}")
                .then(response => response.json())
                .then(data => {
                    // Ocultar spinner del gráfico
                    const spinner = document.getElementById('spinner-volumen');
                    if (spinner) spinner.style.display = 'none';

                    // Renderizar Gráfico
                    const ctx = document.getElementById('chartVolumenSemanal').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.volumenDiario.map(d => {
                                const date = new Date(d.fecha + 'T00:00:00');
                                return date.toLocaleDateString('es-CO', { weekday: 'short', day: 'numeric' });
                            }),
                            datasets: [{
                                label: 'Pedidos',
                                data: data.volumenDiario.map(d => d.total),
                                backgroundColor: '#e06d2a',
                                borderRadius: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { display: false } },
                                x: { grid: { display: false } }
                            }
                        }
                    });

                    // Renderizar Top Productos
                    const topList = document.getElementById('top-productos-list');
                    topList.innerHTML = '';
                    if (data.topProductos.length === 0) {
                        topList.innerHTML = '<p class="text-center text-muted py-5">Sin datos esta semana</p>';
                    } else {
                        data.topProductos.forEach((prod, index) => {
                            topList.innerHTML += `
                                <div class="d-flex align-items-center mb-3">
                                    <div class="fw-bold me-3 text-muted">${index + 1}.</div>
                                    <div class="flex-grow-1">
                                        <div class="fw-500 text-dark" style="font-size: 0.9rem;">${prod.product}</div>
                                        <div class="text-muted" style="font-size: 0.8rem;">${prod.total_vendido} unidades</div>
                                    </div>
                                    <div class="badge bg-light text-dark border">${Math.round(prod.total_vendido)}</div>
                                </div>
                            `;
                        });
                    }
                })
                .catch(error => console.error('Error cargando insights:', error));

            // 2. Cargar Movimientos asíncronamente
            fetch("{{ route('dashboard.movimientos-inout') }}")
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('tabla-movimientos');
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">No hay movimientos recientes</td></tr>';
                        return;
                    }

                    data.forEach(mov => {
                        let badgeClass = '';
                        switch(mov.stateCurrent) {
                            case 'Entregado': badgeClass = 'bg-success'; break;
                            case 'Reparto': badgeClass = 'bg-info'; break;
                            case 'Cancelado': badgeClass = 'bg-danger'; break;
                            default: badgeClass = 'bg-secondary';
                        }

                        const date = new Date(mov.createdAt);
                        const dateStr = date.toLocaleString('es-CO', { 
                            day: '2-digit', month: '2-digit', year: 'numeric', 
                            hour: '2-digit', minute: '2-digit' 
                        });

                        tbody.innerHTML += `
                            <tr>
                                <td>${dateStr}</td>
                                <td><span class="text-capitalize">${mov.platform}</span></td>
                                <td><span class="badge ${badgeClass} badge-status text-white">${mov.stateCurrent}</span></td>
                                <td class="text-end fw-bold">${formatter.format(mov.total)}</td>
                            </tr>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error cargando movimientos:', error);
                    document.getElementById('tabla-movimientos').innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">Error al cargar movimientos</td></tr>';
                });
        });
    </script>

    <!-- SweetAlert Success/Error -->
    @if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: '¡Listo!', text: "{{ session('success') }}", confirmButtonColor: '#e06d2a' });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error') }}", confirmButtonColor: '#e06d2a' });
    </script>
    @endif

</body>

</html>
