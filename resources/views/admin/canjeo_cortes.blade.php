@extends('plantillas/plantilla_admin')

@section('titulo', 'Canjeo Cortes')

@section('contenido_principal')
    <h1>Ventanas de canjeo</h1>
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id)}}">Temporada</a></li>
                  <li class="breadcrumb-item">Ventanas de canjeo</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-9">
            @foreach ($cortes as $corte)
                <div class="card mb-3">
                    <div class="card-header">
                        <h4>{{$corte->titulo}} <small>{{$corte->fecha_publicacion_inicio}} - {{$corte->fecha_publicacion_final}}</small> </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Créditos</th>
                                    <th>Controles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cortes_usuarios as $corte_usuario)
                                @if ($corte_usuario->id_corte==$corte->id)
                                    @php
                                        $usuario = $usuarios->firstWhere('id', $corte_usuario->id_usuario);
                                    @endphp
                                    <tr>
                                        <td>{{$usuario->nombre}} {{$usuario->apellidos}}</td>
                                        <td>{{$usuario->email}}</td>
                                        <td>{{$corte_usuario->creditos}}</td>
                                        <td><a href="{{ route('canjeo.transacciones_usuario', [
                                                                                                'id_temporada'=>$corte->id_temporada,
                                                                                                'id_corte'=>$corte->id,
                                                                                                'id_usuario'=>$corte_usuario->id_usuario,
                                                                                                ])}}">Ver pedidos</a>
                                                                                                </td>
                                    </tr>
                                @endif
                                
                                @endforeach
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-3">
            <div class="card card-body bg-light">
                <form action="{{ route('canjeo.cortes_guardar') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                    @csrf
                    <h4>Crear nueva ventana</h4>
                    <div class="form-group mb-3">
                        <label for="Titulo">Título</label>
                        <input type="text" class="form-control" name="Titulo">
                    </div>
                    <div class="form-group mb-3">
                        <label>¿Contar puntos entre que fechas?</label>
                        <input type="date" class="form-control w-100 mb-3" name="FechaInicio">
                        <input type="date" class="form-control w-100 mb-3" name="FechaFinal">
                    </div>
                    <div class="form-group mb-3">
                        <label>¿Permitir canje entre que fechas?</label>
                        <input type="date" class="form-control w-100 mb-3" name="FechaPublicacionInicio">
                        <input type="date" class="form-control w-100 mb-3" name="FechaPublicacionFinal">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Crear ventana</button>
                </form>
            </div>
        </div>
    </div>
    
@endsection