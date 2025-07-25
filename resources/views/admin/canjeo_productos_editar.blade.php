@extends('plantillas/plantilla_admin')

@section('titulo', 'Canjeo Productos')

@section('contenido_principal')

<!-- ENCABEZADO -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">
        Crear Producto 
        <span class="badge badge-light">{{ $temporada->nombre }}</span> 
        <span class="badge badge-primary">{{ $cuenta->nombre }}</span>
    </h1>
</div>

<!-- BREADCRUMB -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item dropdown">
            <a class="dropdown-toggle text-decoration-none" href="#" id="breadcrumbDropdown" role="button" data-mdb-dropdown-init data-mdb-ripple-init>
                Cuentas
            </a>
            <ul class="dropdown-menu" aria-labelledby="breadcrumbDropdown">
                @foreach($cuentas as $cuentaItem)
                    <li><a class="dropdown-item" href="{{ route('temporadas', ['id_cuenta' => $cuentaItem->id]) }}">{{ $cuentaItem->nombre }}</a></li>
                @endforeach
            </ul>
        </li>
        <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta]) }}">Temporadas</a></li>
        <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id) }}">{{ $temporada->nombre }}</a></li>
        <li class="breadcrumb-item">Ventanas de canje</li>
    </ol>
</nav>

<div class="row">
    <form class="row" action="{{ route('canjeo.productos_actualizar', $producto->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="IdTemporada" value="{{ $producto->id_temporada }}">

        <!-- FORMULARIO IZQUIERDA -->
        <div class="col-md-8">
            <div class="form-group mb-3">
                <label>Nombre del producto</label>
                <input type="text" class="form-control" name="Nombre" value="{{ $producto->nombre }}">
            </div>

            <div class="form-group mb-3">
                <label>Descripción</label>
                <textarea name="Descripcion" class="form-control" rows="4">{{ $producto->descripcion }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label>Región</label>
                <select name="Region" class="form-control">
                    <option value="todas" {{ $producto->region == 'todas' ? 'selected' : '' }}>Todas</option>
                    <option value="México" {{ $producto->region == 'México' ? 'selected' : '' }}>México</option>
                    <option value="RoLA" {{ $producto->region == 'RoLA' ? 'selected' : '' }}>RoLA</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Descripción Larga</label>
                <textarea name="Contenido" class="form-control TextEditor" rows="5">{{ $producto->contenido }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label>Créditos</label>
                <input type="number" step="1" min="0" class="form-control" name="Creditos" value="{{ $producto->creditos }}">
            </div>

            <h5>Variaciones</h5>
            @php
                $variaciones = is_array($producto->variaciones) ? $producto->variaciones : json_decode($producto->variaciones, true) ?? [];
                $variaciones_cantidad = is_array($producto->variaciones_cantidad) ? $producto->variaciones_cantidad : json_decode($producto->variaciones_cantidad, true) ?? [];
            @endphp

            <div class="row mb-3">
                @foreach ($variaciones as $i => $var)
                <div class="col-2">
                    <input type="text" class="form-control" name="Variaciones[{{ $i }}]" value="{{ $var }}">
                </div>
                @endforeach
            </div>

            <h5 class="mb-3">Cantidades</h5>
            <div class="row mb-3">
                @foreach ($variaciones_cantidad as $i => $qty)
                <div class="col-2">
                    <input type="number" class="form-control" name="VariacionesCantidad[{{ $i }}]" value="{{ $qty }}">
                </div>
                @endforeach
            </div>

            <h5 class="mb-4">Canjeados ({{ $canjeados }})</h5>
        </div>

        <!-- FORMULARIO DERECHA -->
        <div class="col-md-4">
            <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$producto->imagen) }}" alt="Producto">
            <div class="form-group mb-3">
                <label>Cambiar Imagen</label>
                <input type="file" class="form-control" name="Imagen">
            </div>

            <input type="hidden" name="LimiteTotal" value="{{ $producto->limite_total }}">
            <input type="hidden" name="LimiteUsuario" value="{{ $producto->limite_usuario }}">

            <button type="submit" class="btn btn-primary w-100">Guardar producto</button>
        </div>
    </form>
</div>
<hr class="mt-5 mb-4">

<div class="row">
    <!-- FORMULARIO DE CARGA -->
    <div class="col-md-3">
        <form action="{{ route('canjeo.productos_galeria_guardar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="IdProducto" value="{{ $producto->id }}">
            <div class="form-group mb-3">
                <label>Subir imagen a galería</label>
                <input type="file" class="form-control" name="Imagen">
            </div>
            <button type="submit" class="btn btn-success w-100">Agregar a galería</button>
        </form>
    </div>

    <!-- GALERÍA DE IMÁGENES -->
    <div class="col-md-9">
        <div class="row" id="sortable-gallery">
            @foreach ($galeria as $item)
                <div class="col-2 mb-3" data-id="{{ $item->id }}">
                    <div class="card">
                        <img class="card-img-top" src="{{ asset('img/publicaciones/'.$item->imagen) }}" alt="Galería">
                        <div class="card-footer p-1">
                            <form action="{{ route('canjeo.productos_galeria_borrar', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link w-100 text-danger p-0">Borrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
