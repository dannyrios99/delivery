<!DOCTYPE html>
<html lang="es">

<head>
    <title>Horarios Rappi</title>
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    </style>
</head>

<body>

    <div class="page-container">

        @section('horarios')
            class="active-page"
        @endsection

        @include('layouts.sidebar')

        <div class="page-content">
            <div class="main-wrapper">

                {{-- TÍTULO PRINCIPAL --}}
                <div class="card shadow-sm mb-4">

                {{-- TÍTULO + VOLVER --}}
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="fw-semibold mb-0" style="font-size:18px;">Horarios Rappi</h4>

                    <a href="{{ route('horarios.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left-long me-1"></i> Volver a Plataformas
                    </a>
                </div>

                {{-- SWITCH DE FILTRO --}}
                <div class="card-body py-3">

                    <form method="GET" class="d-flex justify-content-end">

                        <div class="form-check form-switch d-flex align-items-center">

                            {{-- SWITCH (idéntico al de InOut) --}}
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

                            {{-- TEXTO --}}
                            <label for="switchHorarios" class="form-check-label ms-2 fw-semibold"
                                style="cursor:pointer;">
                                Mostrar solo sucursales con horarios
                            </label>

                        </div>

                    </form>

                </div>
            </div>


                {{-- SUCURSALES AGRUPADAS --}}
                @foreach ($sucursales as $sucursal)
                    <div class="card shadow-sm mb-4 border-0"
                        style="border-radius:14px; border:1px solid rgba(224,109,42,0.15);">

                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3"
                            style="border-radius:14px 14px 0 0;">
                            <h5 class="fw-semibold mb-0" style="font-size:18px;">
                                {{ $sucursal->nombre }}
                            </h5>

                            <button class="btn btn-sm btn-orange" data-bs-toggle="modal"
                                data-bs-target="#modalCrear{{ $sucursal->id }}">
                                <i class="fa-solid fa-plus me-1"></i> Nuevo horario
                            </button>
                        </div>

                        <div class="card-body">

                            @if ($sucursal->horariosRappi->count() == 0)
                                <div class="text-muted py-4 text-center" style="font-size:14px;">
                                    No hay horarios registrados para esta sucursal.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table align-middle" style="border-radius:12px; overflow:hidden;">
                                        <thead class="text-center"
                                            style="background:rgba(224,109,42,0.08); color:#5a5a5a;">
                                            <tr>
                                                <th>Marca</th>
                                                <th>Apertura</th>
                                                <th>Cierre</th>
                                                <th>Lu</th>
                                                <th>Ma</th>
                                                <th>Mi</th>
                                                <th>Ju</th>
                                                <th>Vi</th>
                                                <th>Sa</th>
                                                <th>Do</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>

                                        <tbody class="text-center">

                                            @foreach ($sucursal->horariosRappi as $horario)
                                                <tr style="font-size:15px;">
                                                    <td class="fw-semibold">{{ $horario->marca }}</td>
                                                    <td>{{ $horario->apertura }}</td>
                                                    <td>{{ $horario->cierre }}</td>

                                                    {{-- DÍAS ✔ / — --}}
                                                    <td>{!! $horario->lunes ? '<span style="color:#2ba04b;">✔</span>' : '<span style="color:#bbb;">—</span>' !!}</td>
                                                    <td>{!! $horario->martes ? '<span style="color:#2ba04b;">✔</span>' : '<span style="color:#bbb;">—</span>' !!}</td>
                                                    <td>{!! $horario->miercoles ? '<span style="color:#2ba04b;">✔</span>' : '<span style="color:#bbb;">—</span>' !!}</td>
                                                    <td>{!! $horario->jueves ? '<span style="color:#2ba04b;">✔</span>' : '<span style="color:#bbb;">—</span>' !!}</td>
                                                    <td>{!! $horario->viernes ? '<span style="color:#2ba04b;">✔</span>' : '<span style="color:#bbb;">—</span>' !!}</td>
                                                    <td>{!! $horario->sabado ? '<span style="color:#2ba04b;">✔</span>' : '<span style="color:#bbb;">—</span>' !!}</td>
                                                    <td>{!! $horario->domingo ? '<span style="color:#2ba04b;">✔</span>' : '<span style="color:#bbb;">—</span>' !!}</td>

                                                    <td class="text-center">

                                                        {{-- EDITAR --}}
                                                        <button class="btn btn-sm btn-orange" data-bs-toggle="modal"
                                                            data-bs-target="#modalEditar{{ $horario->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        {{-- ELIMINAR --}}
                                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalEliminar{{ $horario->id }}">
                                                            <i class="fas fa-trash"></i>
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


                    {{-- MODAL CREAR --}}
                    <div class="modal fade" id="modalCrear{{ $sucursal->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">

                                <form method="POST" action="{{ route('rappi.store', $sucursal->id) }}">
                                    @csrf

                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title">Nuevo horario Rappi — {{ $sucursal->nombre }}</h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <label class="fw-semibold mb-1">Marca</label>
                                        <select name="marca" class="form-select mb-3" required>
                                            <option value="">Seleccione...</option>
                                            <option value="Hot">Hot</option>
                                            <option value="Umi">Umi</option>
                                            <option value="Poke">Poke</option>
                                        </select>

                                        <div class="row">
                                            <div class="col mb-3">
                                                <label class="fw-semibold">Apertura</label>
                                                <input type="time" name="apertura" class="form-control" required>
                                            </div>
                                            <div class="col mb-3">
                                                <label class="fw-semibold">Cierre</label>
                                                <input type="time" name="cierre" class="form-control" required>
                                            </div>
                                        </div>

                                        <label class="fw-semibold">Días activos</label>
                                        <div class="d-flex flex-wrap gap-3 my-2">
                                            @php
                                                $dias = [
                                                    'lunes' => 'Lunes',
                                                    'martes' => 'Martes',
                                                    'miercoles' => 'Miércoles',
                                                    'jueves' => 'Jueves',
                                                    'viernes' => 'Viernes',
                                                    'sabado' => 'Sábado',
                                                    'domingo' => 'Domingo',
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

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-orange">Guardar</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                    @foreach ($sucursal->horariosRappi as $horario)
                        {{-- MODAL EDITAR --}}
                        <div class="modal fade" id="modalEditar{{ $horario->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-md">
                                <div class="modal-content">

                                    <form method="POST" action="{{ route('rappi.update', $horario->id) }}">
                                        @csrf

                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title">Editar horario Rappi</h5>
                                            <button class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <label class="fw-semibold mb-1">Marca</label>
                                            <select name="marca" class="form-select mb-3" required>
                                                <option value="Hot"
                                                    {{ $horario->marca == 'Hot' ? 'selected' : '' }}>Hot</option>
                                                <option value="Umi"
                                                    {{ $horario->marca == 'Umi' ? 'selected' : '' }}>Umi</option>
                                                <option value="Poke"
                                                    {{ $horario->marca == 'Poke' ? 'selected' : '' }}>Poke</option>
                                            </select>

                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label class="fw-semibold">Apertura</label>
                                                    <input type="time" name="apertura" class="form-control"
                                                        value="{{ $horario->apertura }}" required>
                                                </div>

                                                <div class="col mb-3">
                                                    <label class="fw-semibold">Cierre</label>
                                                    <input type="time" name="cierre" class="form-control"
                                                        value="{{ $horario->cierre }}" required>
                                                </div>
                                            </div>

                                            <label class="fw-semibold">Días activos</label>
                                            <div class="d-flex flex-wrap gap-3 my-2">
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

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-orange">Actualizar</button>
                                            <button class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>


                        {{-- MODAL ELIMINAR --}}
                        <div class="modal fade" id="modalEliminar{{ $horario->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form method="POST" action="{{ route('rappi.destroy', $horario->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <div class="modal-content">

                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title">Eliminar horario</h5>
                                            <button type="button" class="btn-close"
                                                data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body text-center">
                                            <p>¿Seguro que deseas eliminar este horario?</p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                            <button class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endforeach


                {{-- SWEETALERT --}}
                @if (Session::has('success'))
                    <script>
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: "{{ session('success') }}",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>
                @endif

            </div>
        </div>
    </div>

</body>

</html>
