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
            
        </div>
    </div>
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