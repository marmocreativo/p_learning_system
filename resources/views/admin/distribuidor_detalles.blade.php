@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Detalles del distribuidor: <small>{{$distribuidor->nombre}}</small></h1>
    <div class="row">
        <div class="col-3">
            <table class="table table-stripped">
                <tr>
                    <td>Nombre</td>
                    <td>{{$distribuidor->nombre}}</td>
                </tr>
                <tr>
                    <td>Pais</td>
                    <td>{{$distribuidor->pais}}</td>
                </tr>
                <tr>
                    <td>Region</td>
                    <td>{{$distribuidor->region}}</td>
                </tr>
                <tr>
                    <td>Nivel</td>
                    <td>{{$distribuidor->nivel}}</td>
                </tr>
                <tr>
                    <td>Estado</td>
                    <td>{{$distribuidor->estado}}</td>
                </tr>
        
            </table>
        </div>
        <div class="col-4">
            <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$distribuidor->imagen) }}" alt="Imagen fondo PL">
            @if ($distribuidor->imagen_fondo_a!='fondo_distribuidor_default.png')
                <hr>
                <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$distribuidor->imagen_fondo_a) }}" alt="Imagen fondo PL">
            @endif
            @if ($distribuidor->imagen_fondo_b!='fondo_distribuidor_default.png')
                <hr>
                <img class="img-fluid mb-3" src="{{ asset('img/publicaciones/'.$distribuidor->imagen_fondo_b) }}" alt="Imagen fondo PL">
            @endif
            
            
        </div>
        <div class="col-5">
            <div class="card card-body">
                <h5>Sucursales</h5>
                <hr>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sucursal</th>
                            <th>Controles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sucursales as $sucursal)
                            <tr>
                                <td>{{ $sucursal->nombre }}</td>
                                <td>
                                    <form action="{{route('distribuidores.borrar_sucursal', $sucursal->id)}}" class="form-confirmar" method="POST">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" name="IdDistribuidor" value="{{$distribuidor->id}}">
                                        <button type="submit" class="btn btn-link">Borrar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <form action="{{ route('distribuidores.crear_sucursal') }}" method="POST">
                    @csrf
                    <input type="hidden" name="IdDistribuidor" value="{{$distribuidor->id}}">
                    <div class="d-flex">
                        <div class="form-group">
                            <input type="text" class="form-control" name="Nombre" placeholder="Nombre sucursal">
                        </div>
                        <button class="btn btn-success">Agregar</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
    
    <a href="{{ route('distribuidores') }}">Lista de distribuidores</a>

@endsection