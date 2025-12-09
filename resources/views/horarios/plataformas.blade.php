<!DOCTYPE html>
<html lang="es">

<head>
    <title>Plataformas de Horarios</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .platform-card {
            border-radius: 12px;
            padding: 25px 20px;
            border: 1px solid #e5e7eb;
            transition: all .25s ease;
            cursor: pointer;
            background: #fff;
        }

        .platform-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            border-color: #e06d2a;
        }

        .platform-icon {
            width: 55px;
            height: 55px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(224, 109, 42, 0.12);
            color: #e06d2a;
            font-size: 26px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="page-container">

    {{-- ACTIVAR ITEM DEL SIDEBAR --}}
    @section('horarios')
        class="active-page"
    @endsection

    @include('layouts.sidebar')

    <div class="page-content">
        <div class="main-wrapper">

            {{-- CARD PRINCIPAL QUE ENVUELVE TODO --}}
            <div class="card shadow-sm border-0" style="border-radius:14px;">
                <div class="card-body">

                    {{-- ENCABEZADO --}}
                    <div class="mb-4">
                        <h3 class="fw-bold mb-1" style="font-size:22px;">Plataformas de Horarios</h3>
                        <p class="text-muted" style="font-size:14px;">
                            Selecciona una plataforma para administrar los horarios.
                        </p>
                    </div>

                    {{-- GRID DE PLATAFORMAS --}}
                    <div class="row g-4">

                        {{-- INOUT --}}
                        <div class="col-md-4">
                            <a href="{{ route('inout.index') }}" class="text-decoration-none text-dark">
                                <div class="platform-card">
                                    <div class="platform-icon">
                                        <i class="fa-solid fa-clock"></i>
                                    </div>
                                    <h5 class="fw-semibold">InOut</h5>
                                    
                                </div>
                            </a>
                        </div>

                        {{-- RAPPI --}}
                        <div class="col-md-4">
                            <a href="{{ route('rappi.index') }}" class="text-decoration-none text-dark">
                                <div class="platform-card">
                                    <div class="platform-icon">
                                        <i class="fa-solid fa-motorcycle"></i>
                                    </div>
                                    <h5 class="fw-semibold">Rappi</h5>
                                    
                                </div>
                            </a>
                        </div>

                        {{-- DIDI --}}
                        <div class="col-md-4">
                            <a href="#" class="text-decoration-none text-dark">
                                <div class="platform-card">
                                    <div class="platform-icon">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                    <h5 class="fw-semibold">Didi</h5>
                                    
                                </div>
                            </a>
                        </div>

                    </div> {{-- row --}}

                </div> {{-- card-body --}}
            </div> {{-- card --}}
        </div>
    </div>

</div>

</body>
</html>
