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
        <div class="col-12 mb-3">
            <div class="card card-body bg-light">
                <form action="{{ route('canjeo.cortes_guardar') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                    @csrf
                    <h4>Crear nueva ventana</h4>
                    <div class="d-flex justify-">
                        <div class="flex-fill form-group mb-3 mx-3">
                            <label for="Titulo">Título</label>
                            <input type="text" class="form-control" name="Titulo">
                        </div>
                        <div class="flex-fill form-group mb-3 mx-3">
                            <label>¿Contar puntos entre que fechas?</label>
                            <input type="date" class="form-control w-100 mb-3" name="FechaInicio">
                            <input type="date" class="form-control w-100 mb-3" name="FechaFinal">
                        </div>
                        <div class="flex-fill form-group mb-3 mx-3">
                            <label>¿Permitir canje entre que fechas?</label>
                            <input type="date" class="form-control w-100 mb-3" name="FechaPublicacionInicio">
                            <input type="date" class="form-control w-100 mb-3" name="FechaPublicacionFinal">
                        </div>
                        <div class="flex-fill mx-3">
                            <button type="submit" class="btn btn-primary w-100">Crear ventana</button>
                        </div>
                        
                    </div>
                    
                </form>
            </div>
        </div>
        <div class="col-12">
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
                                        <th>Pedidos</th>
                                        
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
                                                @php
                                                $etiqueta = 'text-success';
                                                    if($corte_usuario->creditos!=$corte_usuario->puntos_al_corte){
                                                        $etiqueta = 'text-danger';
                                                    }
                                                @endphp
                                                <td>
                                                    <b>Nombre:</b>{{$usuario->nombre}} {{$usuario->apellidos}}<br>
                                                    <b>Correo:</b>{{$usuario->email}}<br>
                                                    <b>Creditos:</b><span class={{$etiqueta}}>{{$corte_usuario->creditos}}</span><br>
                                                    <b>Fecha:</b>{{$usuario->fecha_corte}}<br>
                                                </td>
                                                <td>
                                                   <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Prod.</th>
                                                                <th>Cred.</th>
                                                                <th>Dir.</th>
                                                                <th>Est.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($corte_usuario->transacciones as $transaccion)
                                                            <tr>
                                                                <td>
                                                                    @foreach ($transaccion->productos as $producto)
                                                                        <p>{{$producto->nombre}} (Cant: {{$producto->cantidad}})</p>
                                                                    @endforeach
                                                                </td>
                                                                <td>{{$transaccion->creditos}}</td>
                                                                @php
                                                                    $direccion = $transaccion->direccion_calle.', '
                                                                                    .$transaccion->direccion_numero.', '
                                                                                    .$transaccion->direccion_numeroint.', '
                                                                                    .$transaccion->direccion_colonia.', '
                                                                                    .$transaccion->direccion_municipio.', '
                                                                                    .$transaccion->direccion_ciudad.', '
                                                                                    .$transaccion->direccion_codigo_postal;
                                                                @endphp
                                                                <td>
                                                                    <p>{{$transaccion->direccion_nombre}}</p>
                                                                    <p>{{$direccion}}</p>
                                                                    <p>Tel: {{$transaccion->direccion_telefono}}</p>
                                                                    <p>Horario: {{$transaccion->direccion_horario}}</p>
                                                                    <p>Referencias: <div style="word-break: break-all; overflow-wrap: break-word;  white-space: normal;">{{$transaccion->direccion_referencia}}</div> </p>
                                                                    <p>Notas: <div style="word-break: break-all; overflow-wrap: break-word;  white-space: normal;">{{$transaccion->direccion_notas}}</div> </p>
                                                                </td>
                                                                <td>
                                                                    <p>Confirmado: {{$transaccion->confirmado}}</p>
                                                                    <p>Enviado: {{$transaccion->enviado}}</p>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            
                                                        </tbody>
                                                   </table>
                                                </td>
                                                
                                                <td>
                                                    <div class="row">
                                                        
                                                        <div class="col">
                                                            <a href="{{ route('canjeo.transacciones_usuario', [
                                                                'id_temporada'=>$corte->id_temporada,
                                                                'id_corte'=>$corte->id,
                                                                'id_usuario'=>$corte_usuario->id_usuario,
                                                                ])}}" class="btn btn-sm btn-secondary w-100"> <i class="fa fa-list"></i> </a>
                                                        </div>
                                                        <div class="col">
                                                            <button type="button" class="btn btn-sm btn-warning w-100" data-bs-toggle="modal" data-bs-target="#editarCorte{{$corte_usuario->id}}">
                                                                <i class="fa fa-pencil"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col">
                                                            <form action="{{route('canjeo.cortes_usuario_borrar', $corte_usuario->id)}}" class="form-confirmar d-inline" method="POST">
                                                                @csrf
                                                                @method('delete')
                                                                <input type="hidden" name="IdTemporada" value="{{$corte_usuario->id_temporada}}">
                                                                <button type="submit" class="btn btn-sm btn-danger w-100"><i class="fa fa-trash"></i></button>
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
        
    </div>
    
@endsection