@extends('plantillas/plantilla_admin')

@section('titulo', 'Notificaciones')

@section('contenido_principal')
    <h1>PopUps y Cintillos</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <div class="row">
        <div class="col-6">
            <div class="card card-body">
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#formularioPopup" aria-expanded="false" aria-controls="formularioPopup">
                            Nuevo PopUp
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-success w-100" type="button" data-bs-toggle="collapse" data-bs-target="#formularioPopupExterno" aria-expanded="false" aria-controls="formularioPopupExterno">
                            Popup Externo
                        </button>
                    </div>
                </div>

                <div class="collapse" id="formularioPopupExterno">
                    <form action="{{ route('popup.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="IdCuenta" value="{{$cuenta->id}}">
                        <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                        <input type="hidden" name="Clase" value="externo">
                        <input type="hidden" name="Titulo" value="Popup Ext {{ now()->format('Y-m-d') }}">
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="Contenido">Incrustar código</label>
                                    <textarea name="Contenido" class="form-control" rows="20"></textarea>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="FechaInicio">Publicar desde:</label>
                                    <input type="datetime-local" class="form-control" name="FechaInicio">
                                </div>
                                <div class="form-group">
                                    <label for="FechaFinal">Hasta:</label>
                                    <input type="datetime-local" class="form-control" name="FechaFinal">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
                
                <div class="collapse" id="formularioPopup">
                    <form action="{{ route('popup.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="IdCuenta" value="{{$cuenta->id}}">
                        <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                        <input type="hidden" name="Clase" value="normal">
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="Titulo">Titulo</label>
                                    <input type="text" class="form-control" name="Titulo">
                                </div>
                                <div class="form-group">
                                    <label for="Contenido">Contenido</label>
                                    <textarea name="Contenido" class="form-control TextEditor" rows="20"></textarea>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="Imagen">Imagen</label>
                                    <input type="file" class="form-control" name="Imagen" >
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="FechaInicio">Publicar desde:</label>
                                    <input type="datetime-local" class="form-control" name="FechaInicio">
                                </div>
                                <div class="form-group">
                                    <label for="FechaFinal">Hasta:</label>
                                    <input type="datetime-local" class="form-control" name="FechaFinal">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
                
                <hr>
                <table class="table">
                    <tbody>
                        @foreach ($popups as $pop)
                            <tr>
                                <td class="bg-light text-center mb-3">
                                    <h5>{{$pop->titulo}}</h5>
                                    <hr>
                                    {{$pop->contenido}}
                                    @if (!empty($pop->imagen)) 
                                        <img src="{{ asset('img/publicaciones/'.$pop->imagen) }}" alt="Imagen de {{ $pop->titulo }}" style="width: 100%;">
                                    @endif
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Publicar desde: <b>{{$pop->fecha_inicio}}</b>
                                                </td>
                                                <td>
                                                    hasta: <b>{{$pop->fecha_final}}</b>
                                                </td>
                                                <td>
                                                    <form action="{{route('popup.destroy', $pop->id)}}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-body">
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formularioCintillo" aria-expanded="false" aria-controls="formularioCintillo">
                    Nuevo Cintillo
                </button>
                <div class="collapse" id="formularioCintillo">
                    <form action="{{ route('cintillo.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="IdCuenta" value="{{$cuenta->id}}">
                        <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="Texto">Texto</label>
                                    <input type="text" class="form-control" name="Texto">
                                </div>
                                <div class="form-group">
                                    <label for="TextoBoton">Texto Botón</label>
                                    <input type="text" class="form-control" name="TextoBoton">
                                </div>ç
                                <div class="form-group">
                                    <label for="EnlaceBoton">Enlace Botón</label>
                                    <input type="text" class="form-control" name="EnlaceBoton">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="Imagen">Imagen</label>
                                    <input type="file" class="form-control" name="Imagen" >
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="FechaInicio">Publicar desde:</label>
                                    <input type="datetime-local" class="form-control" name="FechaInicio">
                                </div>
                                <div class="form-group">
                                    <label for="FechaFinal">Hasta:</label>
                                    <input type="datetime-local" class="form-control" name="FechaFinal">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
                
                <hr>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Contenido</th>
                            <th>Fechas</th>
                            <th>Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cintillos as $cintillo)
                            <tr>
                                <td>
                                    <h5>{{$cintillo->texto}}</h5>
                                    @if (!empty($cintillo->imagen)) 
                                            <img src="{{ asset('img/publicaciones/'.$cintillo->imagen) }}" alt="Imagen de {{ $cintillo->titulo }}" style="width: 100%;">
                                        @endif
                                    <hr>
                                    {{$cintillo->texto_boton}}
                                </td>
                                <td>
                                    Publicar desde: <b>{{$cintillo->fecha_inicio}}</b><br>
                                    hasta: <b>{{$cintillo->fecha_final}}</b>
                                </td>
                                <td>
                                    <form action="{{route('cintillo.destroy', $cintillo->id)}}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
@endsection