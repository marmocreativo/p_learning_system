@extends('plantillas/plantilla_admin')

@section('titulo', 'Logros')

@section('contenido_principal')
    <h1>Formulario de desafios</h1>
    <form action="{{ route('logros.update', $logro->id) }}" method="POST" enctype="multipart/form-data">
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
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="Premio">Premio MX</label>
                            <input type="text" class="form-control" name="Premio" value="{{$logro->premio}}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="PremioRola">Premio ROLA</label>
                            <input type="text" class="form-control" name="PremioRola" value="{{$logro->premio_rola}}">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="Instrucciones">Instrucciones</label>
                    <textarea class="form-control" name="Instrucciones" id="Instrucciones" rows="5">{{$logro->instrucciones}}</textarea>
                </div>
                <div class="form-group">
                    <label for="Contenido">Contenido</label>
                    <textarea class="form-control TextEditor" name="Contenido" id="Contenido" rows="5">{{$logro->contenido}}</textarea>
                </div>
                <div class="form-group">
                    <label for="Sesiones">Sesiones requeridas <small>(Separadas por coma)</small></label>
                    <textarea class="form-control" name="Sesiones" id="Sesiones" rows="5">{{$logro->sesiones}}</textarea>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="NivelA">Describe el Nivel A</label>
                            <textarea class="form-control TextEditor" name="NivelA" id="NivelA" rows="5">{{$logro->nivel_a}}</textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioA">Premio nivel A MX(número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioA" value="{{$logro->premio_a}}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioRolaA">Premio nivel A ROLA(número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioRolaA" value="{{$logro->premio_rola_a}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="NivelB">Describe el Nivel B</label>
                            <textarea class="form-control TextEditor" name="NivelB" id="NivelB" rows="5">{{$logro->nivel_b}}</textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioB">Premio nivel B MX(número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioB" value="{{$logro->premio_b}}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioRolaB">Premio nivel B ROLA(número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioRolaB" value="{{$logro->premio_rola_b}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="NivelC">Describe el Nivel C</label>
                            <textarea class="form-control TextEditor" name="NivelC" id="NivelC" rows="5">{{$logro->nivel_c}}</textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioC">Premio nivel C MX(número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioC" value="{{$logro->premio_c}}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioRolaC">Premio nivel C ROLA(número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioRolaC" value="{{$logro->premio_rola_c}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="NivelEspecial">Describe el Nivel Especial</label>
                            <textarea class="form-control TextEditor" name="NivelEspecial" id="NivelEspecial" rows="5">{{$logro->nivel_especial}}</textarea>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioEspecial">Premio nivel Especial MX(número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioEspecial" value="{{$logro->premio_especial}}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="PremioRolaEspecial">Premio nivel Especial ROLA(número)</label>
                            <input type="number" class="form-control" step="0.01" name="PremioRolaEspecial" value="{{$logro->premio_rola_especial}}">
                        </div>
                    </div>
                </div>
                
                
            </div>
            <div class="col-4">
                <img class="img-fluid w-50" src="{{ asset('img/publicaciones/'.$logro->imagen) }}" >
                <div class="form-group">
                    <label for="Imagen">Imagen cards</label>
                    <input type="file" class="form-control" name="Imagen" >
                </div>
                <hr/>
                <img class="img-fluid w-50" src="{{ asset('img/publicaciones/'.$logro->imagen_fondo) }}" >
                <div class="form-group">
                    <label for="ImagenFondo">Imagen cuadricula</label>
                    <input type="file" class="form-control" name="ImagenFondo" >
                </div>
                <hr/>
                <img class="img-fluid w-50" src="{{ asset('img/publicaciones/'.$logro->tabla_mx) }}" >
                <div class="form-group">
                    <label for="TablaMx">Imagen tabla premios MX</label>
                    <input type="file" class="form-control" name="TablaMx" >
                </div>
                <hr/>
                <img class="img-fluid w-50" src="{{ asset('img/publicaciones/'.$logro->tabla_rola) }}" >
                <div class="form-group">
                    <label for="TablaRola">Imagen tabla premios ROLA</label>
                    <input type="file" class="form-control" name="TablaRola" >
                </div>
                <hr>
                
                <div class="form-group">
                    <label for="NivelUsuario">Nivel Usuario</label>
                    <select name="NivelUsuario" id="" class="form-control">
                        <option value="ventas" @if($logro->nivel_usuario=='ventas') selected @endif>Ventas</option>
                        <option value="especialista" @if($logro->nivel_usuario=='especialista') selected @endif>Especialista</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Region">Región</label>
                    <select class="form-control" name="Region" id="Region">
                        <option value="Todas" @if($logro->region=='Todas')selected @endif>Todas</option>
                        <option value="México" @if($logro->region=='México')selected @endif>México</option>
                        <option value="RoLA" @if($logro->region=='RoLA')selected @endif>RoLA</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="CantidadEvidencias">Cantidad de evidencias (Max)</label>
                    <input type="number" class="form-control" step="1" name="CantidadEvidencias" value="{{$logro->cantidad_evidencias}}">
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
                <div class="form-group">
                    <label for="Orden">Orden</label>
                    <input type="number" min="0" class="form-control" name="Orden" value="{{$logro->orden}}">
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </form>
@endsection