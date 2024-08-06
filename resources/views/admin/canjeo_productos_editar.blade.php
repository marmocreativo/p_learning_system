@extends('plantillas/plantilla_admin')

@section('titulo', 'Canjeo Productos')

@section('contenido_principal')
    <h1>Canjeo Productos</h1>
    <div class="row">
        <div class="col-9">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id)}}">Temporada</a></li>
                  <li class="breadcrumb-item">Canjeo Productos</li>
                </ol>
            </nav>
        </div>
        <div class="col-3">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="{{ route('canjeo.productos_crear', ['id_temporada'=>$temporada->id]) }}" class="btn btn-success">Crear Producto</a>
            </div>
            
        </div>
    </div>
    
        <div class="row">
            <form class="row" action="{{ route('canjeo.productos_actualizar', $producto->id) }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="IdTemporada" value="{{$producto->id_temporada}}">
            @csrf
            @method('PUT')
            <div class="col-4">
                <div class="form-group">
                    <label for="Nombre">Nombre del producto</label>
                    <input type="text" class="form-control" name="Nombre" value="{{$producto->nombre}}">
                </div>
                <div class="form-group">
                    <label for="Descripcion">Descripción</label>
                    <textarea name="Descripcion" class="form-control" rows="5">{{$producto->descripcion}}</textarea>
                </div>
                <div class="form-group">
                    <label for="Contenido">Descripción Larga</label>
                    <textarea name="Contenido" class="form-control TextEditor" rows="5">{{$producto->descripcion}}</textarea>
                </div>
                <div class="form-group">
                    <label for="Creditos">Creditos</label>
                    <input type="number" step="1" min="0" class="form-control" name="Creditos" value="{{$producto->creditos}}">
                </div>
                <h5>Variaciones</h5>
                @php
                    $variaciones = json_decode($producto->variaciones);
                    $variaciones_cantidad = json_decode($producto->variaciones_cantidad);
                @endphp
                <div class="row mb-3">
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[0]" value="{{$variaciones[0]}}">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[1]" value="{{$variaciones[1]}}">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[2]" value="{{$variaciones[2]}}">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[3]" value="{{$variaciones[3]}}">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[4]" value="{{$variaciones[4]}}">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Variaciones[5]" value="{{$variaciones[5]}}">
                        </div>
                    </div>
                </div>
                <h5 class="mb-3">Cantidades</h5>
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" value="1" class="form-control" name="VariacionesCantidad[0]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" value="{{$variaciones_cantidad[1]}}" class="form-control" name="VariacionesCantidad[1]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" value="{{$variaciones_cantidad[2]}}" class="form-control" name="VariacionesCantidad[2]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" value="{{$variaciones_cantidad[3]}}" class="form-control" name="VariacionesCantidad[3]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" value="{{$variaciones_cantidad[4]}}" class="form-control" name="VariacionesCantidad[4]">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <input type="number" value="{{$variaciones_cantidad[5]}}" class="form-control" name="VariacionesCantidad[5]">
                        </div>
                    </div>
                </div>
                <h5 class="mt-3">Total: 5</h5>
                
            </div>
            <div class="col-4">
                <img class="img-fluid" src="{{ asset('img/publicaciones/'.$producto->imagen) }}" alt="Ejemplo">
                <hr>
                <div class="form-group">
                    <label for="Imagen">Cambiar Imagen</label>
                    <input type="file" class="form-control" name="Imagen" >
                </div>
                <hr>
                <div class="form-group">
                    <label for="LimiteTotal">Límite de productos en la temporada</label>
                    <input type="number" step="1" min="0" class="form-control" name="LimiteTotal" value="{{$producto->limite_total}}">
                    <p>Dejar en 0 para ilimitado</p>
                </div>
                <div class="form-group">
                    <label for="LimiteUsuario">Límite de canje por usuario</label>
                    <input type="number" step="1" min="0" class="form-control" name="LimiteUsuario" value="{{$producto->limite_usuario}}">
                    <p>Dejar en 0 para ilimitado</p>
                </div>
                <button type="submit" class="btn btn-primary w-100">Guardar</button>
            </div>
            </form>
            
        </div>
        <div class="row mt-4">
            <div class="col-3">
                <form class="col-4" action="{{ route('canjeo.productos_galeria_guardar') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="IdProducto" value="{{$producto->id}}">
                    @csrf
                    <div >
                       
                        
                        <hr>
                        <div class="form-group">
                            <label for="Imagen">Subir imágen Galeria</label>
                            <input type="file" class="form-control" name="Imagen" >
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                    </div>
                </form>
            </div>
            <div class="col-9">
                <div class="row">
                    @foreach ($galeria as $item)
                    <div class="col-2">
                        <img class="img-fluid" src="{{ asset('img/publicaciones/'.$item->imagen) }}" alt="Ejemplo">
                    </div>
                        
                    @endforeach
                    
                </div>
            </div>
        </div>
    
    
@endsection