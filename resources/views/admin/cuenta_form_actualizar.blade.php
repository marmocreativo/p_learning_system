@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas')

@section('contenido_principal')
    <h1>Actualizar la cuenta</h1>
    <form action="{{ route('cuentas.update',$cuenta->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    <div class="row">
        <div class="col-3">
            <h5>Configuración de la cuenta</h5>
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
                <label for="CanjeoPuntos">Activar canjeo de puntos?</label>
                <select name="CanjeoPuntos" id="CanjeoPuntos" class="form-control">
                    <option value="si" <?php if($cuenta->canjeo_puntos=='si'){ echo 'selected'; } ?>>Si</option>
                    <option value="no" <?php if($cuenta->canjeo_puntos=='no'){ echo 'selected'; } ?>>No</option>
                </select>
            </div>
            <input type="hidden" name="Estado" value="{{$cuenta->estado}}">
        </div>
        <div class="col-9">
            <h5>Home</h5>
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="Badge">Medalla / cintillo</label>
                        <input type="text" class="form-control" name="Badge" value="{{$cuenta->badge}}">
                    </div>
                    <div class="form-group">
                        <label for="Titulo">Titulo</label>
                        <input type="text" class="form-control" name="Titulo" value="{{$cuenta->titulo}}">
                    </div>
                    <div class="form-group">
                        <label for="TituloResaltado">Titulo Resaltado</label>
                        <input type="text" class="form-control" name="TituloResaltado" value="{{$cuenta->titulo_resaltado}}">
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="BotonTexto">Botón (texto)</label>
                        <input type="text" class="form-control" name="BotonTexto" value="{{$cuenta->boton_texto}}">
                    </div>
                    <div class="form-group">
                        <label for="BotonEnlace">Botón (enlace)</label>
                        <input type="text" class="form-control" name="BotonEnlace" value="{{$cuenta->boton_enlace}}">
                    </div>
                </div>
                <div class="col-8">
                    <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$cuenta->fondo) }}" alt="Imagen">
                    <div class="form-group">
                        <label for="Fondo">Fondo</label>
                        <input type="file" class="form-control" name="Fondo" >
                    </div>
                    <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$cuenta->imagen_video) }}" alt="Imagen">
                    <div class="form-group">
                        <label for="ImagenVideo">Imagen del video</label>
                        <input type="file" class="form-control" name="ImagenVideo" >
                    </div>
                    <div class="form-group">
                        <label for="LinkVideo">URL Video</label>
                        <input type="text" class="form-control" name="LinkVideo" value="{{$cuenta->link_video}}">
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                
            </div>
        </div>
    </div>
    </form>
@endsection