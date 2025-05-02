@extends('plantillas/plantilla_admin')

@section('titulo', 'Trivias')

@section('contenido_principal')
    <h1>Formulario de trivias</h1>
    <form action="{{ route('trivias.store') }}" method="POST">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$_GET['id_temporada']}}">
        @csrf
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Titulo">Titulo</label>
                    <input type="text" class="form-control" name="Titulo">
                </div>
                
                <div class="form-group">
                    <label for="Descripcion">Descripción</label>
                    <textarea class="form-control TextEditor" name="Descripcion" id="Descripcion" rows="5"></textarea>
                </div>
                <input type="hidden" name="MensajeAntes" value="">
                <input type="hidden" name="MensajeDespues" value="">
                
                
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="Estado">Estado</label>
                    <select name="Estado" id="Estado" class="form-control">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
                <hr>
                <h5>Puntaje</h5>
                <div class="form-group">
                    <label for="Puntaje">Puntos por pregunta</label>
                    <input type="number" class="form-control" name="Puntaje">
                </div>
                <hr>
                <h5>Configuracion preguntas</h5>
                <div class="form-group">
                    <label for="CantidadPreguntas">Número de preguntas a mostrar</label>
                    <input type="number" class="form-control" min="0" step="1" name="CantidadPreguntas">
                </div>
                <div class="form-group">
                    <label for="CantidadPreguntas"></label>
                    <select name="Orden" id="Orden" class="form-control">
                        <option value="ordenado">Ordenado</option>
                        <option value="random">Random</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="IdJackpot">Incrustar Minijuego</label>
                    <select name="IdJackpot" id="IdJackpot" class="form-control">
                        <option value="">Ningúno</option>
                        @foreach ($jackpots as $jackpot)
                            <option value="{{$jackpot->id}}">{{$jackpot->titulo}}</option>
                        @endforeach
                        
                    </select>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaPublicacion">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="FechaPublicacion">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraPublicacion">Hora de Inicio</label>
                            <input type="time" class="form-control" name="HoraPublicacion">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaVigencia">Fecha de finalización</label>
                            <input type="date" class="form-control" name="FechaVigencia">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraVigencia">Hora de finalización</label>
                            <input type="time" class="form-control" name="HoraVigencia">
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
@endsection