@extends('plantillas/plantilla_admin')

@section('titulo', 'Desafios')

@section('contenido_principal')
<style>
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .progress-bar {
        border-radius: 10px;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        transition: width 0.3s ease;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .progress {
            height: 16px !important;
        }
        
        .progress-bar {
            font-size: 10px;
        }
    }
</style>
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

    <!-- Mensajes de feedback -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle"></i> Error:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-check-circle"></i> Éxito:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-times-circle"></i> Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle"></i> Advertencia:</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12 col-lg-4">
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
        
        <div class="col-12 col-lg-8">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs mb-3" id="desafioTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="participaciones-tab" data-mdb-tab-init data-mdb-target="#participaciones" type="button" role="tab" aria-controls="participaciones" aria-selected="true">
                        <i class="fas fa-users me-2"></i>Participaciones
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="skus-tab" data-mdb-tab-init data-mdb-target="#skus" type="button" role="tab" aria-controls="skus" aria-selected="false">
                        <i class="fas fa-barcode me-2"></i>SKUs
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reporte-tab" data-mdb-tab-init data-mdb-target="#reporte" type="button" role="tab" aria-controls="reporte" aria-selected="false">
                        <i class="fas fa-file-excel me-2"></i>Reporte
                    </button>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content" id="desafioTabContent">
                <!-- Pestaña Participaciones -->
                <div class="tab-pane fade show active" id="participaciones" role="tabpanel" aria-labelledby="participaciones-tab">
                    <div class="card card-body">
                        <h4>Participaciones</h4>
                        <hr>

                        <!-- Contenedor con altura fija y scroll -->
                        <div style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th style="width: 200px;">Usuario</th>
                                        <th style="width: 150px;">Distribuidor</th>
                                        <th style="width: 80px;" class="text-center">Archivos</th>
                                        <th style="width: 250px;">Progreso</th>
                                        <th style="width: 120px;" class="text-center">Estado</th>
                                        <th style="width: 100px;">Fecha</th>
                                        <th style="width: 80px;" class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($logro->participaciones->isEmpty())
                                        <tr><td colspan="8" class="text-center text-muted py-4">No hay participaciones disponibles.</td></tr>
                                    @else
                                        @foreach ($logro->participaciones as $participacion)
                                            @php
                                                // Calcular progreso total
                                                $niveles = [
                                                    'A' => $participacion->confirmacion_nivel_a,
                                                    'B' => $participacion->confirmacion_nivel_b, 
                                                    'C' => $participacion->confirmacion_nivel_c,
                                                    'ES' => $participacion->confirmacion_nivel_especial
                                                ];
                                                
                                                $nivelesCompletados = 0;
                                                $ultimoNivel = '';
                                                
                                                foreach($niveles as $nivel => $valor) {
                                                    if($valor == "si" ) {
                                                        $nivelesCompletados++;
                                                        $ultimoNivel = $nivel;
                                                    }
                                                }
                                                
                                                $porcentajeProgreso = ($nivelesCompletados / 4) * 100;
                                                
                                                // Determinar color del progreso
                                                $colorProgreso = 'bg-secondary';
                                                if($porcentajeProgreso >= 75) $colorProgreso = 'bg-success';
                                                elseif($porcentajeProgreso >= 50) $colorProgreso = 'bg-info';  
                                                elseif($porcentajeProgreso >= 25) $colorProgreso = 'bg-warning';
                                                
                                                // Colores de estado
                                                $colorEstado = 'secondary';
                                                $textoEstado = ucfirst($participacion->estado);
                                                
                                                switch(strtolower($participacion->estado)) {
                                                    case 'participante':
                                                        $colorEstado = 'success';
                                                        break;
                                                    case 'validando':
                                                        $colorEstado = 'warning';
                                                        break;
                                                    case 'finalizado':
                                                        $colorEstado = 'info';
                                                        break;
                                                }
                                            @endphp
                                            <tr>
                                                <td class="fw-bold">{{ $participacion->id }}</td>
                                                <td>
                                                    <a href="{{ route('logros.detalles_participacion', ['id' => $participacion->id]) }}" class="text-decoration-none">
                                                        <div class="fw-semibold">{{ $participacion->usuario->nombre ?? '—' }}</div>
                                                        <small class="text-muted">{{ $participacion->usuario->apellidos ?? '' }}</small><br>
                                                        <small class="text-muted">{{ $participacion->usuario->email ?? '' }}</small>
                                                    </a>
                                                </td>
                                                <td>
                                                    <small>{{ Str::limit($participacion->distribuidor->nombre ?? '—', 20) }}</small>
                                                </td>
                                                <td class="text-center">
                                                    @if($participacion->anexosNoValidados->count() > 0)
                                                        <span class="badge bg-warning text-dark">{{ $participacion->anexosNoValidados->count() }}</span>
                                                    @else
                                                        <span class="text-muted">0</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                            <div class="progress-bar {{ $colorProgreso }}" role="progressbar" 
                                                                style="width: {{ $porcentajeProgreso }}%" 
                                                                aria-valuenow="{{ $porcentajeProgreso }}" 
                                                                aria-valuemin="0" 
                                                                aria-valuemax="100">
                                                                <small class="fw-bold">{{ number_format($porcentajeProgreso, 0) }}%</small>
                                                            </div>
                                                        </div>
                                                        @if($ultimoNivel)
                                                            <small class="text-muted fw-bold">{{ $ultimoNivel }}</small>
                                                        @endif
                                                    </div>
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            @foreach($niveles as $nivel => $valor)
                                                                <span class="me-1 {{ $valor == 'si' ? 'text-success fw-bold' : 'text-muted' }}">
                                                                    {{ $nivel }}:{{ $valor }}
                                                                </span>
                                                            @endforeach
                                                        </small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-{{ $colorEstado }}">
                                                        {{ $textoEstado }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>{{ \Carbon\Carbon::parse($participacion->fecha_registro)->format('d/m/Y') }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('logros.destroy_participacion', $participacion->id) }}" 
                                                        class="form-confirmar d-inline" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                                title="Eliminar participación">
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

                        <!-- Formulario para carga de Anexos -->
                        <div class="p-4 mt-4 bg-light rounded">
                            <h6 class="mb-3"><i class="fas fa-file-upload"></i> Subir Anexos</h6>
                            
                            <form action="{{ route('logros.subir_anexos') }}" method="POST" class="row g-3 align-items-end" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id_temporada" value="{{ $logro->id_temporada }}">
                                <input type="hidden" name="id_logro" value="{{ $logro->id }}">

                                <div class="col-10">
                                    <div class="form-group">
                                        <label for="file" class="form-label">Archivo Excel</label>
                                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx" required>
                                        <div class="form-text">Solo archivos Excel (.xlsx). Debe contener: email, distribuidor, folio, sku, cantidad, importe_total, emision</div>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <button type="submit" class="btn btn-info w-100">
                                        <i class="fas fa-upload me-2"></i>Subir archivo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Pestaña SKUs -->
                <div class="tab-pane fade" id="skus" role="tabpanel" aria-labelledby="skus-tab">
                    <div class="card card-body">
                        <h5>SKUs</h5>

                        <!-- Formulario de búsqueda -->
                        <form method="GET" action="{{route('logros.show', $logro->id)}}" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="busqueda" class="form-control" placeholder="Buscar SKU" value="{{ request('busqueda') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Tabla en contenedor scrolleable -->
                                <div style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-striped table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>SKU</th>
                                                <th>SKU LIMPIO</th>
                                                <th>Descripción</th>
                                                <th>Controles</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($skus as $sku)
                                                <tr>
                                                    <td><strong>{{ $sku->sku }}</strong></td>
                                                    <td><strong>{{ $sku->sku_clean }}</strong></td>
                                                    <td>{{ $sku->descripcion ?? '—' }}</td>
                                                    <td>
                                                        <!-- Botón de borrar -->
                                                        <form method="POST" action="{{route('logros.borrar_sku')}}" onsubmit="return confirm('¿Estás seguro?');" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id" value="{{$sku->id}}">
                                                            <button class="btn btn-danger btn-sm">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">Sin resultados</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mb-3">
                                    <a href="{{route('logros.descargar_sku', ['id_logro' => $logro->id])}}" class="btn btn-success">
                                        <i class="fas fa-download"></i> Descargar Lista SKUs
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <!-- Formulario para agregar SKU -->
                                <div class="border border-dashed rounded p-3 mb-3">
                                    <h6 class="mb-3"><i class="fas fa-plus-circle"></i> Agregar SKU Individual</h6>
                                    <form method="POST" action="{{route('logros.agregar_sku')}}">
                                        <input type="hidden" name="id_logro" value='{{$logro->id}}'>
                                        <input type="hidden" name="desafio" value='{{$logro->nombre}}'>
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label class="form-label">Nuevo SKU</label>
                                            <input type="text" name="sku" class="form-control" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Descripción</label>
                                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                                        </div>
                                        <button class="btn btn-success w-100" type="submit">
                                            <i class="fas fa-plus"></i> Agregar
                                        </button>
                                    </form>
                                </div>

                                <!-- Formulario para carga masiva -->
                                <div class="border border-dashed rounded p-3">
                                    <h6 class="mb-3"><i class="fas fa-file-excel"></i> Carga Masiva</h6>
                                    <form method="POST" action="{{route('sku_masivo')}}" enctype="multipart/form-data">
                                        <input type="hidden" name="id_logro" value="{{$logro->id}}">
                                        <input type="hidden" name="id_temporada" value="{{$logro->id_temporada}}">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña Reporte -->
                <div class="tab-pane fade" id="reporte" role="tabpanel" aria-labelledby="reporte-tab">
                    <div class="card card-body">
                        <h5><i class="fas fa-chart-line"></i> Generar Reporte</h5>
                        <hr>
                        
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <form action="{{ route('logros.reporte') }}" method="GET" class="row g-3 align-items-end form_pesado">
                                    <input type="hidden" name="id_temporada" value="{{ $logro->id_temporada }}">
                                    <input type="hidden" name="id_logro" value="{{ $logro->id }}">

                                    <div class="col-12">
                                        <label for="region" class="form-label">Selecciona una región</label>
                                        <select name="region" id="region" class="form-select" required>
                                            <option value="">-- Selecciona una región --</option>
                                            <option value="México">México</option>
                                            <option value="RoLA">RoLA</option>
                                            <option value="Interna">Interna</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success btn-lg w-100">
                                            <i class="fas fa-file-excel me-2"></i>Descargar Reporte Excel
                                        </button>
                                    </div>
                                </form>
                                
                                <div class="mt-4 p-3 bg-light rounded">
                                    <h6><i class="fas fa-info-circle"></i> Información del Reporte</h6>
                                    <p class="mb-0 small text-muted">
                                        El reporte incluirá todas las participaciones del desafío "<strong>{{ $logro->nombre }}</strong>" 
                                        filtradas por la región seleccionada. El archivo Excel contendrá información detallada 
                                        de usuarios, distribuidores, progreso y estado de participaciones.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    // Confirmación para eliminar participaciones
    document.querySelectorAll('.form-confirmar').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta participación?')) {
                e.preventDefault();
            }
        });
    });

    // Mantener la pestaña activa después de envío de formularios
    document.addEventListener('DOMContentLoaded', function() {
        // Si hay un parámetro de búsqueda, activar la pestaña SKUs
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('busqueda')) {
            const skusTab = new bootstrap.Tab(document.getElementById('skus-tab'));
            skusTab.show();
        }
    });

    // Auto-ocultar alertas después de 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endsection