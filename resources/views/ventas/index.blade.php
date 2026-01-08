<!DOCTYPE html>
<html lang="es">

<head>
    <title>Ventas por Plataforma</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn-orange {
            background: rgba(224, 109, 42, 0.12);
            color: #e06d2a;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-orange:hover {
            background: rgba(224, 109, 42, 0.18);
            color: #e06d2a;
        }

        .card:hover {
            transform: scale(1.01);
            transition: 0.2s;
        }

        .year-menu {
            position: absolute;
            top: 40px;
            left: 0;
            background: #fff;
            border-radius: 10px;
            padding: 8px 0;
            width: 130px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            display: none;
            z-index: 50;
        }

        .year-menu button {
            width: 100%;
            background: transparent;
            border: none;
            padding: 8px 14px;
            text-align: left;
            font-size: 14px;
        }

        .year-menu button:hover {
            background: #f5f5f5;
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

                {{-- TÍTULO --}}
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="fw-semibold mb-0" style="font-size:18px;">Ventas por Plataforma</h4>
                    </div>
                </div>

                {{-- CARDS DE PLATAFORMAS --}}
                <div class="row">
                    @foreach ($plataformas as $p)
                        <div class="col-md-4 mb-4">

                            <div class="card shadow-sm border-0" style="border-radius:12px;">
                                <div class="card-body d-flex flex-column">

                                    <h5 class="fw-semibold mb-1">{{ $p['nombre'] }}</h5>
                                    <p class="text-muted small mb-3">{{ $p['descripcion'] }}</p>

                                    {{-- FILTRO AÑO --}}
                                    <div class="year-dropdown mb-3" style="position: relative; display: inline-block;">

                                        <button type="button" class="btn btn-sm btn-light shadow-sm"
                                                onclick="toggleYearMenu('{{ $p['slug'] }}')"
                                                style="border-radius: 10px; padding: 6px 14px;">
                                            Año: {{ $p['year'] == 'todos' ? 'Todos' : $p['year'] }}
                                            <i class="fa-solid fa-chevron-down ms-1"></i>
                                        </button>

                                        <div id="year-menu-{{ $p['slug'] }}" class="year-menu">

                                            {{-- TODOS --}}
                                            <button type="button"
                                                    onclick="loadMetrics('{{ $p['slug'] }}', 'todos')">
                                                Todos
                                            </button>


                                            {{-- Últimos 4 años --}}
                                            @foreach ([date('Y'), date('Y') - 1, date('Y') - 2, date('Y') - 3] as $y)
                                                <button type="button"
                                                        onclick="loadMetrics('{{ $p['slug'] }}', '{{ $y }}')">
                                                    {{ $y }}
                                                </button>
                                            @endforeach

                                        </div>
                                    </div>

                                    {{-- VALORES AJAX --}}
                                    <div class="mb-2">
                                        <span class="text-muted small">Órdenes</span><br>
                                        <strong id="ordenes-{{ $p['slug'] }}">Cargando...</strong>
                                    </div>

                                    <div class="mb-2">
                                        <span class="text-muted small">Total vendido</span><br>
                                        <strong id="vendido-{{ $p['slug'] }}">Cargando...</strong>
                                    </div>

                                    <div class="mb-3">
                                        <span class="text-muted small">Ticket promedio</span><br>
                                        <strong id="ticket-{{ $p['slug'] }}">Cargando...</strong>
                                    </div>

                                    <div class="mt-auto">
                                        @if ($p['ruta'] !== '#')
                                            <a href="{{ $p['ruta'] }}" class="btn btn-orange w-100">
                                                Ver ventas de {{ $p['nombre'] }}
                                            </a>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>Próximamente</button>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>

    <script>
        function toggleYearMenu(slug) {
            let menu = document.getElementById('year-menu-' + slug);

            // Cerrar todos antes de abrir
            document.querySelectorAll('.year-menu').forEach(m => {
                if (m !== menu) m.style.display = 'none';
            });

            // Toggle
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }

        // Cerrar si se hace clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.year-dropdown')) {
                document.querySelectorAll('.year-menu').forEach(m => m.style.display = 'none');
            }
        });
    </script>

    <script>
    function loadMetrics(platform, year) {

        // Cambiar texto del botón
        const btn = document.querySelector(
            `button[onclick="toggleYearMenu('${platform}')"]`
        );

        if (btn) {
            btn.innerHTML = `Año: ${year === 'todos' ? 'Todos' : year}
                <i class="fa-solid fa-chevron-down ms-1"></i>`;
        }

        // Cerrar menú
        document.getElementById('year-menu-' + platform).style.display = 'none';

        // Mostrar loading
        document.getElementById(`ordenes-${platform}`).innerText = 'Cargando...';
        document.getElementById(`vendido-${platform}`).innerText = 'Cargando...';
        document.getElementById(`ticket-${platform}`).innerText = 'Cargando...';

        fetch(`{{ route('ventas.metricas') }}?platform=${platform}&year=${year}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById(`ordenes-${platform}`).innerText =
                    new Intl.NumberFormat().format(data.total_ordenes ?? 0);

                document.getElementById(`vendido-${platform}`).innerText =
                    "$ " + new Intl.NumberFormat('es-CO').format(data.total_vendido ?? 0);

                document.getElementById(`ticket-${platform}`).innerText =
                    "$ " + new Intl.NumberFormat('es-CO').format(Math.round(data.ticket_promedio ?? 0));
            })
            .catch(() => {
                document.getElementById(`ordenes-${platform}`).innerText = "–";
                document.getElementById(`vendido-${platform}`).innerText = "–";
                document.getElementById(`ticket-${platform}`).innerText = "–";
            });
    }

    document.addEventListener("DOMContentLoaded", function () {
        loadMetrics('inout', 'todos');
        loadMetrics('rappi', 'todos');
    });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {

        @foreach ($plataformas as $p)
            @if (in_array($p['slug'], ['inout', 'rappi', 'didi']))
                fetch("{{ route('ventas.metricas') }}?platform={{ $p['slug'] }}&year={{ $p['year'] }}")
                    .then(res => res.json())
                    .then(data => {

                        document.getElementById("ordenes-{{ $p['slug'] }}").innerText =
                            new Intl.NumberFormat().format(data.total_ordenes ?? 0);

                        document.getElementById("vendido-{{ $p['slug'] }}").innerText =
                            "$ " + new Intl.NumberFormat('es-CO').format(data.total_vendido ?? 0);

                        document.getElementById("ticket-{{ $p['slug'] }}").innerText =
                            "$ " + new Intl.NumberFormat('es-CO').format(Math.round(data.ticket_promedio ?? 0));
                    })
                    .catch(() => {
                        document.getElementById("ordenes-{{ $p['slug'] }}").innerText = "–";
                        document.getElementById("vendido-{{ $p['slug'] }}").innerText = "–";
                        document.getElementById("ticket-{{ $p['slug'] }}").innerText = "–";
                    });
            @endif
        @endforeach

    });
    </script>

</body>

</html>
