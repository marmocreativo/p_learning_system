@extends('plantillas/plantilla_admin')

@section('titulo', 'Canjeo Productos')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Crear Producto <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
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
            <li class="breadcrumb-item">Ventanas de canje</li>
        </ol>
    </nav>
    <form action="{{ route('canjeo.productos_guardar') }}" method="POST" enctype="multipart/form-data">
        <div class="row">
            <input type="hidden" name="IdTemporada" value="{{$_GET['id_temporada']}}">
            @csrf
            <div class="col-4">
                <div class="form-group">
                    <label for="Nombre">Nombre del producto</label>
                    <input type="text" class="form-control" name="Nombre">
                </div>
                <div class="form-group">
                    <label for="Descripcion">Descripción corta</label>
                    <textarea name="Descripcion" class="form-control" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="Region">Region</label>
                    <select name="Region" class="form-control">
                        <option value="todas">Todas</option>
                        <option value="México">México</option>
                        <option value="RoLA">RoLA</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Contenido">Descripción Larga</label>
                    <textarea name="Contenido" class="form-control TextEditor" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="Creditos">Creditos</label>
                    <input type="number" step="1" min="0" class="form-control" name="Creditos">
                </div>
                <h5 class="mt-3">Variaciones</h5>
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[0]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[1]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[2]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[3]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[4]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[5]">
                        </div>
                    </div>
                </div>
                <h5 class="mt-3">Cantidades</h5>
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" class="form-control" name="VariacionesCantidad[0]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" class="form-control" name="VariacionesCantidad[1]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" class="form-control" name="VariacionesCantidad[2]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" class="form-control" name="VariacionesCantidad[3]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" class="form-control" name="VariacionesCantidad[4]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" class="form-control" name="VariacionesCantidad[5]">
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="Imagen">Imagen</label>
                    <input type="file" class="form-control" name="Imagen" >
                </div>
                <hr>
                <div class="form-group">
                    <label for="LimiteTotal">Límite de productos en la temporada</label>
                    <input type="number" step="1" min="0" class="form-control" name="LimiteTotal" value="0">
                    <p>Dejar en 0 para ilimitado</p>
                </div>
                <div class="form-group">
                    <label for="LimiteUsuario">Límite de canje por usuario</label>
                    <input type="number" step="1" min="0" class="form-control" name="LimiteUsuario" value="0">
                    <p>Dejar en 0 para ilimitado</p>
                </div>
                <button type="submit" class="btn btn-primary w-100">Guardar</button>
            </div>
        </div>
    </form>
    
@endsection