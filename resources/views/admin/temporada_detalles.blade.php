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
            <a href="{{ route('temporadas', ['id_cuenta'=> $temporada->id_cuenta]) }}">Lista de temporadas</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12 mb-3"><h2>Actividades</h2></div>
        <div class="col-3">
            <div class="card card-body">
                <h4>Detalles</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Nombre</b></td>
                        <td>{{$temporada->nombre}}</td>
                    </tr>
                    <tr>
                        <td><b>Descripción</b></td>
                        <td>{{$temporada->descripcion}}</td>
                    </tr>
                    <tr>
                        <td><b>Fecha Inicio</b></td>
                        <td>{{$temporada->fecha_inicio}}</td>
                    </tr>
                    <tr>
                        <td><b>Fecha Final</b></td>
                        <td>{{$temporada->fecha_final}}</td>
                    </tr>
                </table>
                <a href="{{route('temporadas.reporte', ['post'=>$temporada->id, 'region'=>'todas', 'distribuidor'=>'0'])}}" class="btn btn-info">Reporte</a>
                <hr>
            </div>
        </div>
        
        <div class="col-3">
            <div class="card card-body">
                <h4>Sesiones</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>{{$sesiones_totales}}</td>
                    </tr>
                    <tr>
                        <td><b>Publicadas</b></td>
                        <td>{{$sesiones_publicadas}}</td>
                    </tr>
                    <tr>
                        <td><b>Pendientes</b></td>
                        <td>{{$sesiones_pendientes}}</td>
                    </tr>
                </table>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <a class="btn btn-info w-100" href="{{ route('sesiones', ['id_temporada'=> $temporada->id]) }}">Lista sesiones</a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('sesiones.reporte_completadas', ['post' => $temporada->id, 'region' => 'todas', 'distribuidor' => '0', 'sesiones' => 'todas']) }}" 
                            class="btn btn-primary w-100 enlace_pesado">Reporte</a>                         
                        
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a class="btn btn-outline-warning w-100" href="{{ route('sesiones.completadas', ['id_temporada'=> $temporada->id]) }}">Verificar completadas</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card card-body">
                <h4>Trivias</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>{{$trivias_totales}}</td>
                    </tr>
                    <tr>
                        <td><b>Publicadas</b></td>
                        <td>{{$trivias_publicadas}}</td>
                    </tr>
                    <tr>
                        <td><b>Pendientes</b></td>
                        <td>{{$trivias_pendientes}}</td>
                    </tr>
                </table>
                @if($trivia_activa)
                <a class="btn btn-success" href="{{ route('trivias.resultados', $trivia_activa->id) }}">Trivia activa {{$trivia_activa->titulo}}</a>
                @endif
                <hr>

                <a href="{{ route('trivias', ['id_temporada'=> $temporada->id]) }}">Lista de trivias</a>
            </div>
        </div>
        <div class="col-3">
            <div class="card card-body">
                <h4>Jackpot</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>{{$jackpots_totales}}</td>
                    </tr>
                </table>
                @if($jackpot_activo)
                <a class="btn btn-success" href="{{ route('jackpots.resultados', $jackpot_activo->id) }}">Jackpot activo {{$jackpot_activo->titulo}}</a>
                @endif
                <hr>
                <a href="{{ route('jackpots', ['id_temporada'=> $temporada->id]) }}">Lista de jackpots</a>
            </div>
            
        </div>

    </div>
    <hr>
    <div class="row">
        <div class="col-12 mb-3"><h2>Contenido / Participantes</h2></div>
        <div class="col-3">
            <div class="card card-body d-none">
                <h4>Sliders</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>5</td>
                    </tr>
                </table>
                <a class="btn btn-info" href="{{ route('sliders', ['id_temporada'=> $temporada->id]) }}">Lista de sliders</a>
            </div>
            <div class="card card-body">
                <h4>Canje</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <th><b>Productos</b></th>
                        <th><b>Pedidos</b></th>
                    </tr>
                    <tr>
                        <td><b>{{$productos}}</b></td>
                        <td><b>{{$transacciones}}</b></td>
                    </tr>
                    <tr>
                        <td><a class="btn btn-info" href="{{ route('canjeo.productos', ['id_temporada'=> $temporada->id]) }}">Ver productos</a></td>
                        <td><a class="btn btn-info" href="{{ route('canjeo.cortes', ['id_temporada'=> $temporada->id]) }}">Ver pedidos</a></td>
                    </tr>
                </table>
            </div>
            <hr>
            <div class="card card-body">
                <h4>Páginas</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>{{$paginas_totales}}</td>
                    </tr>
                </table>
                <a class="btn btn-info" href="{{ route('publicaciones', ['id_temporada'=> $temporada->id, 'clase'=> 'pagina']) }}">Lista de páginas</a>
            </div>
            <hr>
            <div class="card card-body">
                <h4>Preguntas frecuentes</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>{{$faq_totales}}</td>
                    </tr>
                </table>
                <a class="btn btn-info" href="{{ route('publicaciones', ['id_temporada'=> $temporada->id, 'clase'=> 'faq']) }}">Lista de preguntas</a>
            </div>
            <hr>
            <div class="card card-body">
                <h4>PopUps y Cintillos</h4>
                <a class="btn btn-info" href="{{ route('popups', ['id_temporada'=> $temporada->id]) }}">Lista de popups</a>
            </div>
        </div>
        <div class="col-3">
            <div class="card card-body">
                <h4>Distribuidores</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>{{$distribuidores_suscritos}}</td>
                    </tr>
                </table>
                <a class="btn btn-info" href="{{ route('distribuidores.suscritos', ['id_temporada'=> $temporada->id]) }}">Distribuidores suscritos</a>
            </div>
        </div>
        
        <div class="col-3">
            <div class="card card-body">
                <h4>Usuarios / Líderes</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>{{$usuarios_suscritos}}</td>
                    </tr>
                </table>
                <div class="row">
                    <div class="col-6">
                        <a class="btn btn-info w-100" href="{{ route('admin_usuarios_suscritos', ['id_temporada'=> $temporada->id]) }}">Suscripciones</a>
                    </div>
                    <div class="col-6">
                        <a class="btn btn-success w-100" href="{{ route('admin_usuarios_puntos_extra', ['id_temporada'=> $temporada->id]) }}">Puntos extra</a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a class="btn btn-success w-100" href="{{ route('top_10_region', ['id'=> $temporada->id_cuenta, 'region'=>'México']) }}">Top 10</a>
                    </div>
                </div>
                

            </div>
        </div>
        <div class="col-3">
            <div class="card card-body">
                <h4>Logros (Champions)</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Retos</b></td>
                        <td>{{$logros_totales}}</td>
                    </tr>
                    <tr>
                        <td><b>Participantes</b></td>
                        <td>{{$logros_participantes}}</td>
                    </tr>
                </table>
                <a class="btn btn-info" href="{{ route('logros', ['id_temporada'=> $temporada->id]) }}">Logros</a>
            </div>
        </div>

    </div>
    <div class="p-4">
        <form action="{{ route('registros_pasados.csv') }}" class="d-flex" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="file" name="file" accept=".xlsx">
            </div>
            <button type="submit" class="btn btn-primary">Sesiones pasadas</button>
        </form>
    </div>
    


@endsection