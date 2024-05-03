@extends('plantillas/plantilla_admin')

@section('titulo', 'Logros')

@section('contenido_principal')
    <h1>Formulario de logros</h1>
    <form action="{{ route('logros.update', $logro->id) }}" method="POST">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$logro->id_temporada}}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Nombre">Nombre</label>
                    <input type="text" class="form-control" name="Nombre" value="{{$logro->nombre}}">
                </div>
                <div class="form-group">
                    <label for="Premio">Premio</label>
                    <input type="text" class="form-control" name="Premio" value="{{$logro->premio}}">
                </div>
                <div class="form-group">
                    <label for="Instrucciones">Instrucciones</label>
                    <textarea class="form-control" name="Instrucciones" id="Instrucciones" rows="5">{{$logro->instrucciones}}</textarea>
                </div>
                <div class="form-group">
                    <label for="Contenido">Contenido</label>
                    <textarea class="form-control TextEditor" name="Contenido" id="Contenido" rows="5">{{$logro->contenido}}</textarea>
                </div>
                <hr>
                <div class="form-group">
                    <label for="NivelA">Describe el Nivel A</label>
                    <textarea class="form-control" name="NivelA" id="NivelA" rows="5">{{$logro->nivel_a}}</textarea>
                </div>
                <div class="form-group">
                    <label for="NivelB">Describe el Nivel B</label>
                    <textarea class="form-control" name="NivelB" id="NivelB" rows="5">{{$logro->nivel_b}}</textarea>
                </div>
                <div class="form-group">
                    <label for="NivelC">Describe el Nivel C</label>
                    <textarea class="form-control" name="NivelC" id="NivelC" rows="5">{{$logro->nivel_c}}</textarea>
                </div>
                <div class="form-group">
                    <label for="NivelEspecial">Describe el Nivel Especial</label>
                    <textarea class="form-control" name="NivelEspecial" id="NivelEspecial" rows="5">{{$logro->nivel_especial}}</textarea>
                </div>
                
                
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="NivelUsuario">Nivel Usuario</label>
                    <select name="NivelUsuario" id="" class="form-control">
                        <option value="ventas" @if($logro->nivel_usuario=='ventas') selected @endif>Ventas</option>
                        <option value="especialista" @if($logro->nivel_usuario=='especialista') selected @endif>Especialista</option>
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaInicio">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="FechaInicio" value="{{date('Y-m-d', strtotime($logro->fecha_inicio))}}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraInicio">Hora de Inicio</label>
                            <input type="time" class="form-control" name="HoraInicio" value="{{date('H:i:s', strtotime($logro->fecha_inicio))}}">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaVigente">Fecha de finalización</label>
                            <input type="date" class="form-control" name="FechaVigente" value="{{date('Y-m-d', strtotime($logro->fecha_vigente))}}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraVigente">Hora de finalización</label>
                            <input type="time" class="form-control" name="HoraVigente" value="{{date('H:i:s', strtotime($logro->fecha_vigente))}}">
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
@endsection