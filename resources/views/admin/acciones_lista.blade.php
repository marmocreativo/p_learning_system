@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Historial de acciones</h1>
    <div class="row">
        <div class="col-9">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Home</a></li>
                  <li class="breadcrumb-item">Acciones</li>
                </ol>
            </nav>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col">
            <form action="{{ route('acciones') }}" method="GET" class="row g-3 align-items-end">
            {{-- Campo de texto: Correo usuario --}}
            <div class="col-md-4">
                <label for="correo_usuario" class="form-label">Correo usuario</label>
                <input type="text" class="form-control" name="correo_usuario" id="correo_usuario" value="{{ request('correo_usuario') }}">
            </div>

            {{-- Select: Cuenta --}}
            <div class="col-md-3">
                <label for="id_cuenta" class="form-label">Cuenta</label>
                <select name="id_cuenta" id="id_cuenta" class="form-select">
                    <option value="">Todas</option>
                    @foreach ($cuentas as $cuenta)
                        <option value="{{ $cuenta->id }}" {{ request('id_cuenta') == $cuenta->id ? 'selected' : '' }}>
                            {{ $cuenta->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Select: Temporada --}}
            @if ($temporadas)
                <div class="col-md-3">
                <label for="id_temporada" class="form-label">Temporada</label>
                <select name="id_temporada" id="id_temporada" class="form-select">
                    <option value="">Todas</option>
                    @foreach ($temporadas as $temporada)
                        <option value="{{ $temporada->id }}" {{ request('id_temporada') == $temporada->id ? 'selected' : '' }}>
                            {{ $temporada->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            

            {{-- Botón --}}
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </form>
        </div>
        <div class="row mt-4">
            <div class="col-4">
                @if ($intentos_login)
                    <div class="card card-body">
                    <h5>Intentos de login</h5>
                    <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>correo</th>
                            <th>intento</th>
                            <th>cuenta</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($intentos_login as $intento)
                            <tr>
                                <td>{{ $intento->usuario }}</td>
                                <td>{{ $intento->try }}</td>
                                <td>
                                    @switch($intento->id_cuenta)
                                        @case(1)
                                            PL-Electrico
                                            @break
                                        @case(3)
                                            PL-NI
                                            @break
                                            @case(4)
                                            Etailers
                                            @break
                                            @case(5)
                                            Test
                                            @break
                                        @default
                                            
                                    @endswitch
                                </td>
                                <td>{{ $intento->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No se encontraron resultados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                @endif
                
            </div>
            <div class="col-8">
                <div class="card card-body">
                    <h5>Historiald e acciones</h5>
                    <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Acción</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($acciones as $accion)
                            <tr>
                                <td>{{ $accion->nombre }}</td>
                                <td>{{ $accion->accion }}</td>
                                <td>{{ $accion->descripcion }}</td>
                                <td>{{ $accion->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No se encontraron resultados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>


@endsection