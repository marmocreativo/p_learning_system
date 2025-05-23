@extends('plantillas/plantilla_admin')

@section('titulo', 'Trivias')

@section('contenido_principal')
    <h1>Formulario de trivias</h1>
    <form action="{{ route('trivias.update', $trivia->id ) }}" method="POST">
        <input type="hidden" name="IdCuenta" value="{{$trivia->id_cuenta}}">
        <input type="hidden" name="IdTemporada" value="{{$trivia->id_temporada}}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Titulo">Titulo</label>
                    <input type="text" class="form-control" name="Titulo" value="{{ $trivia->titulo }}">
                </div>
                
                <div class="form-group">
                    <label for="Descripcion">Descripción</label>
                    <textarea class="form-control TextEditor" name="Descripcion" id="Descripcion" rows="5">{{ $trivia->descripcion }}</textarea>
                </div>
                <input type="hidden" name="MensajeAntes" value="{{ $trivia->mensaje_antes }}">
                <input type="hidden" name="MensajeDespues" value="{{ $trivia->mensaje_despues }}">
                
                
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="Estado">Estado</label>
                    <select name="Estado" id="Estado" class="form-control">
                        <option value="activo" @if($trivia->estado == 'activo') selected @endif >Activo</option>
                        <option value="inactivo" @if($trivia->estado == 'inactivo') selected @endif >Inactivo</option>
                    </select>
                </div>
                <hr>
                <h5>Puntaje</h5>
                <div class="form-group">
                    <label for="Puntaje">Puntos por pregunta</label>
                    <input type="number" class="form-control" name="Puntaje" value="{{ $trivia->puntaje }}">
                </div>
                <hr>
                <h5>Configuracion preguntas</h5>
                <div class="form-group">
                    <label for="CantidadPreguntas">Número de preguntas a mostrar</label>
                    <input type="number" class="form-control" min="0" step="1" name="CantidadPreguntas" value="{{ $trivia->cantidad_preguntas }}">
                </div>
                <div class="form-group">
                    <label for="Orden">Orden para mostrar las preguntas</label>
                    <select name="Orden" id="Orden" class="form-control">
                        <option value="ordenado" @if($trivia->orden == 'ordenado') selected @endif>Ordenado</option>
                        <option value="random" @if($trivia->orden == 'random') selected @endif>Random</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="IdJackpot">Incrustar Minijuego</label>
                    <select name="IdJackpot" id="IdJackpot" class="form-control">
                        <option value="">Ningúno</option>
                        @foreach ($jackpots as $jackpot)
                            <option value="{{$jackpot->id}}" @if ($jackpot->id==$trivia->id_jackpot) selected @endif>{{$jackpot->titulo}}</option>
                        @endforeach
                    </select>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaPublicacion">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="FechaPublicacion" value="{{ date('Y-m-d', strtotime($trivia->fecha_publicacion)) }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraPublicacion">Hora de Inicio</label>
                            <input type="time" class="form-control" name="HoraPublicacion" value="{{ date('H:i:s', strtotime($trivia->fecha_publicacion)) }}">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaVigencia">Fecha de finalización</label>
                            <input type="date" class="form-control" name="FechaVigencia" value="{{ date('Y-m-d', strtotime($trivia->fecha_vigencia)) }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraVigencia">Hora de finalización</label>
                            <input type="time" class="form-control" name="HoraVigencia" value="{{ date('H:i:s', strtotime($trivia->fecha_vigencia)) }}">
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
@endsection