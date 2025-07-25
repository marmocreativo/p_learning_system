@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Detalles temporada <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <a class="btn btn-outline-primary" href="{{ route('temporadas', ['id_cuenta'=> $temporada->id_cuenta]) }}">Lista de temporadas</a>
    </div>

    <nav aria-label="breadcrumb mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item dropdown">
                <a class="dropdown-toggle text-decoration-none" href="#" id="breadcrumbDropdown" role="button"  data-mdb-dropdown-init
                        data-mdb-ripple-init>
                    Cuentas
                </a>
                <ul class="dropdown-menu" aria-labelledby="breadcrumbDropdown">
                    @foreach($cuentas as $cuentaItem)
                        <li>
                            <a class="dropdown-item" href="{{ route('temporadas', ['id_cuenta' => $cuentaItem->id]) }}">
                                {{ $cuentaItem->nombre }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
            <li class="breadcrumb-item">{{$temporada->nombre}}</li>
        </ol>
    </nav>
    <hr>
    <div class="row">
        <div class="col-3">
            <div class="card card-body">
                <h4>Resumen</h4>
                <table class="table table-bordered table-sm">
                    <tr><td><b>Nombre</b></td><td>{{ $temporada->nombre }}</td></tr>
                    <tr><td><b>Descripción</b></td><td>{{ $temporada->descripcion }}</td></tr>
                    <tr><td><b>Fecha Inicio</b></td><td>{{ $temporada->fecha_inicio }}</td></tr>
                    <tr><td><b>Fecha Final</b></td><td>{{ $temporada->fecha_final }}</td></tr>
                    <tr>
                        <td><b>Estado</b></td>
                        <td>
                            <span class="badge text-uppercase {{ $temporada->estado === 'ACTIVA' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $temporada->estado }}
                            </span>
                        </td>
                    </tr>
                </table>
                <a href="{{ route('temporadas.reporte', ['post' => $temporada->id, 'region' => 'todas', 'distribuidor' => '0']) }}" class="btn btn-info w-100 mb-2">Reporte</a>
                <a href="{{ route('acciones') }}" class="btn btn-info w-100">Acciones</a>
            </div>
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-body">
                    <!-- Tabs navs -->
                   <ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a
                            data-mdb-tab-init
                            class="nav-link active"
                            id="ex1-tab-1"
                            href="#actividades-tab"
                            role="tab"
                            aria-controls="actividades-tab"
                            aria-selected="true"
                            >Actividades</a
                            >
                        </li>
                        <li class="nav-item" role="presentation">
                            <a
                            data-mdb-tab-init
                            class="nav-link"
                            id="ex1-tab-2"
                            href="#desafios-tab"
                            role="tab"
                            aria-controls="desafios-tab"
                            aria-selected="false"
                            >Desafíos y Canje</a
                            >
                        </li>
                        <li class="nav-item" role="presentation">
                            <a
                            data-mdb-tab-init
                            class="nav-link"
                            id="ex1-tab-3"
                            href="#participantes-tab"
                            role="tab"
                            aria-controls="participantes-tab"
                            aria-selected="false"
                            >Participantes</a
                            >
                        </li>
                        <li class="nav-item" role="presentation">
                            <a
                            data-mdb-tab-init
                            class="nav-link"
                            id="ex1-tab-3"
                            href="#publicaciones-tab"
                            role="tab"
                            aria-controls="publicaciones-tab"
                            aria-selected="false"
                            >Publicaciones / Noticias</a
                            >
                        </li>
                        </ul>
                    <!-- Tabs navs -->

                    <!-- Tabs content -->
                    <div class="tab-content" id="ex1-content">
                        <div
                            class="tab-pane fade show active"
                            id="actividades-tab"
                            role="tabpanel"
                            aria-labelledby="actividades-tab"
                        >
                            <div class="row">
                                <div class="col-4">
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
                                    </div>
                                </div>
                                <div class="col-4">
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
                                <div class="col-4">
                                    <div class="card card-body">
                                        <h4>Minijuegos</h4>
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
                                        <a href="{{ route('jackpots', ['id_temporada'=> $temporada->id]) }}">Lista de minijuegos</a>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="desafios-tab" role="tabpanel" aria-labelledby="desafios-tab">
                            <div class="row">
                                <div class="col-6">
                                    <div class="card card-body">
                                        <h4>Desafíos (Champions)</h4>
                                        <table class="table table-bordered table-sm">
                                            <tr>
                                                <td><b>Desafíos</b></td>
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
                                <div class="col-6">
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
                                                <td>
                                                    <a class="btn btn-info" href="{{ route('canjeo.productos', ['id_temporada'=> $temporada->id, 'region'=>'RoLA']) }}">Ver productos RoLA</a>
                                                    <a class="btn btn-info" href="{{ route('canjeo.productos', ['id_temporada'=> $temporada->id, 'region'=>'México']) }}">Ver productos México</a>
                                                </td>
                                                <td><a class="btn btn-info" href="{{ route('canjeo.cortes', ['id_temporada'=> $temporada->id]) }}">Ver pedidos</a></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="participantes-tab" role="tabpanel" aria-labelledby="participantes-tab">
                            <div class="row">
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
                                <div class="col-9">
                                    <div class="card card-body">
                                        <h4>Usuarios / Líderes</h4>
                                        <div class="row">
                                            <div class="col-4">
                                                <table class="table table-bordered table-sm">
                                                    <tr>
                                                        <td><b>Total</b></td>
                                                        <td>{{$usuarios_suscritos}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-8">
                                                <a class="btn btn-info w-100 mb-3" href="{{ route('admin_usuarios_suscritos', ['id_temporada'=> $temporada->id]) }}">Suscripciones</a>
                                                <a class="btn btn-success w-100 mb-3" href="{{ route('admin_usuarios_puntos_extra', ['id_temporada'=> $temporada->id]) }}">Puntos extra</a>
                                                <a class="btn btn-success w-100 mb-3" href="{{ route('top_10_region', ['id'=> $temporada->id_cuenta, 'region'=>'México']) }}">Top 10</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="publicaciones-tab" role="tabpanel" aria-labelledby="publicaciones-tab">
                            <div class="row">
                                <div class="col-3">
                                    <div class="card card-body">
                                        <h4>Sliders</h4>
                                        <table class="table table-bordered table-sm">
                                            <tr>
                                                <td><b>Total</b></td>
                                                <td>5</td>
                                            </tr>
                                        </table>
                                        <a class="btn btn-info" href="{{ route('sliders', ['id_temporada'=> $temporada->id]) }}">Lista de sliders</a>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="card card-body">
                                        <h4>PopUps y Cintillos</h4>
                                        <a class="btn btn-info" href="{{ route('popups', ['id_temporada'=> $temporada->id]) }}">Lista de popups</a>
                                    </div>
                                </div>
                                <div class="col-3">
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
                                </div>
                                <div class="col-3">
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
                                </div>
                                <div class="col-3">
                                    <div class="card card-body">
                                        <h4>Noticias</h4>
                                        <table class="table table-bordered table-sm">
                                            <tr>
                                                <td><b>Total</b></td>
                                                <td>{{$noticias_totales}}</td>
                                            </tr>
                                        </table>
                                        <a class="btn btn-info" href="{{ route('publicaciones', ['id_temporada'=> $temporada->id, 'clase'=> 'noticia']) }}">Lista de noticias</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    <!-- Tabs content -->
                </div>
            </div>
        </div>
    </div>
    

@endsection