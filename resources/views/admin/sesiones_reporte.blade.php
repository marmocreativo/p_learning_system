@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Reporte sesiones temporada: <small>{{$temporada->nombre}}</small></h1>
    <div class="row">
        <div class="col-9">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
                  <li class="breadcrumb-item">Temporada</li>
                </ol>
            </nav>
        </div>
        <div class="col-3">
            <form action="{{ route('sesiones.reporte_completadas_excel', ['post' => $temporada->id]) }}" method="GET" class="d-flex">
                @csrf
                <input type="hidden" name="id_temporada" value='{{$temporada->id}}'>
                <input type="hidden" name="region" value="{{request()->get('region')}}">
                <input type="hidden" name="distribuidor" value="{{request()->get('distribuidor')}}">
                <input type="hidden" name="sesiones" value="{{request()->get('sesiones')}}">
                <input type="hidden" name="time" value="{{date('U')}}">
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Descargar excel</button>
                </div>
                

            </form>
        </div>
    </div>
    <hr>
    <div class="row mb-3">
        <div class="col-12">
            <form action="{{ route('sesiones.reporte_completadas', ['post' => $temporada->id]) }}" method="GET" class="d-flex w-100">
                @csrf
                <div class="form-group d-flex me-3">
                    <label for="region" class="pt-2 me-2">Región</label>
                    <select name="region" class="form-control">
                        <option value="todas"  @selected(request()->get('region') === 'todas')>Todas</option>
                        <option value="México"  @selected(request()->get('region') === 'México')>México</option>
                        <option value="RoLA"  @selected(request()->get('region') === 'RoLA')>RoLA</option>
                    </select>
                </div>
                <div class="form-group d-flex me-3">
                    <label for="distribuidor" class="pt-2 me-2">Distribuidor</label>
                    <select name="distribuidor" class="form-control">
                        <option value="0">Todos</option>
                        @foreach ($distribuidores as $disty)
                        <option value="{{$disty->id}}" @selected(request()->get('distribuidor') === $disty->id )>{{$disty->nombre}}</option>
                        @endforeach
                        
                    </select>
                </div>
                <div class="form-group d-flex me-3">
                    <label for="sesiones" class="pt-2 me-2">Mostrar sesiones</label>
                    <select name="sesiones" class="form-control">
                        <option value="todas" @selected(request()->get('sesiones') === 'todas' )>Todas</option>
                        <option value="actuales" @selected(request()->get('sesiones') === 'actuales' )>Actuales</option>
                        <option value="anteriores" @selected(request()->get('sesiones') === 'anteriores' )>Anteriores</option>
                    </select>
                </div>
                <div class="form-group d-flex">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Region</th>
                            <th>Distribuidor</th>
                            @php
                                $numero_sesion_actual = 1;
                                $numero_sesion_pasada = 1;
                                $mostrar_anterior = true;
                                $mostrar_actual = true;
                                if(isset($_GET['sesiones'])){
                                    switch ($_GET['sesiones']) {
                                        case 'todas':
                                            $mostrar_anterior = true;
                                            $mostrar_actual = true;
                                            break;
                                        case 'actuales':
                                            $mostrar_anterior = false;
                                            $mostrar_actual = true;
                                            break;
                                        case 'anteriores':
                                            $mostrar_anterior = true;
                                            $mostrar_actual = false;
                                            break;
                                        
                                        default:
                                            $mostrar_anterior = true;
                                            $mostrar_actual = true;
                                            break;
                                    }
                                }
                            @endphp 
                            @if ($mostrar_anterior)
                                @foreach ($sesiones_anteriores as $sesion)
                                    <th>2023 S{{$numero_sesion_pasada}}</th>
                                    @php $numero_sesion_pasada ++; @endphp 
                                @endforeach
                            @endif
                            @if ($mostrar_actual)
                                @foreach ($sesiones_actuales as $sesion)
                                    <th>2024 S{{$numero_sesion_actual}}</th>
                                    @php $numero_sesion_actual ++; @endphp 
                                @endforeach
                            @endif
                            
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios_suscritos as $usuario)
                            @php
                                $mostrar_fila = false;
                                switch ($_GET['sesiones']) {
                                    case 'todas':
                                            if($usuario->vis_actual || $usuario->vis_anterior){
                                                $mostrar_fila = true;
                                            }
                                        break;
                                    case 'actuales':
                                        if($usuario->vis_actual){
                                            $mostrar_fila = true;
                                        }
                                    break;
                                    case 'anteriores':
                                        if($usuario->vis_anterior){
                                            $mostrar_fila = true;
                                        }
                                    break;
                                    
                                    default:
                                        $mostrar_fila = true;
                                        break;
                                }
                            @endphp

                            @if ($mostrar_fila)
                            @php $puntaje_total = 0; @endphp
                            <tr>
                                <td>{{ $usuario->nombre }} {{ $usuario->apellidos }}
                                </td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->region }}</td>
                                <td>{{ $usuario->distribuidor }}</td>
                                
                                @if ($mostrar_anterior)
                                    @foreach ($sesiones_anteriores as $sesion)
                                        @php
                                            $visualizacion = $visualizaciones_anteriores->first(function ($visualizacion) use ($usuario, $sesion) {
                                                return $visualizacion->id_usuario == $usuario->id_usuario && $visualizacion->id_sesion == $sesion->id;
                                            });
                                        @endphp
                                        @if ($visualizacion)
                                            @php
                                                $anio = \Carbon\Carbon::parse($visualizacion->fecha_ultimo_video)->year;
                                                $anio = $anio < 2023 ? 2023 : ($anio > 2024 ? 2024 : $anio);
                                            @endphp
                                            <td>{{ $anio }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                    @endforeach
                                @endif
                    
                                @if ($mostrar_actual)
                                    @foreach ($sesiones_actuales as $sesion)
                                        @php
                                            $visualizacion = $visualizaciones_actuales->first(function ($visualizacion) use ($usuario, $sesion) {
                                                return $visualizacion->id_usuario == $usuario->id_usuario && $visualizacion->id_sesion == $sesion->id;
                                            });
                                        @endphp
                                        @if ($visualizacion)
                                            @php
                                                $anio = \Carbon\Carbon::parse($visualizacion->fecha_ultimo_video)->year;
                                                $anio = $anio < 2023 ? 2023 : ($anio > 2024 ? 2024 : $anio);
                                            @endphp
                                            <td>{{ $anio }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                    @endforeach
                                @endif
                            </tr>
                            @endif
                    
                            
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    

@endsection