@extends('plantillas/plantilla_admin')

@section('titulo', 'Notificaciones')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">PopUps <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <div class="btn-group" role="group" aria-label="Basic example">
        </div>
    </div>

    <nav aria-label="breadcrumb mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item dropdown">
                <a class="dropdown-toggle text-decoration-none" href="#" id="breadcrumbDropdown" role="button"  data-mdb-dropdown-init
                        data-mdb-ripple-init>
                    Cuentas
                </a>
                <ul class="dropdown-menu" aria-labelledby="breadcrumbDropdown">
                    @foreach($cuentas as $cuentaItem)
                        <li>
                            <a class="dropdown-item" href="{{ route('temporadas', ['id_cuenta' => $cuentaItem->id]) }}">
                                {{ $cuentaItem->nombre }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id)}}">{{$temporada->nombre}}</a> </li>
            <li class="breadcrumb-item">PopUps y Tiras</li>
        </ol>
    </nav>
    <hr>
    <div class="row">
        <div class="col-6">
            <div class="card card-body">
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary w-100" type="button" data-mdb-collapse-init
  data-mdb-ripple-init
  data-mdb-target="#formularioPopup" aria-expanded="false" aria-controls="formularioPopup">
                            Nuevo PopUp
                        </button>
                    </div>
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
                                <div class="form-group">
                                    <label for="Urls">Urls donde mostrar</label>
                                    <textarea name="Urls" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="Imagen">Imagen</label>
                                    <input type="file" class="form-control" name="Imagen" >
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="BotonTexto">Texto del Botón</label>
                                    <input type="text" class="form-control" name="BotonTexto">
                                </div>
                                <div class="form-group">
                                    <label for="BotonLink">Enlace del botón</label>
                                    <input type="text" class="form-control" name="BotonLink">
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
                                    <h5>{{ $pop->titulo }}</h5>
                                    
                                    <hr>
                                    {!! $pop->contenido !!}
                                    
                                    @if (!empty($pop->imagen)) 
                                        <img src="{{ asset('img/publicaciones/' . $pop->imagen) }}" alt="Imagen de {{ $pop->titulo }}" style="width: 100%;">
                                    @endif
                    
                                    <table class="table table-bordered mt-3">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Publicar desde: <b>{{ $pop->fecha_inicio }}</b>
                                                </td>
                                                <td>
                                                    hasta: <b>{{ $pop->fecha_final }}</b>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm mb-2" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalPopup{{ $pop->id }}">
                                                        Editar
                                                    </button>
                                    
                                                </td>
                                                <td>
                                                    <form action="{{ route('popup.destroy', $pop->id) }}" method="POST">
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
                    
                            <!-- Modal -->
                            <div class="modal fade" id="modalPopup{{ $pop->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $pop->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel{{ $pop->id }}">{{ $pop->titulo }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('popup.update') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="IdCuenta" value="{{$cuenta->id}}">
                                                <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                                                <input type="hidden" name="Clase" value="normal">
                                                <input type="hidden" name="Identificador" value="{{$pop->id}}">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="form-group">
                                                            <label for="Titulo">Titulo</label>
                                                            <input type="text" class="form-control" name="Titulo" value="{{$pop->titulo}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Contenido">Contenido</label>
                                                            <textarea name="Contenido" class="form-control TextEditor" rows="20">{!! $pop->contenido !!}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Urls">Urls donde mostrar</label>
                                                            <textarea name="Urls" class="form-control" rows="3">{{$pop->urls}}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        @if (!empty($pop->imagen)) 
                                                            <img src="{{ asset('img/publicaciones/' . $pop->imagen) }}" alt="Imagen de {{ $pop->titulo }}" class="img-fluid mt-3">
                                                        @endif
                                                        <div class="form-group">
                                                            <label for="Imagen">Imagen</label>
                                                            <input type="file" class="form-control" name="Imagen" >
                                                        </div>
                                                        <hr>
                                                        <div class="form-group">
                                                            <label for="BotonTexto">Texto del Botón</label>
                                                            <input type="text" class="form-control" name="BotonTexto" value="{{$pop->boton_texto}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="BotonLink">Enlace del botón</label>
                                                            <input type="text" class="form-control" name="BotonLink" value="{{$pop->boton_link}}">
                                                        </div>
                                                        <hr>
                                                        <div class="form-group">
                                                            <label for="FechaInicio">Publicar desde:</label>
                                                            <input type="datetime-local" class="form-control" name="FechaInicio" value="{{ $pop->fecha_inicio }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="FechaFinal">Hasta:</label>
                                                            <input type="datetime-local" class="form-control" name="FechaFinal" name="FechaInicio" value="{{ $pop->fecha_final }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-body">
                <button class="btn btn-primary" type="button" data-mdb-collapse-init
  data-mdb-ripple-init
  data-mdb-target="#formularioCintillo" aria-expanded="false" aria-controls="formularioCintillo">
                    Nueva Tira
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
                                    <textarea name="Texto" class="form-control TextEditor" rows="1"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="TextoBoton">Texto Botón</label>
                                    <input type="text" class="form-control" name="TextoBoton">
                                </div>
                                <div class="form-group">
                                    <label for="EnlaceBoton">Enlace Botón</label>
                                    <input type="text" class="form-control" name="EnlaceBoton">
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
                                    <h5>{!! $cintillo->texto !!}</h5>
                                    @if (!empty($cintillo->imagen)) 
                                            <img src="{{ asset('img/publicaciones/'.$cintillo->imagen) }}" alt="Imagen de {{ $cintillo->titulo }}" style="width: 100%;">
                                        @endif
                                    <hr>
                                    <button class="btn btn-outline-dark">{{$cintillo->texto_boton}}</button>
                                </td>
                                <td>
                                    Publicar desde: <b>{{$cintillo->fecha_inicio}}</b><br>
                                    hasta: <b>{{$cintillo->fecha_final}}</b>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm mb-2" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalCintillo{{ $cintillo->id }}">
                                        Editar
                                    </button>
                    
                                </td>
                                <td>
                                    <form action="{{route('cintillo.destroy', $cintillo->id)}}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                            <!-- Modal -->
                            <div class="modal fade" id="modalCintillo{{ $cintillo->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $cintillo->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel{{ $cintillo->id }}">{{ $cintillo->titulo }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('cintillo.update') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="Identificador" value="{{$cintillo->id}}">
                                                <input type="hidden" name="IdCuenta" value="{{$cuenta->id}}">
                                                <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="form-group">
                                                            <label for="Texto">Texto</label>
                                                            <textarea name="Texto" class="form-control TextEditor" rows="1">{{$cintillo->texto}}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="TextoBoton">Texto Botón</label>
                                                            <input type="text" class="form-control" name="TextoBoton" value="{{$cintillo->texto_boton}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="EnlaceBoton">Enlace Botón</label>
                                                            <input type="text" class="form-control" name="EnlaceBoton" value="{{$cintillo->enlace_boton}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="FechaInicio">Publicar desde:</label>
                                                            <input type="datetime-local" class="form-control" name="FechaInicio" value="{{$cintillo->fecha_inicio}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="FechaFinal">Hasta:</label>
                                                            <input type="datetime-local" class="form-control" name="FechaFinal" value="{{$cintillo->fecha_final}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
@endsection