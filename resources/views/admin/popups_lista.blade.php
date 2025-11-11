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
            <li class="breadcrumb-item">PopUps y Cintillos</li>
        </ol>
    </nav>
    <hr>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="popupsTab" role="tablist" data-mdb-tabs-init>
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="popups-tab" data-mdb-tab-init href="#popups" role="tab" aria-controls="popups" aria-selected="true">
                PopUps <span class="badge badge-primary">{{ count($popups) }}</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="cintillos-tab" data-mdb-tab-init href="#cintillos" role="tab" aria-controls="cintillos" aria-selected="false">
                Cintillos <span class="badge badge-secondary">{{ count($cintillos) }}</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="popup-lideres-tab" data-mdb-tab-init href="#popup-lideres" role="tab" aria-controls="popup-lideres" aria-selected="false">
                PopUp Lideres <span class="badge badge-info">{{ count($popup_lideres) }}</span>
            </a>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="popupsTabContent">
        <!-- Tab PopUps -->
        <div class="tab-pane fade show active" id="popups" role="tabpanel" aria-labelledby="popups-tab">
            <div class="card card-body mt-3">
                <!-- Botón crear y formulario en una fila -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <button class="btn btn-primary w-100" type="button" data-mdb-collapse-init
                            data-mdb-ripple-init data-mdb-target="#formularioPopup" aria-expanded="false" aria-controls="formularioPopup">
                            <i class="fas fa-plus me-2"></i>Nuevo PopUp
                        </button>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex justify-content-end align-items-center">
                            <span class="badge badge-info me-2">{{ count($popups) }} PopUps</span>
                            <small class="text-muted">Administra los popups de la temporada</small>
                        </div>
                    </div>
                </div>
                
                <!-- Formulario colapsable más compacto -->
                <div class="collapse" id="formularioPopup">
                    <div class="card border-primary mb-3">
                        <div class="card-body">
                            <form action="{{ route('popup.create') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="IdCuenta" value="{{$cuenta->id}}">
                                <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                                <input type="hidden" name="Clase" value="normal">
                                
                                <div class="row">
                                    <!-- Columna principal -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="Titulo" class="form-label">Título</label>
                                            <input type="text" class="form-control" name="Titulo" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="Contenido" class="form-label">Contenido</label>
                                            <textarea name="Contenido" class="form-control TextEditor" rows="12"></textarea>
                                        </div>
                                    </div>
                                    
                                    <!-- Columna lateral -->
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="Imagen" class="form-label">Imagen</label>
                                            <input type="file" class="form-control" name="Imagen" accept="image/*">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="BotonTexto" class="form-label">Texto del Botón</label>
                                            <input type="text" class="form-control" name="BotonTexto">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="BotonLink" class="form-label">Enlace del botón</label>
                                            <input type="url" class="form-control" name="BotonLink">
                                        </div>
                                    </div>
                                    
                                    <!-- Columna de fechas y URLs -->
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="Urls" class="form-label">URLs donde mostrar</label>
                                            <textarea name="Urls" class="form-control" rows="3" placeholder="Una URL por línea"></textarea>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="FechaInicio" class="form-label">Publicar desde</label>
                                            <input type="datetime-local" class="form-control" name="FechaInicio">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="FechaFinal" class="form-label">Hasta</label>
                                            <input type="datetime-local" class="form-control" name="FechaFinal">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2" data-mdb-collapse-init data-mdb-target="#formularioPopup">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Guardar PopUp
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de popups en formato de cards -->
                <div class="row">
                    @forelse ($popups as $pop)
                        @php
                            $esExpirado = $pop->fecha_final && \Carbon\Carbon::parse($pop->fecha_final)->isPast();
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 {{ $esExpirado ? 'opacity-75' : '' }}">
                                @if (!empty($pop->imagen))
                                    <img src="{{ asset('img/publicaciones/' . $pop->imagen) }}" 
                                        class="card-img-top {{ $esExpirado ? 'grayscale' : '' }}" 
                                        alt="{{ $pop->titulo }}" 
                                        style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title {{ $esExpirado ? 'text-muted' : '' }}">
                                        {{ $pop->titulo }}
                                        @if($esExpirado)
                                            <span class="badge badge-secondary ms-2">Expirado</span>
                                        @endif
                                    </h5>
                                    <div class="card-text flex-grow-1">
                                        <div style="max-height: 120px; overflow: hidden;">
                                            {!! Str::limit(strip_tags($pop->contenido), 150) !!}
                                        </div>
                                    </div>
                                    
                                    <!-- Información de fechas -->
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="row text-muted small">
                                            <div class="col-6">
                                                <strong>Desde:</strong><br>
                                                {{ $pop->fecha_inicio ? \Carbon\Carbon::parse($pop->fecha_inicio)->format('d/m/Y H:i') : 'No definida' }}
                                            </div>
                                            <div class="col-6">
                                                <strong>Hasta:</strong><br>
                                                {{ $pop->fecha_final ? \Carbon\Carbon::parse($pop->fecha_final)->format('d/m/Y H:i') : 'No definida' }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Botón si existe -->
                                    @if(!empty($pop->boton_texto))
                                        <div class="mt-2">
                                            <span class="badge badge-outline-primary">{{ $pop->boton_texto }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Acciones -->
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalPopup{{ $pop->id }}">
                                            <i class="fas fa-edit me-1"></i>Editar
                                        </button>
                                        <form action="{{ route('popup.destroy', $pop->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar este popup?')">
                                                <i class="fas fa-trash me-1"></i>Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal para editar (más compacto) -->
                        <div class="modal fade" id="modalPopup{{ $pop->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $pop->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel{{ $pop->id }}">
                                            <i class="fas fa-edit me-2"></i>Editar: {{ $pop->titulo }}
                                        </h5>
                                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('popup.update') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="IdCuenta" value="{{$cuenta->id}}">
                                            <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                                            <input type="hidden" name="Clase" value="normal">
                                            <input type="hidden" name="Identificador" value="{{$pop->id}}">
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="Titulo" class="form-label">Título</label>
                                                        <input type="text" class="form-control" name="Titulo" value="{{$pop->titulo}}" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="Contenido" class="form-label">Contenido</label>
                                                        <textarea name="Contenido" class="form-control TextEditor" rows="12">{!! $pop->contenido !!}</textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    @if (!empty($pop->imagen))
                                                        <div class="mb-3 text-center">
                                                            <img src="{{ asset('img/publicaciones/' . $pop->imagen) }}" alt="Imagen actual" class="img-fluid rounded" style="max-height: 150px;">
                                                            <small class="d-block text-muted mt-1">Imagen actual</small>
                                                        </div>
                                                    @endif
                                                    <div class="form-group mb-3">
                                                        <label for="Imagen" class="form-label">Nueva imagen</label>
                                                        <input type="file" class="form-control" name="Imagen" accept="image/*">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="BotonTexto" class="form-label">Texto del Botón</label>
                                                        <input type="text" class="form-control" name="BotonTexto" value="{{$pop->boton_texto}}">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="BotonLink" class="form-label">Enlace del botón</label>
                                                        <input type="url" class="form-control" name="BotonLink" value="{{$pop->boton_link}}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label for="Urls" class="form-label">URLs donde mostrar</label>
                                                        <textarea name="Urls" class="form-control" rows="4">{{$pop->urls}}</textarea>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="FechaInicio" class="form-label">Publicar desde</label>
                                                        <input type="datetime-local" class="form-control" name="FechaInicio" value="{{ $pop->fecha_inicio }}">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="FechaFinal" class="form-label">Hasta</label>
                                                        <input type="datetime-local" class="form-control" name="FechaFinal" value="{{ $pop->fecha_final }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-2"></i>Actualizar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay popups creados</h5>
                                <p class="text-muted">Haz clic en "Nuevo PopUp" para crear el primero</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <style>
        .grayscale {
            filter: grayscale(100%);
            transition: filter 0.3s ease;
        }

        .grayscale:hover {
            filter: grayscale(50%);
        }
        </style>

        <!-- Tab Tiras -->
        <div class="tab-pane fade" id="cintillos" role="tabpanel" aria-labelledby="cintillos-tab">
            <div class="card card-body mt-3">
                <!-- Header con botón crear y estadísticas -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <button class="btn btn-primary w-100" type="button" data-mdb-collapse-init
                            data-mdb-ripple-init data-mdb-target="#formularioCintillo" aria-expanded="false" aria-controls="formularioCintillo">
                            <i class="fas fa-plus me-2"></i>Nuevo Cintillo
                        </button>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex justify-content-end align-items-center">
                            <span class="badge badge-secondary me-2">{{ count($cintillos) }} Cintillos</span>
                            <small class="text-muted">Administra los cintillos informativos de la temporada</small>
                        </div>
                    </div>
                </div>
                
                <!-- Formulario colapsable más compacto -->
                <div class="collapse" id="formularioCintillo">
                    <div class="card border-primary mb-3">
                        <div class="card-body">
                            <form action="{{ route('cintillo.create') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="IdCuenta" value="{{$cuenta->id}}">
                                <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                                
                                <div class="row">
                                    <!-- Columna principal -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="Texto" class="form-label">Texto del Cintillo</label>
                                            <textarea name="Texto" class="form-control TextEditor" rows="3" placeholder="Contenido que se mostrará en el cintillo"></textarea>
                                        </div>
                                    </div>
                                    
                                    <!-- Columna lateral izquierda -->
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="TextoBoton" class="form-label">Texto del Botón</label>
                                            <input type="text" class="form-control" name="TextoBoton" placeholder="Ej: Ver más">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="EnlaceBoton" class="form-label">Enlace del Botón</label>
                                            <input type="url" class="form-control" name="EnlaceBoton" placeholder="https://...">
                                        </div>
                                    </div>
                                    
                                    <!-- Columna de fechas -->
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="FechaInicio" class="form-label">Publicar desde</label>
                                            <input type="datetime-local" class="form-control" name="FechaInicio">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="FechaFinal" class="form-label">Hasta</label>
                                            <input type="datetime-local" class="form-control" name="FechaFinal">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2" data-mdb-collapse-init data-mdb-target="#formularioCintillo">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Guardar Cintillo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de tiras como timeline vertical -->
                <div class="row">
                    <div class="col-12">
                        @forelse ($cintillos as $cintillo)
                            @php
                                $esExpirado = $cintillo->fecha_final && \Carbon\Carbon::parse($cintillo->fecha_final)->isPast();
                            @endphp
                            <div class="card mb-3 {{ $esExpirado ? 'border-secondary opacity-75' : 'border-primary' }}">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- Contenido principal -->
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="me-3">
                                                    <i class="fas fa-info-circle fa-2x {{ $esExpirado ? 'text-secondary' : 'text-primary' }}"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-1 {{ $esExpirado ? 'text-muted' : '' }}">
                                                        Cintillo
                                                        @if($esExpirado)
                                                            <span class="badge badge-secondary ms-2">Expirada</span>
                                                        @else
                                                            <span class="badge badge-success ms-2">Activa</span>
                                                        @endif
                                                    </h5>
                                                </div>
                                            </div>
                                            
                                            <!-- Preview de la tira -->
                                            <div class="alert {{ $esExpirado ? 'alert-secondary' : 'alert-info' }} mb-2" style="font-size: 0.9rem;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="flex-grow-1">
                                                        {!! $cintillo->texto !!}
                                                    </div>
                                                    @if(!empty($cintillo->texto_boton))
                                                        <div class="ms-3">
                                                            <button class="btn btn-sm {{ $esExpirado ? 'btn-outline-secondary' : 'btn-outline-dark' }}">
                                                                {{ $cintillo->texto_boton }}
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if (!empty($cintillo->imagen))
                                                <img src="{{ asset('img/publicaciones/'.$cintillo->imagen) }}" 
                                                    alt="Imagen del cintillo" 
                                                    class="img-fluid rounded {{ $esExpirado ? 'grayscale' : '' }}" 
                                                    style="max-height: 100px; object-fit: cover;">
                                            @endif
                                        </div>
                                        
                                        <!-- Información de fechas -->
                                        <div class="col-md-4">
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <div class="border-end">
                                                        <small class="text-muted d-block">Desde</small>
                                                        <strong class="small">
                                                            {{ $cintillo->fecha_inicio ? \Carbon\Carbon::parse($cintillo->fecha_inicio)->format('d/m/Y') : 'No definida' }}
                                                        </strong>
                                                        <div class="small text-muted">
                                                            {{ $cintillo->fecha_inicio ? \Carbon\Carbon::parse($cintillo->fecha_inicio)->format('H:i') : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Hasta</small>
                                                    <strong class="small {{ $esExpirado ? 'text-danger' : '' }}">
                                                        {{ $cintillo->fecha_final ? \Carbon\Carbon::parse($cintillo->fecha_final)->format('d/m/Y') : 'No definida' }}
                                                    </strong>
                                                    <div class="small {{ $esExpirado ? 'text-danger' : 'text-muted' }}">
                                                        {{ $cintillo->fecha_final ? \Carbon\Carbon::parse($cintillo->fecha_final)->format('H:i') : '' }}
                                                        @if($esExpirado)
                                                            <br><span class="text-danger">⚠️ Expirada</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Acciones -->
                                        <div class="col-md-2">
                                            <div class="d-flex flex-column gap-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalCintillo{{ $cintillo->id }}">
                                                    <i class="fas fa-edit me-1"></i>Editar
                                                </button>
                                                <form action="{{route('cintillo.destroy', $cintillo->id)}}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('¿Eliminar esta tira?')">
                                                        <i class="fas fa-trash me-1"></i>Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal para editar -->
                            <div class="modal fade" id="modalCintillo{{ $cintillo->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $cintillo->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel{{ $cintillo->id }}">
                                                <i class="fas fa-edit me-2"></i>Editar Cintillo Informativo
                                            </h5>
                                            <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('cintillo.update') }}" method="POST" enctype="multipart/form-data" id="formEditCintillo{{ $cintillo->id }}">
                                                @csrf
                                                <input type="hidden" name="Identificador" value="{{$cintillo->id}}">
                                                <input type="hidden" name="IdCuenta" value="{{$cuenta->id}}">
                                                <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                                                
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group mb-3">
                                                            <label for="Texto" class="form-label">Texto del Cintillo</label>
                                                            <textarea name="Texto" class="form-control TextEditor" rows="3">{{$cintillo->texto}}</textarea>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="TextoBoton" class="form-label">Texto del Botón</label>
                                                                    <input type="text" class="form-control" name="TextoBoton" value="{{$cintillo->texto_boton}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label for="EnlaceBoton" class="form-label">Enlace del Botón</label>
                                                                    <input type="url" class="form-control" name="EnlaceBoton" value="{{$cintillo->enlace_boton}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label for="FechaInicio" class="form-label">Publicar desde</label>
                                                            <input type="datetime-local" class="form-control" name="FechaInicio" value="{{$cintillo->fecha_inicio}}">
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="FechaFinal" class="form-label">Hasta</label>
                                                            <input type="datetime-local" class="form-control" name="FechaFinal" value="{{$cintillo->fecha_final}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary" form="formEditCintillo{{ $cintillo->id }}">
                                                        <i class="fas fa-save me-2"></i>Actualizar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-ribbon fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay cintillos informativos creados</h5>
                                <p class="text-muted">Haz clic en "Nuevo Cintillo" para crear el primero</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab PopUp Lideres -->
<div class="tab-pane fade" id="popup-lideres" role="tabpanel" aria-labelledby="popup-lideres-tab">
    <div class="card card-body mt-3">
        <!-- Header con botón crear y estadísticas -->
        <div class="row mb-3">
            <div class="col-md-4">
                <button type="button" class="btn btn-primary w-100" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalNuevoPopupLider">
                    <i class="fas fa-star me-2"></i>Nuevo PopUp Lider
                </button>
            </div>
            <div class="col-md-8">
                <div class="d-flex justify-content-end align-items-center">
                    <span class="badge badge-info me-2">{{ count($popup_lideres) }} PopUps Lideres</span>
                    <small class="text-muted">Contenido especializado para distribuidores líderes</small>
                </div>
            </div>
        </div>
        
        <!-- Lista de PopUp Lideres en cards -->
        <div class="row">
            @forelse ($popup_lideres as $popup_lider)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 {{ $popup_lider->estado == 'borrador' ? 'border-warning' : 'border-success' }}">
                        @if (!empty($popup_lider->imagen))
                            <img src="{{ asset('img/publicaciones/' . $popup_lider->imagen) }}" 
                                 class="card-img-top" 
                                 alt="{{ $popup_lider->titulo }}" 
                                 style="height: 200px; object-fit: cover;">
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $popup_lider->titulo }}</h5>
                                <span class="badge {{ $popup_lider->estado == 'publicado' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($popup_lider->estado) }}
                                </span>
                            </div>
                            
                            <div class="card-text flex-grow-1 mb-3">
                                <p class="mb-2">{{ Str::limit($popup_lider->resumen, 120) }}</p>
                            </div>
                            
                            <!-- Información de distribuidores -->
                            <div class="mb-3 p-2 bg-light rounded">
                                <div class="row text-center">
                                    <div class="col-12">
                                        <i class="fas fa-users text-primary me-2"></i>
                                        <strong>{{ count($popup_lider->distribuidores) }}</strong>
                                        <small class="text-muted">
                                            {{ count($popup_lider->distribuidores) == 1 ? 'distribuidor' : 'distribuidores' }}
                                        </small>
                                    </div>
                                </div>
                                
                                @if(count($popup_lider->distribuidores) > 0)
                                    <div class="mt-2">
                                        <small class="text-muted d-block">Asignado a:</small>
                                        <div class="small">
                                            @foreach($distribuidores as $distribuidor)
                                                @if(in_array($distribuidor->distribuidor_id, $popup_lider->distribuidores))
                                                    <span class="badge badge-outline-primary me-1 mb-1">{{ $distribuidor->distribuidor_nombre }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Botón si existe -->
                            @if(!empty($popup_lider->texto_boton))
                                <div class="mb-3">
                                    <button class="btn btn-outline-dark btn-sm w-100">
                                        {{ $popup_lider->texto_boton }}
                                    </button>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Acciones -->
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalEditarPopupLider{{ $popup_lider->id }}">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </button>
                                <form action="{{ route('popup_lider.destroy', $popup_lider->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar este PopUp Lider?')">
                                        <i class="fas fa-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Editar PopUp Lider -->
                <div class="modal fade" id="modalEditarPopupLider{{ $popup_lider->id }}" tabindex="-1" aria-labelledby="modalEditarLabel{{ $popup_lider->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditarLabel{{ $popup_lider->id }}">
                                    <i class="fas fa-edit me-2"></i>Editar: {{ $popup_lider->titulo }}
                                </h5>
                                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('popup_lider.update') }}" method="POST" enctype="multipart/form-data" id="formEditPopupLider{{ $popup_lider->id }}">
                                    @csrf
                                    <input type="hidden" name="IdCuenta" value="{{ $cuenta->id }}">
                                    <input type="hidden" name="IdTemporada" value="{{ $temporada->id }}">
                                    <input type="hidden" name="Identificador" value="{{ $popup_lider->id }}">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="Titulo" class="form-label">Título</label>
                                                <input type="text" class="form-control" name="Titulo" value="{{ $popup_lider->titulo }}" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="Resumen" class="form-label">Resumen</label>
                                                <textarea name="Resumen" class="form-control" rows="6">{{ $popup_lider->resumen }}</textarea>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="Distribuidores" class="form-label">Distribuidores</label>
                                                <select multiple class="form-control" name="Distribuidores[]" style="height: 150px;">
                                                    @foreach($distribuidores as $distribuidor)
                                                        <option value="{{ $distribuidor->distribuidor_id }}" 
                                                            {{ in_array($distribuidor->distribuidor_id, $popup_lider->distribuidores) ? 'selected' : '' }}>
                                                            {{ $distribuidor->distribuidor_nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Mantén presionado Ctrl (Cmd en Mac) para seleccionar múltiples opciones</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            @if (!empty($popup_lider->imagen))
                                                <div class="mb-3 text-center">
                                                    <img src="{{ asset('img/publicaciones/' . $popup_lider->imagen) }}" 
                                                        alt="Imagen actual" 
                                                        class="img-fluid rounded" 
                                                        style="max-height: 150px;">
                                                    <small class="d-block text-muted mt-1">Imagen actual</small>
                                                </div>
                                            @endif
                                            <div class="form-group mb-3">
                                                <label for="Imagen" class="form-label">Nueva imagen</label>
                                                <input type="file" class="form-control" name="Imagen" accept="image/*">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="TextoBoton" class="form-label">Texto del Botón</label>
                                                <input type="text" class="form-control" name="TextoBoton" value="{{ $popup_lider->texto_boton }}">
                                            </div>
                                            
                                            <!-- NUEVO: Opciones para enlace en edición -->
                                            <div class="form-group mb-3">
                                                <label class="form-label">Enlace del Botón</label>
                                                
                                                <!-- Mostrar enlace actual si existe -->
                                                @if(!empty($popup_lider->enlace_boton))
                                                    <div class="alert alert-info p-2 mb-2">
                                                        <small><strong>Enlace actual:</strong></small>
                                                        <div class="small text-break">
                                                            {{ Str::limit($popup_lider->enlace_boton, 50) }}
                                                            @if(Str::startsWith($popup_lider->enlace_boton, 'https://system.panduitlatam.com/archivos/descargas/'))
                                                                <span class="badge badge-primary ms-1">Archivo subido</span>
                                                            @else
                                                                <span class="badge badge-secondary ms-1">URL externa</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <!-- Tabs para elegir tipo de enlace - CORREGIDO PARA MDB -->
                                                <ul class="nav nav-pills nav-sm mb-2" id="enlaceTabsEdit{{ $popup_lider->id }}" role="tablist" data-mdb-pills-init>
                                                    <li class="nav-item" role="presentation">
                                                        <a class="nav-link active" 
                                                        id="url-tab-edit{{ $popup_lider->id }}" 
                                                        data-mdb-pill-init 
                                                        href="#url-content-edit{{ $popup_lider->id }}" 
                                                        role="tab" 
                                                        aria-controls="url-content-edit{{ $popup_lider->id }}"
                                                        aria-selected="true">URL</a>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <a class="nav-link" 
                                                        id="archivo-tab-edit{{ $popup_lider->id }}" 
                                                        data-mdb-pill-init 
                                                        href="#archivo-content-edit{{ $popup_lider->id }}" 
                                                        role="tab"
                                                        aria-controls="archivo-content-edit{{ $popup_lider->id }}"
                                                        aria-selected="false">Nuevo Archivo</a>
                                                    </li>
                                                </ul>
                                                
                                                <div class="tab-content">
                                                    <!-- Pestaña URL manual -->
                                                    <div class="tab-pane fade show active" id="url-content-edit{{ $popup_lider->id }}" role="tabpanel" aria-labelledby="url-tab-edit{{ $popup_lider->id }}">
                                                        <input type="url" class="form-control" name="EnlaceBoton" value="{{ $popup_lider->enlace_boton }}" placeholder="https://ejemplo.com/archivo.pdf">
                                                        <small class="text-muted">Modifica la URL o mantén la actual</small>
                                                    </div>
                                                    
                                                    <!-- Pestaña subida de archivo -->
                                                    <div class="tab-pane fade" id="archivo-content-edit{{ $popup_lider->id }}" role="tabpanel" aria-labelledby="archivo-tab-edit{{ $popup_lider->id }}">
                                                        <input type="file" class="form-control" name="ArchivoDescarga" 
                                                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt">
                                                        <small class="text-muted">Sube un nuevo archivo (reemplazará el enlace actual)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="Estado" class="form-label">Estado</label>
                                                <select class="form-control" name="Estado">
                                                    <option value="borrador" {{ $popup_lider->estado == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                                    <option value="publicado" {{ $popup_lider->estado == 'publicado' ? 'selected' : '' }}>Publicado</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Vista previa del estado -->
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle me-2"></i>Estado actual</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>{{ ucfirst($popup_lider->estado) }}</span>
                                                    <span class="badge {{ $popup_lider->estado == 'publicado' ? 'badge-success' : 'badge-warning' }}">
                                                        {{ $popup_lider->estado == 'publicado' ? 'Visible' : 'Oculto' }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Información adicional -->
                                            <div class="card bg-light">
                                                <div class="card-body p-3">
                                                    <h6 class="card-title">Información</h6>
                                                    <ul class="list-unstyled small mb-0">
                                                        <li><strong>Distribuidores:</strong> {{ count($popup_lider->distribuidores) }}</li>
                                                        <li><strong>Creado:</strong> {{ $popup_lider->created_at->format('d/m/Y') }}</li>
                                                        <li><strong>Actualizado:</strong> {{ $popup_lider->updated_at->format('d/m/Y') }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary" form="formEditPopupLider{{ $popup_lider->id }}">
                                            <i class="fas fa-save me-2"></i>Actualizar
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay PopUps Lideres creados</h5>
                        <p class="text-muted">Crea contenido especializado para tus distribuidores líderes</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Nuevo PopUp Lider -->
<div class="modal fade" id="modalNuevoPopupLider" tabindex="-1" aria-labelledby="modalNuevoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoLabel">
                    <i class="fas fa-star me-2"></i>Nuevo PopUp Lider
                </h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('popup_lider.create') }}" method="POST" enctype="multipart/form-data" id="formNuevoPopupLider">
                    @csrf
                    <input type="hidden" name="IdCuenta" value="{{ $cuenta->id }}">
                    <input type="hidden" name="IdTemporada" value="{{ $temporada->id }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="Titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" name="Titulo" required placeholder="Título del PopUp Lider">
                            </div>
                            <div class="form-group mb-3">
                                <label for="Resumen" class="form-label">Resumen</label>
                                <textarea name="Resumen" class="form-control" rows="6" placeholder="Describe el contenido y propósito del popup"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="Distribuidores" class="form-label">Distribuidores</label>
                                <select multiple class="form-control" name="Distribuidores[]" style="height: 150px;">
                                    @foreach($distribuidores as $distribuidor)
                                        <option value="{{ $distribuidor->distribuidor_id }}">
                                            {{ $distribuidor->distribuidor_nombre }} 
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Mantén presionado Ctrl (Cmd en Mac) para seleccionar múltiples opciones</small>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="Imagen" class="form-label">Imagen</label>
                                <input type="file" class="form-control" name="Imagen" accept="image/*">
                            </div>
                            <div class="form-group mb-3">
                                <label for="TextoBoton" class="form-label">Texto del Botón</label>
                                <input type="text" class="form-control" name="TextoBoton" placeholder="Ej: Ver detalles">
                            </div>
                            
                            <!-- NUEVO: Opciones para enlace -->
                            <div class="form-group mb-3">
                                <label class="form-label">Enlace del Botón</label>
                                
                                <!-- Tabs para elegir tipo de enlace -->
                                <ul class="nav nav-pills nav-sm mb-2" id="enlaceTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="url-tab" data-mdb-tab-init href="#url-content" role="tab">URL</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="archivo-tab" data-mdb-tab-init href="#archivo-content" role="tab">Archivo</a>
                                    </li>
                                </ul>
                                
                                <div class="tab-content">
                                    <!-- Pestaña URL manual -->
                                    <div class="tab-pane fade show active" id="url-content" role="tabpanel">
                                        <input type="url" class="form-control" name="EnlaceBoton" placeholder="https://ejemplo.com/archivo.pdf">
                                        <small class="text-muted">Ingresa una URL externa</small>
                                    </div>
                                    
                                    <!-- Pestaña subida de archivo -->
                                    <div class="tab-pane fade" id="archivo-content" role="tabpanel">
                                        <input type="file" class="form-control" name="ArchivoDescarga" 
                                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt">
                                        <small class="text-muted">Sube un archivo (máx. 10MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="Estado" class="form-label">Estado</label>
                                <select class="form-control" name="Estado">
                                    <option value="borrador" selected>Borrador</option>
                                    <option value="publicado">Publicado</option>
                                </select>
                            </div>
                            
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-lightbulb me-2"></i>Consejos</h6>
                                <ul class="small mb-0">
                                    <li>Usa "Borrador" para revisar antes de publicar</li>
                                    <li>Puedes usar URL externa o subir archivo</li>
                                    <li>Si subes archivo, se generará la URL automáticamente</li>
                                </ul>
                            </div>
                            
                            <!-- NUEVO: Información sobre tipos de archivo -->
                            <div class="card bg-light mt-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title">Archivos soportados</h6>
                                    <div class="small">
                                        <span class="badge badge-outline-primary me-1">PDF</span>
                                        <span class="badge badge-outline-primary me-1">DOC</span>
                                        <span class="badge badge-outline-primary me-1">XLS</span>
                                        <span class="badge badge-outline-primary me-1">PPT</span>
                                        <span class="badge badge-outline-primary me-1">ZIP</span>
                                        <span class="badge badge-outline-primary">TXT</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="formNuevoPopupLider">
                    <i class="fas fa-save me-2"></i>Crear PopUp Lider
                </button>
            </div>
        </div>
    </div>
</div>
<!-- JavaScript para manejar las pestañas y validación -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar cambio de pestañas - limpiar el campo opuesto
    document.querySelectorAll('[data-bs-toggle="pill"]').forEach(function(tab) {
        tab.addEventListener('shown.bs.tab', function(e) {
            const target = e.target.getAttribute('data-bs-target');
            const form = e.target.closest('form');
            
            if (target.includes('url-content')) {
                // Si selecciona URL, limpiar el campo de archivo
                const archivoInput = form.querySelector('input[name="ArchivoDescarga"]');
                if (archivoInput) archivoInput.value = '';
            } else if (target.includes('archivo-content')) {
                // Si selecciona archivo, limpiar el campo de URL
                const urlInput = form.querySelector('input[name="EnlaceBoton"]');
                if (urlInput) urlInput.value = '';
            }
        });
    });
    
    // Validar que al menos uno de los campos esté lleno antes de enviar
    document.querySelectorAll('form[id^="formNuevoPopupLider"], form[id^="formEditPopupLider"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const urlInput = form.querySelector('input[name="EnlaceBoton"]');
            const archivoInput = form.querySelector('input[name="ArchivoDescarga"]');
            const textoBoton = form.querySelector('input[name="TextoBoton"]');
            
            // Solo validar si hay texto de botón
            if (textoBoton && textoBoton.value.trim() !== '') {
                const hasUrl = urlInput && urlInput.value.trim() !== '';
                const hasFile = archivoInput && archivoInput.files.length > 0;
                
                if (!hasUrl && !hasFile) {
                    e.preventDefault();
                    alert('Si especificas texto para el botón, debes proporcionar una URL o subir un archivo.');
                    return false;
                }
            }
        });
    });
    
    // Detectar cuando se sube un archivo y cambiar automáticamente a esa pestaña
    document.querySelectorAll('input[name="ArchivoDescarga"]').forEach(function(input) {
        input.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                // Encontrar la pestaña de URL en el mismo formulario y limpiar el campo
                const form = e.target.closest('form');
                const urlInput = form.querySelector('input[name="EnlaceBoton"]');
                if (urlInput) {
                    urlInput.value = '';
                }
                
                // Mostrar mensaje informativo
                console.log('Archivo seleccionado:', e.target.files[0].name);
            }
        });
    });
    
    // Detectar cuando se escribe una URL y limpiar el archivo
    document.querySelectorAll('input[name="EnlaceBoton"]').forEach(function(input) {
        input.addEventListener('input', function(e) {
            if (e.target.value.trim() !== '') {
                // Encontrar el campo de archivo en el mismo formulario y limpiarlo
                const form = e.target.closest('form');
                const archivoInput = form.querySelector('input[name="ArchivoDescarga"]');
                if (archivoInput) {
                    archivoInput.value = '';
                }
            }
        });
    });
});
</script>
@endsection