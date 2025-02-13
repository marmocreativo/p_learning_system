@extends('plantillas/plantilla_admin')

@section('titulo', 'Notificaciones')

@section('contenido_principal')
    <h1>PopUps y Cintillos</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <div class="row">
        <div class="col-6">
            <div class="card card-body">
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formularioPopup" aria-expanded="false" aria-controls="formularioPopup">
                    Nuevo PopUp
                </button>
                <div class="collapse" id="formularioPopup">
                    <form action="{{ route('popup.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="IdCuenta" value="{{$cuenta->id}}">
                        <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
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
                    <thead>
                        <tr>
                            <th>Contenido</th>
                            <th>Fechas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($popups as $pop)
                            <tr>
                                <td>
                                    <h5>{{$pop->titulo}}</h5>
                                    @if (!empty($pop->imagen)) 
                                            <img src="{{ asset('img/publicaciones/'.$pop->imagen) }}" alt="Imagen de {{ $pop->titulo }}" style="width: 100%;">
                                        @endif
                                    <hr>
                                    {{$pop->contenido}}
                                </td>
                                <td>
                                    Publicar desde: <b>{{$pop->fecha_inicio}}</b><br>
                                    hasta: <b>{{$pop->fecha_final}}</b>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-body">
                Lista de cintillos
            </div>
        </div>
    </div>
@endsection