@extends('plantillas/plantilla_admin')

@section('titulo', 'Resultado de Importación de SKUs')

@section('contenido_principal')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Encabezado -->
            <div class="card mb-4">
                <div class="card-header {{ $resultados['modo'] === 'cotejar' ? 'bg-info' : 'bg-primary' }} text-white">
                    <h3 class="mb-0">
                        @if($resultados['modo'] === 'cotejar')
                            <i class="fas fa-search"></i> Cotejo de SKUs (Sin Cambios)
                        @else
                            <i class="fas fa-check-circle"></i> Resultado de la Importación de SKUs
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <strong>Temporada:</strong> {{ $temporada->nombre }}<br>
                        <strong>Modo:</strong> 
                        <span class="badge {{ $resultados['modo'] === 'cotejar' ? 'bg-info' : 'bg-success' }}">
                            {{ strtoupper($resultados['modo']) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Resumen General -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center border-success">
                        <div class="card-body">
                            <h5 class="text-success">
                                <i class="fas fa-plus-circle fa-2x"></i>
                            </h5>
                            <h2 class="mb-0">{{ $resultados['agregados'] }}</h2>
                            <p class="text-muted mb-0">
                                @if($resultados['modo'] === 'cotejar')
                                    SKUs Nuevos Detectados
                                @else
                                    SKUs Agregados
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-warning">
                        <div class="card-body">
                            <h5 class="text-warning">
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </h5>
                            <h2 class="mb-0">{{ $resultados['existentes'] }}</h2>
                            <p class="text-muted mb-0">SKUs Ya Existentes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-info">
                        <div class="card-body">
                            <h5 class="text-info">
                                <i class="fas fa-file-excel fa-2x"></i>
                            </h5>
                            <h2 class="mb-0">{{ $resultados['total'] }}</h2>
                            <p class="text-muted mb-0">Total Procesadas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ NUEVO: Tabla de SKUs a procesar (Solo en modo COTEJAR) -->
            @if($resultados['modo'] === 'cotejar' && !empty($resultados['skus_a_procesar']))
            <div class="card mb-4 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> SKUs Detectados ({{ count($resultados['skus_a_procesar']) }})
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        <i class="fas fa-info-circle"></i> 
                        Los siguientes SKUs se procesarían si cambias el modo a <strong>"Actualizar"</strong>:
                    </p>
                    <div style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">Fila</th>
                                    <th>Desafío</th>
                                    <th>SKU Original</th>
                                    <th>SKU Limpio</th>
                                    <th>Descripción</th>
                                    <th style="width: 100px;" class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resultados['skus_a_procesar'] as $sku)
                                <tr class="{{ $sku['estado'] === 'existente' ? 'table-warning' : '' }}">
                                    <td class="text-center">{{ $sku['fila'] }}</td>
                                    <td>{{ $sku['desafio'] }}</td>
                                    <td><code>{{ $sku['sku_original'] }}</code></td>
                                    <td><code>{{ $sku['sku_limpio'] }}</code></td>
                                    <td>{{ $sku['descripcion'] ?: '—' }}</td>
                                    <td class="text-center">
                                        @if($sku['estado'] === 'nuevo')
                                            <span class="badge bg-success">Nuevo</span>
                                        @else
                                            <span class="badge bg-warning">Existente</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Desafíos No Encontrados -->
            @if(!empty($resultados['desafios_no_encontrados']))
            <div class="card mb-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-times-circle"></i> Desafíos No Encontrados
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        Los siguientes desafíos <strong>no existen</strong> en la temporada <strong>{{ $temporada->nombre }}</strong>:
                    </p>
                    <div class="alert alert-warning">
                        <ul class="mb-0">
                            @foreach($resultados['desafios_no_encontrados'] as $desafio)
                                <li><strong>{{ $desafio }}</strong></li>
                            @endforeach
                        </ul>
                    </div>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle"></i> 
                        Verifica que los nombres de los desafíos en el Excel coincidan exactamente con los registrados en el sistema.
                    </p>
                </div>
            </div>
            @endif

            <!-- Errores Detallados -->
            @if(!empty($resultados['errores']))
            <div class="card mb-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Errores Encontrados ({{ count($resultados['errores']) }})
                    </h5>
                </div>
                <div class="card-body">
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Descripción del Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resultados['errores'] as $index => $error)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $error }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Mensajes según modo -->
            @if($resultados['modo'] === 'cotejar')
                @if(empty($resultados['errores']))
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Cotejo completado:</strong> 
                        Se detectaron {{ $resultados['agregados'] }} SKUs nuevos y {{ $resultados['existentes'] }} ya existentes.
                        Para guardar los cambios, vuelve a subir el archivo en modo <strong>"Actualizar"</strong>.
                    </div>
                @endif
            @else
                @if(empty($resultados['errores']) && $resultados['agregados'] > 0)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> 
                        <strong>¡Importación exitosa!</strong> 
                        Se agregaron {{ $resultados['agregados'] }} SKUs correctamente.
                    </div>
                @endif

                @if(empty($resultados['errores']) && $resultados['agregados'] === 0 && $resultados['existentes'] > 0)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Información:</strong> 
                        Todos los SKUs del archivo ya existían en el sistema.
                    </div>
                @endif
            @endif

            <!-- Botones de Acción -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('logros.lista_skus', ['id_temporada' => $id_temporada]) }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> Ver Lista de SKUs
                </a>
                <a href="{{ route('temporadas.show', $id_temporada) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a la Temporada
                </a>
            </div>
        </div>
    </div>
</div>
@endsection