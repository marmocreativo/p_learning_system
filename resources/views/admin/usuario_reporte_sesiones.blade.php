@extends('plantillas/plantilla_admin')

@section('titulo', 'Detalles del usuario')

@section('contenido_principal')
    @if($usuario)
    <h1>Reporte del usuario: <small>{{$usuario->nombre}}</small></h1>
    <div class="row">
        <div class="col-3">
            <table class="table table-stripped">
                <tr>
                    <th>Nombre</th>
                    <td>{{$usuario->nombre}}</td>
                </tr>
                <tr>
                    <th>Apellidos</th>
                    <td>{{$usuario->apellidos}}</td>
                </tr>
                <tr>
                    <th>Correo</th>
                    <td>{{$usuario->email}}</td>
                </tr>
                <tr>
                    <th>Nivel usuario</th>
                    <td>{{$suscripcion->nivel_usuario}}</td>
                </tr>
                <tr>
                    <th>Función</th>
                    <td>{{$suscripcion->funcion}}</td>
                </tr>
                <tr>
                    <th>Nivel Distribuidor</th>
                    <td>{{$suscripcion->nombre_distribuidor}}</td>
                </tr>
                <tr>
                    <th>Temporada actual completa</th>
                    <td>{{$suscripcion->temporada_completa}}</td>
                </tr>
                <tr>
                    <th>Temporada anterior completa</th>
                    <td>{{$suscripcion->champions_a}}</td>
                </tr>
                <tr>
                    <th>University</th>
                    <td>{{$suscripcion->champions_b}}</td>
                </tr>
            </table>
        </div>
        <div class="col-9">
            <h3>Sesiones Temporada actual</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sesion</th>
                        <th>Visualización</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sesiones_actuales as $sesion_actual)
                    <tr>
                        <td>{{$sesion_actual->titulo}}</td>
                        @php
                          $visualizacion = $visualizaciones_actuales->firstWhere('id_sesion', '=', $sesion_actual->id);   
                        @endphp
                        @if ($visualizacion)
                            <td>{{$visualizacion->fecha_ultimo_video}}</td>
                        @else
                            <td>-</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
            <h3>Sesiones Temporada anterior</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sesion</th>
                        <th>Visualización</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sesiones_anteriores as $sesion)
                    <tr>
                        <td>{{$sesion->titulo}}</td>
                        @php
                          $visualizacion = $visualizaciones_anteriores->firstWhere('id_sesion', '=', $sesion->id);   
                        @endphp
                        @if ($visualizacion)
                            <td>{{$visualizacion->fecha_ultimo_video}}</td>
                        @else
                            <td>-</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <h3>Acciones</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Accion</th>
                        <th>Descripcion</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($acciones as $accion)
                    <tr>
                        <td>{{$accion->accion}}</td>
                        <td>{{$accion->descripcion}}</td>
                        <td>{{$accion->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    @endif

@endsection