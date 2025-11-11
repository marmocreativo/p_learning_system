@extends('plantillas/plantilla_admin')

@section('titulo', 'SKUs')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Lista de SKUs <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <div class="btn-group" role="group">
            <a href="{{ route('logros', ['id_temporada'=>$temporada->id]) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Desafíos
            </a>
        </div>
    </div>

    <nav aria-label="breadcrumb mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item dropdown">
                <a class="dropdown-toggle text-decoration-none" href="#" id="breadcrumbDropdown" role="button" data-mdb-dropdown-init data-mdb-ripple-init>
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
            <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id)}}">{{$temporada->nombre}}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('logros', ['id_temporada'=>$temporada->id])}}">Desafíos</a></li>
            <li class="breadcrumb-item active">SKUs</li>
        </ol>
    </nav>

    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-file-upload"></i> Carga Masiva de SKUs
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('sku_masivo')}}" enctype="multipart/form-data">
                <input type="hidden" name="id_temporada" value="{{$temporada->id}}">
                @csrf
                
                <div class="form-group mb-3">
                    <label class="form-label">Archivo Excel</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                    <div class="form-text">Formatos soportados: Excel (.xlsx, .xls)</div>
                </div>
                
                <!-- ✅ NUEVO SELECT -->
                <div class="form-group mb-3">
                    <label class="form-label">Modo de Operación</label>
                    <select name="modo" class="form-select" required>
                        <option value="cotejar" selected>Cotejar (Solo verificar)</option>
                        <option value="actualizar">Actualizar (Procesar cambios)</option>
                    </select>
                    <div class="form-text">
                        <strong>Cotejar:</strong> Solo muestra qué SKUs se agregarían sin hacer cambios.<br>
                        <strong>Actualizar:</strong> Procesa y guarda los SKUs en la base de datos.
                    </div>
                </div>
                
                <button class="btn btn-success w-100" type="submit">
                    <i class="fas fa-upload"></i> Cargar Archivo
                </button>
            </form>

            <!-- Información adicional -->
            <div class="alert alert-info mt-3 mb-0">
                <strong><i class="fas fa-lightbulb"></i> Nota:</strong> 
                El sistema buscará los desafíos por nombre dentro de la temporada <strong>{{$temporada->nombre}}</strong>. 
                Asegúrate de que los nombres coincidan exactamente.
            </div>
        </div>
    </div>

    <!-- Formulario de búsqueda -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-search"></i> Filtros de búsqueda
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('logros.lista_skus') }}" method="GET">
                <input type="hidden" name="id_temporada" value="{{$temporada->id}}">
                
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="busqueda_sku" class="form-label">Buscar por SKU o SKU Limpio</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="busqueda_sku" 
                                name="busqueda_sku" 
                                placeholder="Ej: ABC-123 o ABC123"
                                value="{{ $busqueda_sku ?? '' }}"
                            >
                        </div>
                        <small class="text-muted">Busca en ambos campos: SKU y SKU limpio</small>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="busqueda_desafio" class="form-label">Buscar por Nombre del Desafío</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-trophy"></i></span>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="busqueda_desafio" 
                                name="busqueda_desafio" 
                                list="desafios_list"
                                placeholder="Ej: Desafío Champions"
                                value="{{ $busqueda_desafio ?? '' }}"
                            >
                        </div>
                        <datalist id="desafios_list">
                            @foreach($desafios as $desafio)
                                <option value="{{ $desafio }}">
                            @endforeach
                        </datalist>
                        <small class="text-muted">Escribe para ver sugerencias</small>
                    </div>

                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            @if($busqueda_sku || $busqueda_desafio)
                                <a href="{{ route('logros.lista_skus', ['id_temporada'=>$temporada->id]) }}" class="btn btn-outline-secondary btn-block">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                @if($busqueda_sku || $busqueda_desafio)
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-filter"></i> <strong>Filtros activos:</strong>
                        @if($busqueda_sku)
                            <span class="badge bg-primary">SKU: {{ $busqueda_sku }}</span>
                        @endif
                        @if($busqueda_desafio)
                            <span class="badge bg-success">Desafío: {{ $busqueda_desafio }}</span>
                        @endif
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Información de resultados -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Total de resultados:</strong> {{ $skus->total() }} SKUs
                    @if($skus->total() > 0)
                        <span class="text-muted">
                            (mostrando {{ $skus->firstItem() }} - {{ $skus->lastItem() }})
                        </span>
                    @endif
                </div>
                <div>
                    <a href="{{ route('logros.descargar_sku', ['id_temporada'=>$temporada->id]) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de SKUs -->
    <div class="card">
        <div class="card-body p-0">
            @if($skus->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;" class="text-center">Imagen</th>
                                <th>Desafío</th>
                                <th style="width: 150px;">SKU</th>
                                <th style="width: 150px;">SKU Limpio</th>
                                <th>Descripción</th>
                                <th style="width: 120px;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($skus as $sku)
                                <tr>
                                    <td class="text-center align-middle">
                                        @if($sku->imagen_logro && $sku->imagen_logro != 'default.jpg')
                                            <img 
                                                src="{{ asset('img/publicaciones/'.$sku->imagen_logro) }}" 
                                                alt="{{ $sku->nombre_logro }}" 
                                                class="rounded shadow-sm"
                                                style="width: 50px; height: 50px; object-fit: cover;"
                                            >
                                        @else
                                            <div class="bg-secondary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-trophy text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div>
                                            <strong class="text-primary">{{ $sku->desafio }}</strong>
                                        </div>
                                        @if($sku->nombre_logro != $sku->desafio)
                                            <small class="text-muted">{{ $sku->nombre_logro }}</small>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <code class="bg-light px-2 py-1 rounded">{{ $sku->sku }}</code>
                                    </td>
                                    <td class="align-middle">
                                        <code class="bg-light px-2 py-1 rounded">{{ $sku->sku_clean }}</code>
                                    </td>
                                    <td class="align-middle">
                                        @if($sku->detalles)
                                            <span title="{{ $sku->detalles }}">
                                                {{ Str::limit($sku->detalles, 60) }}
                                            </span>
                                        @else
                                            <span class="text-muted fst-italic">Sin descripción</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a 
                                                href="{{ route('logros.show', $sku->id_logro) }}" 
                                                class="btn btn-outline-primary"
                                                title="Ver desafío"
                                                data-mdb-ripple-init
                                            >
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('logros.borrar_sku', $sku->id) }}" method="POST" class="form-confirmar d-inline">
                                                @csrf
                                                <button 
                                                    type="submit" 
                                                    class="btn btn-outline-danger"
                                                    title="Eliminar SKU"
                                                    data-mdb-ripple-init
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                    <h5 class="text-muted">
                        @if($busqueda_sku || $busqueda_desafio)
                            No se encontraron SKUs con los filtros aplicados
                        @else
                            No hay SKUs registrados en esta temporada
                        @endif
                    </h5>
                    @if($busqueda_sku || $busqueda_desafio)
                        <p class="text-muted mb-4">Intenta modificar los criterios de búsqueda</p>
                        <a href="{{ route('logros.lista_skus', ['id_temporada'=>$temporada->id]) }}" class="btn btn-primary">
                            <i class="fas fa-redo"></i> Mostrar todos los SKUs
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Paginación -->
        @if($skus->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $skus->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Scripts -->
    <script>
        // Confirmación antes de borrar
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.form-confirmar').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    if (confirm('¿Estás seguro de que deseas eliminar este SKU?\n\nEsta acción no se puede deshacer.')) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection