@extends('plantillas/plantilla_admin')

@section('titulo', 'Sesiones')

@section('contenido_principal')
    <h1>Sesiónes</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('sesiones.create', ['id_temporada'=>$_GET['id_temporada']]) }}">Crear Sesión</a>
    <hr>
    <div class="row">
        @foreach ($sesiones as $sesion)
            @php
                // Obtener la fecha de hoy
                $today = \Carbon\Carbon::now()->toDateString();
                // Comparar la fecha de publicación con la fecha actual
                $isPastOrToday = $sesion->fecha_publicacion <= $today;
            @endphp
            <div class="col-3">
                <div class="card {{ $isPastOrToday ? 'bg-primary text-white' : '' }}">
                    <div class="card-body">
                        <h3 title="{{$sesion->id}}">{{$sesion->titulo}} </h3>
                        <table class="table table-stripped">
                            <tbody>
                                <tr>
                                    <td><a href="{{route('sesiones.show', $sesion->id)}}" className="btn btn-primary">Ver contenido</a></td>
                                </tr>
                                <tr>
                                    <td><a href="{{route('sesiones.resultados', $sesion->id)}}" className="btn btn-primary">Ver resultados</a></td>
                                </tr>
                                <tr>
                                    <td><a href="{{route('sesiones.resultados_excel', ['id_sesion' => $sesion->id])}}" download="reporte-{{$sesion->titulo}}" className="btn btn-primary">Resultados Excel</a></td>
                                </tr>
                                <tr>
                                    <td><a href="{{route('sesiones.edit', $sesion->id)}}" className="btn btn-primary">Editar</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <form action="{{route('sesiones.destroy', $sesion->id)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{$sesiones->links()}}
@endsection