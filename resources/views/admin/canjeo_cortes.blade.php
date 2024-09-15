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
                        <h4>
                            {{$corte->titulo}} 
                            <small>{{$corte->fecha_publicacion_inicio}} - {{$corte->fecha_publicacion_final}}</small> 
                            <!-- Botón para colapsar -->
                            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCorte{{$corte->id}}" aria-expanded="true" aria-controls="collapseCorte{{$corte->id}}">
                                Mostrar/ocultar
                            </button>
                        </h4>
                    </div>
                    <div id="collapseCorte{{$corte->id}}" class="collapse">
                        <div class="card-body">
                            <a href="{{ route('canjeo.exportar_corte', ['id_corte'=>$corte->id]) }}" download="reporte_usuarios_general.xls">Descargar EXCEL</a>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Créditos</th>
                                        <th>Pedidos</th>
                                        <th>Fecha</th>
                                        <th>Controles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cortes_usuarios as $corte_usuario)
                                        @if ($corte_usuario->id_corte == $corte->id)
                                            @php
                                                $usuario = $usuarios->firstWhere('id', $corte_usuario->id_usuario);
                                            @endphp
                                            <tr>
                                                <td>{{$usuario->nombre}} {{$usuario->apellidos}}</td>
                                                <td>{{$usuario->email}}</td>
                                                <td>{{$corte_usuario->creditos}}</td>
                                                <td>
                                                    @php
                                                        $numero_transacciones = 0;
                                                        foreach($transacciones as $transaccion){
                                                            if($transaccion->id_corte == $corte->id && $transaccion->id_usuario == $corte_usuario->id_usuario){
                                                                $numero_transacciones++;
                                                            }
                                                        }
                                                        echo $numero_transacciones;
                                                    @endphp
                                                </td>
                                                <td>{{$corte_usuario->fecha_corte}}</td>
                                                <td>
                                                    <div class="row">
                                                        
                                                        <div class="col">
                                                            <a href="{{ route('canjeo.transacciones_usuario', [
                                                                'id_temporada'=>$corte->id_temporada,
                                                                'id_corte'=>$corte->id,
                                                                'id_usuario'=>$corte_usuario->id_usuario,
                                                                ])}}" class="btn btn-sm btn-secondary w-100">Ver pedidos</a>
                                                        </div>
                                                        <div class="col">
                                                            <button type="button" class="btn btn-sm btn-warning w-100" data-bs-toggle="modal" data-bs-target="#editarCorte{{$corte_usuario->id}}">
                                                                Editar créditos
                                                            </button>
                                                        </div>
                                                        <div class="col">
                                                            <form action="{{route('canjeo.cortes_usuario_borrar', $corte_usuario->id)}}" class="form-confirmar d-inline" method="POST">
                                                                @csrf
                                                                @method('delete')
                                                                <input type="hidden" name="IdTemporada" value="{{$corte_usuario->id_temporada}}">
                                                                <button type="submit" class="btn btn-sm btn-danger w-100">Borrar corte del usuario</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- Botón y modal para editar -->
                                                    
                                                    
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="editarCorte{{$corte_usuario->id}}" tabindex="-1" aria-labelledby="editarCorte{{$corte_usuario->id}}Label" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar puntaje</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{route('canjeo.cortes_usuario_actualizar', $corte_usuario->id)}}" method="POST">
                                                                        @csrf
                                                                        @method('put')
                                                                        <input type="hidden" name="IdTemporada" value="{{$corte_usuario->id_temporada}}">
                                                                        <div class="mb-3">
                                                                            <label for="Puntaje" class="form-label">Puntaje</label>
                                                                            <input type="number" class="form-control" name="Puntaje" min="0" step="1" value="{{$corte_usuario->creditos}}">
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">Cambiar puntaje</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Ver pedidos -->
                                                    

                                                    <!-- Formulario para borrar -->
                                                    
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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