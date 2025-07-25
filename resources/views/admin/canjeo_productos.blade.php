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
            <li class="breadcrumb-item">Ventanas de canje</li>
        </ol>
    </nav>
    <div class="row">
        @foreach ($productos as $producto)
        <div class="col-12">
           
                <div class="card h-100">
                    <div class="row g-0 align-items-stretch">
                        <!-- Imagen -->
                        <div class="col-md-2 d-flex align-items-center p-2">
                            <img src="{{ asset('img/publicaciones/'.$producto->imagen) }}" class="img-fluid rounded-start w-100" alt="Producto">
                        </div>

                        <!-- Info central -->
                        <div class="col-md-5 p-3">
                            <span class="badge bg-danger mb-2">{{ $producto->region }}</span>
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
                        <div class="col-md-5 p-3">
                            <h6>Variaciones</h6>
                            @php
                                $variaciones = is_array($producto->variaciones) ? $producto->variaciones : json_decode($producto->variaciones, true) ?? [];
                                $variaciones_cantidad = is_array($producto->variaciones_cantidad) ? $producto->variaciones_cantidad : json_decode($producto->variaciones_cantidad, true) ?? [];

                                $conteo = array_fill_keys($variaciones, 0);
                                foreach ($producto->transacciones as $transaccion) {
                                    $var = $transaccion->variacion;
                                    if (isset($conteo[$var])) {
                                        $conteo[$var]++;
                                    }
                                }
                            @endphp

                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Var</th>
                                        <th>Disp</th>
                                        <th>Canj</th>
                                        <th>Rest</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 0; @endphp
                                    @foreach ($conteo as $var => $cantidad)
                                        @php
                                            $disponibles = $variaciones_cantidad[$i] ?? 0;
                                            $restantes = $disponibles - $cantidad;
                                        @endphp
                                        <tr>
                                            <td>{{ $var ?: '—' }}</td>
                                            <td>{{ $disponibles }}</td>
                                            <td>{{ $cantidad }}</td>
                                            <td>{{ max($restantes, 0) }}</td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

        </div>
        @endforeach

    </div>
    
@endsection