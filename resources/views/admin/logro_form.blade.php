@extends('plantillas/plantilla_admin')

@section('titulo', 'Logros')

@section('contenido_principal')
    <h1>Formulario de logros</h1>
    <form action="{{ route('logros.store') }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="IdCuenta" value="1">
        <input type="hidden" name="IdTemporada" value="{{$_GET['id_temporada']}}">
        @csrf
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <label for="Nombre">Nombre</label>
                    <input type="text" class="form-control" name="Nombre">
                </div>
                <div class="form-group">
                    <label for="Premio">Premio en texto</label>
                    <input type="text" class="form-control" name="Premio">
                </div>
                
                <div class="form-group">
                    <label for="Instrucciones">Instrucciones</label>
                    <textarea class="form-control" name="Instrucciones" id="Instrucciones" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="Contenido">Contenido</label>
                    <textarea class="form-control TextEditor" name="Contenido" id="Contenido" rows="5"></textarea>
                </div>
                <hr>
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="NivelA">Describe el Nivel A</label>
                            <textarea class="form-control TextEditor" name="NivelA" id="NivelA" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioA">Premio nivel A (número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioA">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="NivelB">Describe el Nivel B</label>
                            <textarea class="form-control TextEditor" name="NivelB" id="NivelB" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioB">Premio nivel B (número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioB">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="NivelC">Describe el Nivel C</label>
                            <textarea class="form-control TextEditor" name="NivelC" id="NivelC" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioC">Premio nivel C (número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioC">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="NivelEspecial">Describe el Nivel Especial</label>
                            <textarea class="form-control TextEditor" name="NivelEspecial" id="NivelEspecial" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioEspecial">Premio nivel Especial (número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioEspecial">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="Imagen">Imagen</label>
                    <input type="file" class="form-control" name="Imagen" >
                </div>

                <div class="form-group">
                    <label for="ImagenFondo">Imagen tabla de datos</label>
                    <input type="file" class="form-control" name="ImagenFondo" >
                </div>
                <hr>
                
                <div class="form-group">
                    <label for="NivelUsuario">Nivel Usuario</label>
                    <select name="NivelUsuario" id="" class="form-control">
                        <option value="ventas">Ventas</option>
                        <option value="especialista">Especialista</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Region">Región</label>
                    <select class="form-control" name="Region" id="Region">
                        <option value="Todas" >Todas</option>
                        <option value="México" >México</option>
                        <option value="RoLA" >RoLA</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="CantidadEvidencias">Cantidad de evidencias (Max)</label>
                    <input type="number" class="form-control" step="1" name="CantidadEvidencias">
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaInicio">Fecha de Inicio</label>
                            <input type="date" class="form-control" name="FechaInicio">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraInicio">Hora de Inicio</label>
                            <input type="time" class="form-control" name="HoraInicio">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="FechaVigente">Fecha de finalización</label>
                            <input type="date" class="form-control" name="FechaVigente">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="HoraVigente">Hora de finalización</label>
                            <input type="time" class="form-control" name="HoraVigente">
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
@endsection