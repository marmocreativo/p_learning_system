@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas')

@section('contenido_principal')
    <h1>Formulario de Temporadas</h1>
    <form action="{{ route('temporadas.update', $temporada->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label for="Nombre">Nombre de la temporada</label>
                    <input type="text" class="form-control" name="Nombre" value="{{$temporada->nombre}}">
                </div>
                <div class="form-group">
                    <label for="Url">URL</label>
                    <input type="text" class="form-control" name="Url" value="{{$temporada->url}}">
                </div>
                <input type="hidden" name="IdCuenta" value="{{$temporada->id_cuenta}}">
                <div class="form-group">
                    <label for="Descripcion">Descripción</label>
                    <textarea class="form-control" name="Descripcion" id="Descripcion" rows="10">{{$temporada->descripcion}}</textarea>
                </div>
                <div class="form-group">
                    <label for="Imagen">Imagen</label>
                    <input type="file" class="form-control" name="Imagen" >
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    <label for="TituloLanding">Título Landing</label>
                    <input type="text" class="form-control" name="TituloLanding" value="{{$temporada->titulo_landing}}">
                </div>
                <div class="form-group">
                    <label for="MensajeLanding">Mensaje Landing</label>
                    <textarea class="form-control" name="MensajeLanding" id="MensajeLanding" rows="10">{{$temporada->mensaje_landing}}</textarea>
                </div>
                <div class="form-group">
                    <label for="FechaInicio">Fecha de Inicio</label>
                    <input type="date" class="form-control" name="FechaInicio" value="{{date('Y-m-d', strtotime($temporada->fecha_inicio))}}">
                </div>
                <div class="form-group">
                    <label for="FechaFinal">Fecha final</label>
                    <input type="date" class="form-control" name="FechaFinal" value="{{date('Y-m-d', strtotime($temporada->fecha_final))}}">
                </div>
                <div class="form-group">
                    <label for="Estado">Estado</label>
                    <select name="Estado" id="Estado" class="form-control">
                        <option value="activa" @if($temporada->estado == 'activa') selected @endif>Activa</option>
                        <option value="inactiva" @if($temporada->estado == 'inactiva') selected @endif>Inactiva</option>
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
@endsection