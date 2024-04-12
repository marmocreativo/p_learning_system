@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas del sistema')

@section('contenido_principal')
    <h1>Cuentas</h1>
    <hr>
    <a href="{{ route('cuentas.create') }}">Crear Cuenta</a>
    <hr>
    <div class="row">
        @foreach ($cuentas as $cuenta)
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h3>{{$cuenta->nombre}} </h3>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('temporadas', ['id_cuenta'=> $cuenta->id])}}">Ver temporadas</a>
                        <a href="{{route('cuentas.edit', $cuenta->id)}}">Editar</a>
                        <hr>
                        <form action="{{route('cuentas.destroy', $cuenta->id)}}" method="POST">
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