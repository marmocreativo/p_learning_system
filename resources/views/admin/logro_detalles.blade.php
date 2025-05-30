@extends('plantillas/plantilla_admin')

@section('titulo', 'Desafios')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Desafio: {{$logro->nombre}} <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="{{route('logros.edit', $logro->id)}}" class="btn btn-warning">Editar desafio</a>
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
            <li class="breadcrumb-item">Desafio {{$logro->nombre}}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-2">
            <div class="card card-body">
    <h5>Datos generales</h5>
    <table class="table table-bordered table-sm">
        <tr><td><strong>Nombre:</strong> {{ $logro->nombre }}</td></tr>
        <tr><td><strong>Premio en texto:</strong> {{ $logro->premio }}</td></tr>
        <tr><td><strong>Instrucciones:</strong> {{ $logro->instrucciones }}</td></tr>
        <tr><td><strong>Contenido:</strong> {{ $logro->contenido }}</td></tr>
        <tr><td><strong>Nivel A:</strong> {{ $logro->nivel_a }}<br><strong>Premio:</strong> {{ $logro->premio_a }}</td></tr>
        <tr><td><strong>Nivel B:</strong> {{ $logro->nivel_b }}<br><strong>Premio:</strong> {{ $logro->premio_b }}</td></tr>
        <tr><td><strong>Nivel C:</strong> {{ $logro->nivel_c }}<br><strong>Premio:</strong> {{ $logro->premio_c }}</td></tr>
        <tr><td><strong>Nivel Especial:</strong> {{ $logro->nivel_especial }}<br><strong>Premio:</strong> {{ $logro->premio_especial }}</td></tr>
    </table>

    <h5>Configuraciones</h5>
    <table class="table table-bordered table-sm">
        <tr>
            <td class="text-center">
                <img class="img-fluid w-50" src="{{ asset('img/publicaciones/' . $logro->imagen) }}">
            </td>
        </tr>
        <tr>
            <td class="text-center">
                <img class="img-fluid" src="{{ asset('img/publicaciones/' . $logro->imagen_fondo) }}">
            </td>
        </tr>
        <tr><td><strong>Disponible para usuarios:</strong> {{ $logro->nivel_usuario }}</td></tr>
        <tr><td><strong>Max cantidad de archivos:</strong> {{ $logro->cantidad_evidencias }}</td></tr>
        <tr><td><strong>Fecha inicio:</strong> {{ $logro->fecha_inicio }}</td></tr>
        <tr><td><strong>Fecha finalización:</strong> {{ $logro->fecha_vigente }}</td></tr>
    </table>
</div>
        </div>
        
        <div class="col-2">
    <div class="card card-body">
        <h5>Skus</h5>

        <!-- Formulario de búsqueda -->
        <form method="GET" action="{{route('logros.show', $logro->id)}}" class="mb-2">
            <div class="input-group input-group-sm">
                <input type="text" name="busqueda" class="form-control" placeholder="Buscar SKU" value="{{ request('busqueda') }}">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>

        

        <!-- Tabla en contenedor scrolleable -->
        <div style="max-height: 300px; overflow-y: auto;">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>CONTROLES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($skus as $sku)
                        <tr>
                            <td>{{ $sku->sku }}</td>
                            <td>
                                <!-- Botón de borrar -->
                                <form method="POST" action="" onsubmit="return confirm('¿Estás seguro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">Sin resultados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Formulario para agregar SKU -->
        <div class="border border-dashed p-3">
            <form method="POST" action="" class="mt-2">
                @csrf
                <div class="form-group">
                    <label>Nuevo SKU</label>
                    <input type="text" name="Sku" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="Descripcion" class="form-control" rows="3"></textarea>
                </div>
                <button class="btn btn-success w-100 mt-3" type="submit">Agregar</button>
            </form>
        </div>
    </div>
</div>

        <div class="col-8">
            <div class="card card-body">
    <h4>Participaciones</h4>
    <hr>
    <form action="{{ route('logros.reporte') }}" method="GET" class="row g-3 align-items-end my-1">
        <input type="hidden" name="id_temporada" value="{{ $logro->id_temporada }}">
        <input type="hidden" name="id_logro" value="{{ $logro->id }}">

        <div class="col-md-4">
            <label for="region" class="form-label">Selecciona una región</label>
            <select name="region" id="region" class="form-select" required>
                <option value="">-- Selecciona --</option>
                <option value="México">México</option>
                <option value="RoLA">RoLA</option>
                <option value="Interna">Interna</option>
            </select>
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-success w-100">
                Descargar EXCEL
            </button>
        </div>
    </form>
    <hr>

    <!-- Contenedor con altura fija y scroll -->
    <div style="max-height: 400px; overflow-y: auto;">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Distribuidor</th>
                    <th>Archivos a revisar</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Controles</th>
                </tr>
            </thead>
            <tbody>
                @if ($logro->participaciones->isEmpty())
                    <tr><td colspan="7" class="text-center">No hay participaciones disponibles.</td></tr>
                @else
                    @foreach ($logro->participaciones as $participacion)
                        <tr>
                            <td>{{ $participacion->id }}</td>
                            <td>
                                <a href="{{ route('logros.detalles_participacion', ['id' => $participacion->id]) }}">
                                    {{ $participacion->usuario->nombre ?? '—' }} {{ $participacion->usuario->apellidos ?? '' }}
                                </a>
                            </td>
                            <td>{{ $participacion->distribuidor->nombre ?? '—' }}</td>
                            <td>{{ $participacion->anexosNoValidados->count() }}</td>
                            <td>{{ $participacion->estado }}</td>
                            <td>{{ $participacion->fecha_registro }}</td>
                            <td>
                                <form action="{{ route('logros.destroy_participacion', $participacion->id) }}" class="form-confirmar" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

        </div>
    </div>
    <hr>
    
    

@endsection