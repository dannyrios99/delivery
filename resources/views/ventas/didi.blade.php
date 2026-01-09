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

    @section('sucursales')
        {{-- Mantener la lógica de sección si es necesaria --}}
    @endsection

    @include('layouts.sidebar')

    <div class="page-content">
        <div class="main-wrapper">

            {{-- HEADER UNIFICADO --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h4 class="fw-semibold mb-0" style="font-size:18px;">Gestión DiDi</h4>
                        <span class="text-muted small">Control y carga de órdenes desde el reporte de facturación de DiDi.</span>
                    </div>

                    <div class="d-flex" style="gap:12px;">
                        {{-- BOTÓN IMPORTAR --}}
                        <button type="button" class="btn btn-orange btn-sm d-flex align-items-center" 
                                style="gap:6px; border-radius: 8px;"
                                data-bs-toggle="modal" data-bs-target="#importarDidiModal">
                            <i class="fa-solid fa-upload"></i> Importar Datos
                        </button>

                        {{-- BOTÓN PLANTILLA --}}
                        <a href="{{ route('didi.template') }}" 
                           class="btn btn-outline-secondary btn-sm d-flex align-items-center" 
                           style="gap:6px; border-radius: 8px;">
                            <i class="fa-solid fa-download"></i> Plantilla
                        </a>

                        {{-- BOTÓN VOLVER --}}
                        <a href="{{ route('ventas.index') }}" class="btn-back">
                            <i class="fa-solid fa-arrow-left-long"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- TABLA --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h6 class="mb-3 fw-bold">Listado de órdenes DiDi Food</h6>

                    <div class="table-responsive">
                        <table id="didiTable" class="table table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Order ID</th>
                                    <th>Restaurante</th>
                                    <th>Método Pago</th>
                                    <th>Total</th>
                                    <th>Comisión</th>
                                    <th>Ganancia Viaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($order->billing_time)->format('d/m/Y H:i') }}</td>
                                        <td class="fw-bold">{{ $order->order_id }}</td>
                                        <td>{{ $order->store_name }}</td>
                                        <td>{{ $order->payment_method }}</td>
                                        <td class="text-end">$ {{ number_format($order->billing_amount, 2, ',', '.') }}</td>
                                        <td class="text-end text-danger">$ {{ number_format($order->commission_fee, 2, ',', '.') }}</td>
                                        <td class="text-end fw-bold text-success">$ {{ number_format($order->trip_earnings, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL IMPORTAR --}}
<div class="modal fade" id="importarDidiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-semibold" style="font-size:16px;">Importar Archivo DiDi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('didi.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Seleccionar archivo Excel (.xlsx)</label>
                        <input type="file" name="file" class="form-control" required>
                        <div class="form-text mt-2">Sube el reporte oficial de DiDi Billing Report.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-orange px-4">
                        <i class="fas fa-upload me-1"></i> Importar Datos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#didiTable').DataTable({
            "order": [[0, "desc"]],
            "pageLength": 25,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            }
        });
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