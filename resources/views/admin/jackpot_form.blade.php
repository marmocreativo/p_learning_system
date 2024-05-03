@extends('plantillas/plantilla_admin')

@section('titulo', 'Jackpots')

@section('contenido_principal')
    <h1>Formulario de jacpots</h1>
    <form action="{{ route('jackpots.store') }}" method="POST">
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
                    <label for="MensajeAntes">Mensaje Antes del inicio</label>
                    <textarea class="form-control TextEditor" name="MensajeAntes" id="MensajeAntes" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="MensajeDespues">Mensaje Después del término</label>
                    <textarea class="form-control TextEditor" name="MensajeDespues" id="MensajeDespues" rows="5"></textarea>
                </div>
                
                
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="Estado">Estado</label>
                    <select name="Estado" id="Estado" class="form-control">
                        <option value="activo"  >Activo</option>
                        <option value="inactivo"  >Inactivo</option>
                    </select>
                </div>
                <h5>Intentos</h5>
                <div class="form-group">
                    <label for="Intentos">Intentos por jackpot</label>
                    <input type="number" class="form-control" name="Intentos">
                </div>
                <div class="form-group">
                    <label for="Trivia">¿Trivia obligatoria?</label>
                    <select class="form-control" name="Trivia" id="Trivia">
                        <option value="si">Si</option>
                        <option value="no">No</option>
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