@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles de la temporada <small>{{$temporada->nombre}}</small></h1>
    <hr>
    <a href="{{ route('temporadas', ['id_cuenta'=> $temporada->id_cuenta]) }}">Lista de temporadas</a>
    <hr>
    <div class="row">
        <div class="col-12 mb-3"><h2>Actividades</h2></div>
        <div class="col-3 d-none">
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
            </div>
        </div>
        
        <div class="col-4">
            <div class="card card-body">
                <h4>Sesiones</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td><b>Publicadas</b></td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td><b>Pendientes</b></td>
                        <td>5</td>
                    </tr>
                </table>
                <a href="{{ route('sesiones', ['id_temporada'=> $temporada->id]) }}">Lista de sesiones</a>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-body">
                <h4>Trivias</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td><b>Publicadas</b></td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td><b>Pendientes</b></td>
                        <td>5</td>
                    </tr>
                </table>
                <a href="{{ route('trivias', ['id_temporada'=> $temporada->id]) }}">Lista de trivias</a>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-body">
                <h4>Jackpot</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td><b>Publicadas</b></td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td><b>Pendientes</b></td>
                        <td>5</td>
                    </tr>
                </table>
                <a href="{{ route('jackpots', ['id_temporada'=> $temporada->id]) }}">Lista de jackpots</a>
            </div>
            
        </div>

    </div>
    <hr>
    <div class="row">
        <div class="col-12 mb-3"><h2>Contenido</h2></div>
        <div class="col-3">
            <div class="card card-body">
                <h4>Sliders</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>5</td>
                    </tr>
                </table>
                <a href="{{ route('sliders', ['id_temporada'=> $temporada->id]) }}">Lista de sliders</a>
            </div>
        </div>
        <div class="col-3">
            <div class="card card-body">
                <h4>Páginas</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>5</td>
                    </tr>
                </table>
                <a href="{{ route('publicaciones', ['id_temporada'=> $temporada->id, 'clase'=> 'pagina']) }}">Lista de páginas</a>
            </div>
        </div>
        <div class="col-3">
            <div class="card card-body">
                <h4>Preguntas frecuentes</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Total</b></td>
                        <td>5</td>
                    </tr>
                </table>
                <a href="{{ route('publicaciones', ['id_temporada'=> $temporada->id, 'clase'=> 'faq']) }}">Lista de preguntas</a>
            </div>
        </div>
        
        
        
        <div class="col-3">
            <div class="card card-body">
                <h4>Notificaciones</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Nombre</b></td>
                        <td>{{$temporada->nombre}}</td>
                    </tr>
                </table>
                <a href="{{ route('notificaciones', ['id_temporada'=> $temporada->id]) }}">Lista de notificaciones</a>
            </div>
        </div>

    </div>
    
    <hr>
    <div class="row">
        <div class="col-12 mb-3"><h2>Participantes</h2></div>
        <div class="col-4">
            <div class="card card-body">
                <h4>Distribuidores</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Nombre</b></td>
                        <td>{{$temporada->nombre}}</td>
                    </tr>
                </table>
                <a href="{{ route('distribuidores.suscritos', ['id_temporada'=> $temporada->id]) }}">Distribuidores suscritos</a>
            </div>
        </div>
        
        <div class="col-4">
            <div class="card card-body">
                <h4>Usuarios / Líderes</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Nombre</b></td>
                        <td>{{$temporada->nombre}}</td>
                    </tr>
                </table>
                <a href="{{ route('admin_usuarios_suscritos', ['id_temporada'=> $temporada->id]) }}">Usuarios suscritos</a>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-body">
                <h4>Logros (Champions)</h4>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td><b>Nombre</b></td>
                        <td>{{$temporada->nombre}}</td>
                    </tr>
                </table>
                <a href="{{ route('logros', ['id_temporada'=> $temporada->id]) }}">Logros</a>
            </div>
        </div>

    </div>
    
    

@endsection