<!DOCTYPE html>
<html lang="es">

<head>
    <title>Horarios InOut</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="page-container">

        @section('horarios')
            class="active-page"
        @endsection

        @include('layouts.sidebar')

        <div class="page-content">
            <div class="main-wrapper">

                {{-- CARD PRINCIPAL DE ENCABEZADO Y SWITCH --}}
                <div class="card shadow-sm mb-4">

                    {{-- TÍTULO + VOLVER --}}
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Horarios InOut</h4>

                        <a href="{{ route('horarios.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left-long me-1"></i> Volver a Plataformas
                        </a>
                    </div>

                    {{-- SWITCH DE FILTRO --}}
                    <div class="card-body py-3">

                        <form method="GET" class="d-flex justify-content-end">

                            <div class="form-check form-switch d-flex align-items-center">

                                <input class="form-check-input" type="checkbox" name="with" id="switchHorarios"
                                    value="1" onchange="this.form.submit()"
                                    {{ request()->has('with') ? 'checked' : '' }}
                                    style="
                                    cursor:pointer;
                                    width:48px;
                                    height:24px;
                                    background-color: {{ request()->has('with') ? '#e06d2a' : '#ccc' }} !important;
                                    border-color: {{ request()->has('with') ? '#e06d2a' : '#ccc' }} !important;
                                ">

                                <label for="switchHorarios" class="form-check-label ms-2 fw-semibold"
                                    style="cursor:pointer;">
                                    Mostrar solo sucursales con horarios
                                </label>

                            </div>

                        </form>

                    </div>
                </div>


                {{-- LISTA AGRUPADA DE SUCURSALES --}}
                @foreach ($sucursales as $sucursal)
                    <div class="card shadow-sm mb-4">

                        {{-- ENCABEZADO DE SUCURSAL --}}
                        <div
                            class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">{{ $sucursal->nombre }}</h5>

                            <button class="btn btn-sm" style="background:#e06d2a; color:white; border-radius:8px;"
                                data-bs-toggle="modal" data-bs-target="#modalCrear{{ $sucursal->id }}">
                                <i class="fa-solid fa-plus me-1"></i> Nuevo horario
                            </button>
                        </div>

                        {{-- CONTENIDO --}}
                        <div class="card-body">

                            {{-- SI NO HAY HORARIOS --}}
                            @if ($sucursal->horariosInout->count() === 0)
                                <p class="text-muted">No hay horarios registrados para esta sucursal.</p>

                                {{-- SI HAY HORARIOS --}}
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered align-middle">

                                        <thead class="table-light text-center">
                                            <tr>
                                                <th>Mapa</th>
                                                <th>Apertura</th>
                                                <th>Cierre</th>
                                                <th>Lu</th>
                                                <th>Ma</th>
                                                <th>Mi</th>
                                                <th>Ju</th>
                                                <th>Vi</th>
                                                <th>Sa</th>
                                                <th>Do</th>
                                                <th>Fest</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($sucursal->horariosInout as $horario)
                                                <tr>
                                                    <td class="fw-semibold">{{ $horario->mapa }}</td>
                                                    <td>{{ $horario->apertura }}</td>
                                                    <td>{{ $horario->cierre }}</td>

                                                    <td class="text-center">{!! $horario->lunes ? '✔️' : '—' !!}</td>
                                                    <td class="text-center">{!! $horario->martes ? '✔️' : '—' !!}</td>
                                                    <td class="text-center">{!! $horario->miercoles ? '✔️' : '—' !!}</td>
                                                    <td class="text-center">{!! $horario->jueves ? '✔️' : '—' !!}</td>
                                                    <td class="text-center">{!! $horario->viernes ? '✔️' : '—' !!}</td>
                                                    <td class="text-center">{!! $horario->sabado ? '✔️' : '—' !!}</td>
                                                    <td class="text-center">{!! $horario->domingo ? '✔️' : '—' !!}</td>
                                                    <td class="text-center">{!! $horario->festivo ? '✔️' : '—' !!}</td>

                                                    <td class="text-center">
                                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#modalEditar{{ $horario->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach

            </div>



            {{-- MODALES DE CREACIÓN POR SUCURSAL --}}
            @foreach ($sucursales as $sucursal)
                <div class="modal fade" id="modalCrear{{ $sucursal->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content">

                            <form method="POST" action="{{ route('horarios.inout.store') }}">
                                @csrf

                                <input type="hidden" name="sucursal_id" value="{{ $sucursal->id }}">

                                <div class="modal-header bg-light border-bottom">
                                    <h5 class="modal-title fw-semibold">
                                        Nuevo horario — {{ $sucursal->nombre }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body px-4">

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Mapa</label>
                                        <input type="text" name="mapa" class="form-control"
                                            placeholder="Ej: Llevar, 7K, 8K..." required>
                                    </div>

                                    <div class="row">
                                        <div class="col mb-3">
                                            <label class="form-label fw-semibold">Apertura</label>
                                            <input type="time" name="apertura" class="form-control" required>
                                        </div>

                                        <div class="col mb-3">
                                            <label class="form-label fw-semibold">Cierre</label>
                                            <input type="time" name="cierre" class="form-control" required>
                                        </div>
                                    </div>

                                    <label class="form-label fw-semibold">Días activos</label>
                                    <div class="d-flex flex-wrap gap-3">

                                        @php
                                            $dias = [
                                                'lunes' => 'Lunes',
                                                'martes' => 'Martes',
                                                'miercoles' => 'Miércoles',
                                                'jueves' => 'Jueves',
                                                'viernes' => 'Viernes',
                                                'sabado' => 'Sábado',
                                                'domingo' => 'Domingo',
                                                'festivo' => 'Festivo',
                                            ];
                                        @endphp

                                        @foreach ($dias as $campo => $label)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="{{ $campo }}">
                                                <label class="form-check-label">{{ $label }}</label>
                                            </div>
                                        @endforeach

                                    </div>

                                </div>

                                <div class="modal-footer border-top px-4">
                                    <button type="submit" class="btn btn-primary">Crear</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            @endforeach


            {{-- MODALES DE EDICIÓN --}}
            @foreach ($sucursales as $sucursal)
                @foreach ($sucursal->horariosInout as $horario)
                    <div class="modal fade" id="modalEditar{{ $horario->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">

                                <form method="POST" action="{{ route('horarios.inout.update', $horario->id) }}">
                                    @csrf
                                    @method('POST')

                                    <div class="modal-header bg-light border-bottom">
                                        <h5 class="modal-title fw-semibold">Editar horario — {{ $sucursal->nombre }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body px-4">

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Mapa</label>
                                            <input type="text" name="mapa" value="{{ $horario->mapa }}"
                                                class="form-control" required>
                                        </div>

                                        <div class="row">
                                            <div class="col mb-3">
                                                <label class="form-label fw-semibold">Apertura</label>
                                                <input type="time" name="apertura"
                                                    value="{{ $horario->apertura }}" class="form-control" required>
                                            </div>

                                            <div class="col mb-3">
                                                <label class="form-label fw-semibold">Cierre</label>
                                                <input type="time" name="cierre" value="{{ $horario->cierre }}"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                        <label class="form-label fw-semibold">Días activos</label>
                                        <div class="d-flex flex-wrap gap-3">
                                            @foreach ($dias as $campo => $label)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="{{ $campo }}"
                                                        {{ $horario->$campo ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ $label }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="modal-footer border-top px-4">
                                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach


        </div>
    </div>

</body>

</html>
