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
        @foreach ($productos as $producto)
        <div class="col-3">
           
                <div class="card">
                    <div class="card-body">
                        <img class="img-fluid" src="{{ asset('img/publicaciones/'.$producto->imagen) }}" alt="Ejemplo">
                    </div>
                    <div class="card-footer">
                        <h3>{{$producto->nombre}}</h3>
                        <p>{{$producto->descripcion}}</p>
                        <h5>{{$producto->creditos}} Creditos</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Límite total</th>
                                <th>Límite x usuario</th>
                            </tr>
                            <tr>
                                <td>{{$producto->limite_total}}</td>
                                <td>{{$producto->limite_usuario}}</td>
                            </tr>
                        </table>

                        <table class="table">
                            <tr>
                                <td>
                                    <a href="{{route('canjeo.productos_editar', $producto->id)}}" class="btn btn-warning w-100">Editar</a>
                                </td>
                                <td>
                                    <form action="{{route('canjeo.productos_borrar', $producto->id)}}" class="form-confirmar" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-outline-danger w-100">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
        </div>
        @endforeach

    </div>
    
@endsection