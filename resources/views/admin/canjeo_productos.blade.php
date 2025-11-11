@extends('plantillas/plantilla_admin')

@section('titulo', 'Canjeo Productos')

@section('contenido_principal')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Productos <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span>
        @if(request()->has('region'))
            <span class="badge badge-warning">{{ request()->get('region') }}</span>
        @endif
    </h1>
    <div class="btn-group" role="group" aria-label="Basic example">
        <a href="{{ route('canjeo.productos_crear', ['id_temporada'=>$temporada->id]) }}" class="btn btn-success">Crear Producto</a>
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
        <li class="breadcrumb-item">Ventanas de canje</li>
    </ol>
</nav>

<!-- Indicador de drag & drop -->
<div class="alert alert-info mb-3">
    <i class="fas fa-info-circle"></i> Arrastra los productos para reordenarlos
</div>

<div class="row" id="productos-sortable">
    @foreach ($productos as $producto)
    <div class="col-12 mb-3 producto-item" data-id="{{ $producto->id }}" style="cursor: move;">
        <div class="card h-100">
            <div class="row g-0 align-items-stretch">
                <!-- Icono de drag -->
                <div class="col-auto d-flex align-items-center p-3 bg-light">
                    <i class="fas fa-grip-vertical fa-2x text-muted"></i>
                </div>

                <!-- Imagen -->
                <div class="col-md-2 d-flex align-items-center p-2">
                    <img src="{{ asset('img/publicaciones/'.$producto->imagen) }}" class="img-fluid rounded-start w-100" alt="Producto">
                </div>

                <!-- Info central -->
                <div class="col-md-2 p-3">
                    <span class="badge bg-danger mb-2">{{ $producto->region }}</span>
                    <span class="badge bg-secondary mb-2">Orden: {{ $producto->orden }}</span>
                    <h5>{{ $producto->nombre }}</h5>
                    <p>{{ $producto->descripcion }}</p>
                    <h6>{{ $producto->creditos }} Créditos</h6>
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{route('canjeo.productos_editar', $producto->id)}}" class="btn btn-warning w-50">Editar</a>
                        <form action="{{route('canjeo.productos_borrar', $producto->id)}}" class="form-confirmar w-50" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger w-100">Borrar</button>
                        </form>
                    </div>
                </div>

                <!-- Tabla de cantidades -->
                <div class="col-md-6 p-3">
                    <h6>Variaciones</h6>
                    @php
                        $variaciones = is_array($producto->variaciones) ? $producto->variaciones : json_decode($producto->variaciones, true) ?? [];
                        $variaciones_cantidad = is_array($producto->variaciones_cantidad) ? $producto->variaciones_cantidad : json_decode($producto->variaciones_cantidad, true) ?? [];
                        
                        // Agrupar transacciones por id_corte obtenido de la transacción principal
                        $transaccionesPorCorte = $producto->transacciones->groupBy(function($item) {
                            return $item->transaccion ? $item->transaccion->id_corte : null;
                        });
                        
                        // Calcular conteo total
                        $conteoTotal = array_fill_keys($variaciones, 0);
                        foreach ($producto->transacciones as $transaccion) {
                            $var = $transaccion->variacion;
                            if (isset($conteoTotal[$var])) {
                                $conteoTotal[$var] += $transaccion->cantidad;
                            }
                        }
                        
                        // Obtener cortes ordenados (excluyendo null)
                        $cortesProducto = $transaccionesPorCorte->keys()->filter(function($key) { 
                            return !is_null($key); 
                        })->sort();
                    @endphp
                    
                    <div style="max-height: 300px; overflow-y: auto; overflow-x: auto;">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                <tr>
                                    <th>Var</th>
                                    <th>Disp</th>
                                    @foreach($cortes as $corte)
                                        @if($cortesProducto->contains($corte->id))
                                            <th class="text-center" style="min-width: 80px;" title="{{ $corte->titulo }}">
                                                {{ Str::limit($corte->titulo, 10) }}
                                            </th>
                                        @endif
                                    @endforeach
                                    <th>Rest</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach ($variaciones as $var)
                                    @php
                                        $disponibles = $variaciones_cantidad[$i] ?? 0;
                                        $cantidadTotal = $conteoTotal[$var] ?? 0;
                                        $restantes = $disponibles - $cantidadTotal;
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $var ?: '—' }}</strong></td>
                                        <td>{{ $disponibles }}</td>
                                        
                                        @foreach($cortes as $corte)
                                            @if($cortesProducto->contains($corte->id))
                                                @php
                                                    $cantidadCorte = 0;
                                                    if($transaccionesPorCorte->has($corte->id)) {
                                                        foreach ($transaccionesPorCorte[$corte->id] as $transaccion) {
                                                            if ($transaccion->variacion == $var) {
                                                                $cantidadCorte += $transaccion->cantidad;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <td class="text-center {{ $cantidadCorte > 0 ? 'text-primary' : 'text-muted' }}">
                                                    {{ $cantidadCorte > 0 ? $cantidadCorte : '—' }}
                                                </td>
                                            @endif
                                        @endforeach
                                        
                                        <td class="{{ $restantes < 0 ? 'text-danger fw-bold' : ($restantes == 0 ? 'text-warning' : 'text-success') }}">
                                            {{ $restantes }}
                                        </td>
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach
                                
                                @if(count($variaciones) > 0)
                                    <tr class="table-secondary fw-bold">
                                        <td>TOTAL</td>
                                        <td>{{ array_sum($variaciones_cantidad) }}</td>
                                        @foreach($cortes as $corte)
                                            @if($cortesProducto->contains($corte->id))
                                                @php
                                                    $totalCorte = 0;
                                                    if($transaccionesPorCorte->has($corte->id)) {
                                                        foreach ($transaccionesPorCorte[$corte->id] as $transaccion) {
                                                            $totalCorte += $transaccion->cantidad;
                                                        }
                                                    }
                                                @endphp
                                                <td class="text-center">{{ $totalCorte }}</td>
                                            @endif
                                        @endforeach
                                        <td>{{ array_sum($variaciones_cantidad) - array_sum($conteoTotal) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Iniciando Sortable para productos...');
    
    const productosContainer = document.getElementById('productos-sortable');
    
    if (!productosContainer) {
        console.error('No se encontró el contenedor productos-sortable');
        return;
    }
    
    console.log('Contenedor encontrado:', productosContainer);
    console.log('Sortable disponible:', typeof Sortable !== 'undefined');
    
    // Inicializar Sortable
    const sortable = new Sortable(productosContainer, {
        animation: 150,
        handle: '.producto-item',
        draggable: '.producto-item',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onStart: function(evt) {
            console.log('Comenzó el arrastre');
        },
        onEnd: function(evt) {
            console.log('Terminó el arrastre');
            
            // Obtener el nuevo orden
            const items = productosContainer.querySelectorAll('.producto-item');
            const ordenData = [];
            
            items.forEach((item, index) => {
                ordenData.push({
                    id: item.getAttribute('data-id'),
                    orden: index + 1
                });
            });
            
            console.log('Nuevo orden:', ordenData);
            
            // Enviar al servidor
            actualizarOrden(ordenData);
        }
    });
    
    console.log('Sortable inicializado correctamente');
    
    function actualizarOrden(ordenData) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        if (!csrfToken) {
            console.error('No se encontró el token CSRF');
            mostrarMensaje('Error: Token CSRF no encontrado', 'danger');
            return;
        }
        
        console.log('Enviando orden al servidor...');
        
        fetch('{{ route("canjeo.productos_actualizar_orden") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: JSON.stringify({ orden: ordenData })
        })
        .then(response => {
            console.log('Respuesta recibida:', response);
            return response.json();
        })
        .then(data => {
            console.log('Datos:', data);
            if (data.success) {
                mostrarMensaje('Orden actualizado correctamente', 'success');
            } else {
                mostrarMensaje('Error al actualizar el orden: ' + (data.error || 'Desconocido'), 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje('Error al actualizar el orden: ' + error.message, 'danger');
        });
    }
    
    function mostrarMensaje(mensaje, tipo) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alert.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 3000);
    }
});

</script>

<style>
.sortable-ghost {
    opacity: 0.4;
    background: #f8f9fa;
}

.sortable-chosen {
    cursor: grabbing !important;
}

.sortable-drag {
    opacity: 0.8;
}

.producto-item {
    cursor: grab !important;
}

.producto-item:active {
    cursor: grabbing !important;
}
</style>
@endsection