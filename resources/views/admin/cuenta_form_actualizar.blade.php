@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 m-0">Actualizar la cuenta <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <a href="{{ route('cuentas') }}" class="btn btn-warning">
             Salir
        </a>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cuentas</li>
        </ol>
    </nav>
    <form action="{{ route('cuentas.update',$cuenta->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    <div class="row">
        <div class="col-9">
            <h5>Diseño del Home</h5>
            <div class="row">
                <div class="col-8">
                    <div class="card card-body">
                        <h5>Slider</h5>
                        <div class="form-group">
                            <label for="Badge">Medallón</label>
                            <input type="text" class="form-control" name="Badge" value="{{$cuenta->badge}}">
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="Titulo">Texto Principal (blanco)</label>
                                    <input type="text" class="form-control" name="Titulo" value="{{$cuenta->titulo}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="TituloResaltado">Texto Principal (color de realce)</label>
                                    <input type="text" class="form-control" name="TituloResaltado" value="{{$cuenta->titulo_resaltado}}">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="BotonTexto">Botón principal(texto)</label>
                                    <input type="text" class="form-control" name="BotonTexto" value="{{$cuenta->boton_texto}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="BotonEnlace">Botón principal (enlace)</label>
                                    <input type="text" class="form-control" name="BotonEnlace" value="{{$cuenta->boton_enlace}}">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$cuenta->fondo) }}" alt="Imagen">
                        <div class="form-group">
                            <label for="Fondo">Fondo</label>
                            <input type="file" class="form-control" name="Fondo" >
                        </div>
                        
                        
                    </div>
                </div>
                <div class="col-4">
                    <div class="card card-body">
                        <h5>Video</h5>
                        <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$cuenta->imagen_video) }}" alt="Imagen">
                        <div class="form-group">
                            <label for="ImagenVideo">Imagen del video</label>
                            <input type="file" class="form-control" name="ImagenVideo" >
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="LinkVideo">URL Video</label>
                            <input type="text" class="form-control" name="LinkVideo" value="{{$cuenta->link_video}}">
                        </div>
                        
                    </div> 
                </div>
                
            </div>
        </div>
        <div class="col-2">
            <div class="card">
                <div class="card-header">
                    <h5>Configuración de la cuenta</h5>
                </div>
                <div class="card-body">
                    <div class="p-4" style="background-color: #ddd;">
                        <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$cuenta->logotipo) }}" alt="Logotipo">
                    </div>
                    <div class="form-group">
                        <label for="Logotipo">Logotipo</label>
                        <input type="file" class="form-control" name="Logotipo" >
                    </div>
                    <div class="form-group">
                        <label for="ColorRealse">Color de realse</label>
                        <input type="text" class="form-control" name="ColorRealse" value="{{$cuenta->color_realse}}">
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="FondoMenu">Fondo del menú</label>
                                <input type="text" class="form-control" name="FondoMenu" value="{{$cuenta->fondo_menu}}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="TextoMenu">Texto del menú</label>
                                <input type="text" class="form-control" name="TextoMenu" value="{{$cuenta->texto_menu}}">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="Nombre">Nombre de la cuenta</label>
                        <input type="text" class="form-control" name="Nombre" value="{{$cuenta->nombre}}">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="TemporadaActual">Temporada Actual</label>
                        <select class="form-control" name="TemporadaActual" id="TemporadaActual">
                            @foreach($temporadas as $temporada)
                            <option value="{{$temporada->id}}" @if($temporada->id == $cuenta->temporada_actual) selected @endif>{{$temporada->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="Sesiones">Activar sesiones?</label>
                        <select name="Sesiones" id="Sesiones" class="form-control">
                            <option value="si" <?php if($cuenta->sesiones=='si'){ echo 'selected'; } ?>>Si</option>
                            <option value="no" <?php if($cuenta->sesiones=='no'){ echo 'selected'; } ?>>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Trivias">Activar trivias?</label>
                        <select name="Trivias" id="Trivias" class="form-control">
                            <option value="si" <?php if($cuenta->trivias=='si'){ echo 'selected'; } ?>>Si</option>
                            <option value="no" <?php if($cuenta->trivias=='no'){ echo 'selected'; } ?>>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Jackpots">Activar Jackpots?</label>
                        <select name="Jackpots" id="Jackpots" class="form-control">
                            <option value="si" <?php if($cuenta->jackpots=='si'){ echo 'selected'; } ?>>Si</option>
                            <option value="no" <?php if($cuenta->jackpots=='no'){ echo 'selected'; } ?>>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="top10">Activar Top10?</label>
                        <select name="top10" id="top10" class="form-control">
                            <option value="si" <?php if($cuenta->top10=='si'){ echo 'selected'; } ?>>Si</option>
                            <option value="no" <?php if($cuenta->top10=='no'){ echo 'selected'; } ?>>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Jackpots">Activar Champions?</label>
                        <select name="Champions" id="Champions" class="form-control">
                            <option value="si" <?php if($cuenta->champions=='si'){ echo 'selected'; } ?>>Si</option>
                            <option value="no" <?php if($cuenta->champions=='no'){ echo 'selected'; } ?>>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="CanjeoPuntos">Activar canjeo de puntos?</label>
                        <select name="CanjeoPuntos" id="CanjeoPuntos" class="form-control">
                            <option value="si" <?php if($cuenta->canjeo_puntos=='si'){ echo 'selected'; } ?>>Si</option>
                            <option value="no" <?php if($cuenta->canjeo_puntos=='no'){ echo 'selected'; } ?>>No</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="BonoLogin">Activar bono de login?</label>
                                <select name="BonoLogin" id="BonoLogin" class="form-control">
                                    <option value="si" <?php if($cuenta->bono_login=='si'){ echo 'selected'; } ?>>Si</option>
                                    <option value="no" <?php if($cuenta->bono_login=='no'){ echo 'selected'; } ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="BonoLogin">Puntos por primer login</label>
                                <input type="number" class="form-control" name="BonoLoginCantidad" value="{{$cuenta->bono_login_cantidad}}">
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="Estado" value="{{$cuenta->estado}}">
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Guardar</button> 
    </div>
    </form>
@endsection