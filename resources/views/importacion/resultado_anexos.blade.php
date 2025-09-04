@extends('plantillas/plantilla_admin')

@section('titulo', 'Resultados de Importación')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Resultados de Importación de Anexos</h1>
        <a href="{{ route('logros.show', $id_logro) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Desafío
        </a>
    </div>

    <!-- Resumen de resultados -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ $procesados ?? 0 }}</h4>
                    <p class="card-text">Registros Procesados</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ $errores ?? 0 }}</h4>
                    <p class="card-text">Errores Encontrados</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ count($resultados) }}</h4>
                    <p class="card-text">Total de Filas</p>
                </div>
            </div>
        </div>
    </div>

    @if(isset($mensaje))
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> {{ $mensaje }}
        </div>
    @endif

    <!-- Tabla de resultados detallados -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Detalle de Procesamiento</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Fila Excel</th>
                            <th>Usuario (Email)</th>
                            <th>Folio</th>
                            <th>SKU</th>
                            <th>Estado</th>
                            <th class="text-center">Folio Creado</th>
                            <th class="text-center">SKU Creado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($resultados as $resultado)
                            @php
                                $esError = str_contains(strtolower($resultado['estado']), 'error') || 
                                          str_contains(strtolower($resultado['estado']), 'no encontrado') ||
                                          str_contains(strtolower($resultado['estado']), 'vacío');
                                $esExito = str_contains(strtolower($resultado['estado']), 'procesado correctamente');
                                $esAdvertencia = !$esError && !$esExito;
                            @endphp
                            <tr class="{{ $esError ? 'table-danger' : ($esExito ? 'table-success' : 'table-warning') }}">
                                <td class="fw-bold">{{ $resultado['fila'] }}</td>
                                <td>{{ $resultado['usuario'] }}</td>
                                <td>{{ $resultado['folio'] }}</td>
                                <td><code>{{ $resultado['sku'] }}</code></td>
                                <td>
                                    @if($esError)
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @elseif($esExito)
                                        <i class="fas fa-check-circle text-success"></i>
                                    @else
                                        <i class="fas fa-exclamation-circle text-warning"></i>
                                    @endif
                                    {{ $resultado['estado'] }}
                                </td>
                                <td class="text-center">
                                    @if($resultado['folio_creado'] == 'si')
                                        <span class="badge bg-success">Creado</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($resultado['sku_creado'] == 'si')
                                        <span class="badge bg-success">Creado</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No hay resultados para mostrar</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Resumen de errores comunes -->
    @if($errores > 0)
        <div class="card mt-4">
            <div class="card-header bg-warning">
                <h6 class="mb-0 text-dark">
                    <i class="fas fa-exclamation-triangle"></i> Errores Comunes y Soluciones
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Problemas Frecuentes:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-dot-circle text-danger"></i> <strong>Usuario no encontrado:</strong> Verifique que el email exista en el sistema</li>
                            <li><i class="fas fa-dot-circle text-danger"></i> <strong>Sin participación:</strong> El usuario debe estar registrado en este desafío</li>
                            <li><i class="fas fa-dot-circle text-danger"></i> <strong>Campos vacíos:</strong> Verifique que todas las celdas tengan datos</li>
                            <li><i class="fas fa-dot-circle text-danger"></i> <strong>Formato de fecha:</strong> Use formato dd/mm/yyyy (ej: 25/12/2024)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Columnas Requeridas en Excel:</h6>
                        <ul class="list-unstyled">
                            <li><code>email</code> - Email del usuario</li>
                            <li><code>distribuidor</code> - Nombre del distribuidor</li>
                            <li><code>folio</code> - Número de folio</li>
                            <li><code>sku</code> - Código SKU</li>
                            <li><code>cantidad</code> - Cantidad numérica</li>
                            <li><code>importe_total</code> - Importe decimal</li>
                            <li><code>emision</code> - Fecha en formato dd/mm/yyyy</li>
                            <li><code>moneda</code> - Código de moneda (opcional)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
<script>
    // Auto scroll a los errores si hay muchos
    document.addEventListener('DOMContentLoaded', function() {
        const tablaErrores = document.querySelector('.table-danger');
        if (tablaErrores) {
            tablaErrores.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endsection