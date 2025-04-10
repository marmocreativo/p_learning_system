@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas del sistema')

@section('contenido_principal')
    <h1>Cuentas</h1>
    <div class="row">
        <div class="col-9">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Cuentas</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-3">
            <a href="{{ route('cuentas.create') }}">Crear Cuenta</a>            
        </div>
    </div>
    <hr>
   
    <hr>
    <div class="row">
        @foreach ($cuentas as $cuenta)
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h3>{{$cuenta->nombre}} </h3>
                        <table class="table table-stripped">
                            <tbody>
                                @if ($cuenta->temporada_actual)
                                <tr>
                                    <td><a href="{{route('temporadas.show', $cuenta->temporada_actual)}}">Ver temporada activa</a></td>
                                </tr>
                                @endif
                                
                                <tr>
                                    <td><a href="{{route('temporadas', ['id_cuenta'=> $cuenta->id])}}">Ver todas las temporadas</a></td>
                                </tr>
                                <tr>
                                    <td><a href="{{route('cuentas.edit', $cuenta->id)}}">Editar cuenta</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <form action="{{route('cuentas.destroy', $cuenta->id)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{$cuentas->links()}}
@endsection