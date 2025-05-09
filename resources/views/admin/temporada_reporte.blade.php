@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles de la temporada <small>{{$temporada->nombre}}</small></h1>
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
            <form action="{{ route('temporadas.reporte_excel', ['post' => $temporada->id]) }}" method="GET" class="d-flex">
                @csrf
                <input type="hidden" name="region" value="{{request()->get('region')}}">
                <input type="hidden" name="distribuidor" value="{{request()->get('distribuidor')}}">
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
            <form action="{{ route('temporadas.reporte', ['post' => $temporada->id]) }}" method="GET" class="d-flex">
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
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Region</th>
                            <th>Distribuidor</th>
                            <th>Sucursal</th>
                            @php
                                $numero_sesion = 1;
                                $numero_trivia = 1;
                                $numero_jackpot = 1;
                            @endphp 
                            @foreach ($sesiones as $sesion)
                                <th>S{{$numero_sesion}} Vis</th>
                                <th>S{{$numero_sesion}} Ev</th>
                                @php $numero_sesion ++; @endphp 
                            @endforeach
                            @foreach ($trivias as $trivia)
                                <th>T{{$numero_trivia}}</th>
                                <th>T{{$numero_trivia}} G</th>
                                @php $numero_trivia ++; @endphp 
                            @endforeach
                            @foreach ($jackpots as $jackpot)
                                <th>J{{$numero_jackpot}}</th>
                                @php $numero_jackpot ++; @endphp 
                            @endforeach
                            <th>Puntos Extra</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios_suscritos as $usuario)
                        @php $puntaje_total = 0; @endphp
                        <tr>
                            <td>{{$usuario->id_usuario}}</td>
                            <td>{{$usuario->nombre}} {{$usuario->apellidos}}</td>
                            <td>{{$usuario->email}}</td>
                            <td>{{$usuario->region}}</td>
                            <td>{{$usuario->distribuidor}}</td>
                            <td>{{$usuario->sucursal}}</td>
                            @foreach ($sesiones as $sesion)
                                @php
                                    $visualizacion = $visualizaciones->first(function ($visualizacion) use ($usuario, $sesion) {
                                        return $visualizacion->id_usuario == $usuario->id_usuario && $visualizacion->id_sesion == $sesion->id;
                                    });
                                    $evaluacion = $respuestas->filter(function ($respuesta) use ($usuario, $sesion) {
                                        return $respuesta->id_usuario == $usuario->id_usuario && $respuesta->id_sesion == $sesion->id;
                                    });
                                    $puntaje_evaluacion = 0;
                                    foreach($evaluacion as $res){
                                        $puntaje_evaluacion += $res->puntaje;
                                    }
                                @endphp
                                @if ($visualizacion)
                                    @php $puntaje_total +=$visualizacion->puntaje+$puntaje_evaluacion; @endphp
                                    <td>{{$visualizacion->puntaje}}</td>
                                    <td>{{$puntaje_evaluacion}}</td>
                                @else
                                    <td>0</td>
                                    <td>0</td>
                                @endif
                            @endforeach
                            @foreach ($trivias as $trivia)
                                @php
                                    $t_respuestas = $trivias_respuestas->filter(function ($respuesta) use ($usuario, $trivia) {
                                        return $respuesta->id_usuario == $usuario->id_usuario && $respuesta->id_trivia == $trivia->id;
                                    });
                                    $ganador = $trivias_ganadores->first(function ($ganador) use ($usuario, $trivia) {
                                        return $ganador->id_usuario == $usuario->id_usuario && $ganador->id_trivia == $trivia->id;
                                    });
                                    $puntaje_trivias = 0;
                                    foreach($t_respuestas as $res){
                                        $puntaje_trivias += $res->puntaje;
                                    }
                                @endphp
                                @if ($t_respuestas)
                                    @php $puntaje_total +=$puntaje_trivias; @endphp
                                    <td>{{$puntaje_trivias}}</td>
                                    @if ($ganador)
                                        <td>Si</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                    
                                @else
                                    <td>0</td>
                                    <td>-</td>
                                @endif
                                
                            @endforeach
                            @foreach ($jackpots as $jackpot)
                                @php
                                    $intentos = $jackpots_intentos->filter(function ($intento) use ($usuario, $jackpot) {
                                        return $intento->id_usuario == $usuario->id_usuario && $intento->id_jackpot == $jackpot->id;
                                    });
                                    $puntaje_jackpot = 0;
                                    foreach($intentos as $int){
                                        $puntaje_jackpot += $int->puntaje;
                                    }
                                @endphp
                                @if ($intentos)
                                    @php $puntaje_total +=$puntaje_jackpot; @endphp
                                    <td>{{$puntaje_jackpot}}</td>
                                @else
                                    <td>0</td>
                                @endif
                                
                            @endforeach
                            @php
                                $total_puntos_extra = 0;
                                $puntos_usuario = $puntos_extra->filter(function ($entrada) use ($usuario) {
                                    return $entrada->id_usuario == $usuario->id_usuario;
                                });
                                $total_puntos_extra = $puntos_usuario->sum('puntos');
                            @endphp
                            <td>{{$total_puntos_extra}}</td>
                            <td>{{$puntaje_total}}</td>
                        </tr>
                        @endforeach
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    

@endsection