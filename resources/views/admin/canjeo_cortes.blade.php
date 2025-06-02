@extends('plantillas/plantilla_admin')

@section('titulo', 'Canjeo Cortes')

@section('contenido_principal')
<div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Ventanas de canje <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <div class="btn-group" role="group" aria-label="Basic example">
           
        </div>
    </div>

    <nav aria-label="breadcrumb mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item dropdown">
                <a class="dropdown-toggle text-decoration-none" href="#" id="breadcrumbDropdown" role="button"  data-mdb-dropdown-init
                        data-mdb-ripple-init>
                    Cuentas
                </a>
                <ul class="dropdown-menu" aria-labelledby="breadcrumbDropdown">
                    @foreach($cuentas as $cuentaItem)
                        <li>
                            <a class="dropdown-item" href="{{ route('temporadas', ['id_cuenta' => $cuentaItem->id]) }}">
                                {{ $cuentaItem->nombre }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id)}}">{{$temporada->nombre}}</a> </li>
            <li class="breadcrumb-item">Ventanas de canje</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-8">
            @foreach ($cortes as $corte)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #dddddd">
                        <h5>{{$corte->titulo}}</h5>
                        <div>
                            <table class="table table-sm">
                                <tr>
                                    <th>Inicio: {{$corte->fecha_publicacion_inicio}} </th>
                                    <th>Fin: {{$corte->fecha_publicacion_final}} </th>
                                </tr>
                            </table>
                        </div>
                        <div class="btn-goup">
                            <button class="btn btn-warning" type="button" data-mdb-collapse-init data-mdb-ripple-init data-mdb-target="#collapseForm{{$corte->id}}" aria-expanded="true" aria-controls="collapseCorte{{$corte->id}}">
                                Editar fecha de corte
                            </button>
                            <button class="btn btn-success" type="button" data-mdb-collapse-init data-mdb-ripple-init data-mdb-target="#collapseCorte{{$corte->id}}" aria-expanded="true" aria-controls="collapseCorte{{$corte->id}}">
                                Ver lista de canjes
                            </button>
                        </div>
                        <h4>
                            
                            <!-- Botón para colapsar -->
                            
                        </h4>
                    </div>
                    <div id="collapseForm{{$corte->id}}" class="collapse">
                        <div class="card card-body">
                            <form action="{{ route('canjeo.cortes_actualizar', $corte->id) }}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                            @csrf
                            @method('put')
                            <h4>Editar ventana de canje</h4>
                            <h5 class="my-3">Título</h5>
                            <div class="form-group">
                                <input type="text" class="form-control" name="Titulo" value="{{$corte->titulo}}">
                            </div>
                            <h5 class="my-3">¿Permitir canje entre que fechas?</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="date" class="form-control w-100 mb-3" name="FechaPublicacionInicio" value="{{$corte->fecha_publicacion_inicio}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="date" class="form-control w-100 mb-3" name="FechaPublicacionFinal" value="{{$corte->fecha_publicacion_final}}">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3 w-100">Actualizar ventana</button>
                            
                        </form>
                        </div>
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
                                                            <button type="button" class="btn btn-sm btn-warning w-100" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#editarCorte{{$corte_usuario->id}}">
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
        <div class="col-3 mb-3">
            <div class="card card-body">
                <form action="{{ route('canjeo.cortes_guardar') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="IdTemporada" value="{{$temporada->id}}">
                    @csrf
                    <h4>Nueva Ventana de canje</h4>
                    <h5 class="my-3">Título</h5>
                    <div class="form-group">
                        <input type="text" class="form-control" name="Titulo">
                    </div>
                    <h5 class="my-3">¿Permitir canje entre que fechas?</h5>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="date" class="form-control w-100 mb-3" name="FechaPublicacionInicio">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <input type="date" class="form-control w-100 mb-3" name="FechaPublicacionFinal">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 w-100">Crear ventana</button>
                    
                </form>
            </div>
        </div>
        
        
    </div>
    
@endsection