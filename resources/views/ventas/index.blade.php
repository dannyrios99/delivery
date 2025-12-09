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

                    {{-- SELECT AÑO --}}
                    <form method="GET">
                        <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </form>
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

                                <div class="mb-2">
                                    <span class="text-muted small">Órdenes</span><br>
                                    <strong>{{ number_format($p['total_ordenes']) }}</strong>
                                </div>

                                <div class="mb-2">
                                    <span class="text-muted small">Total vendido</span><br>
                                    <strong>$ {{ number_format($p['total_vendido'], 0, ',', '.') }}</strong>
                                </div>

                                <div class="mb-3">
                                    <span class="text-muted small">Ticket promedio</span><br>
                                    <strong>$ {{ number_format($p['ticket_promedio'], 0, ',', '.') }}</strong>
                                </div>

                                <div class="mt-auto">
                                    @if($p['ruta'] !== '#')
                                        <a href="{{ $p['ruta'] }}?year={{ $year }}" class="btn btn-orange w-100">
                                            Ver ventas de {{ $p['nombre'] }}
                                        </a>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            Próximamente
                                        </button>
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

</body>
</html>
