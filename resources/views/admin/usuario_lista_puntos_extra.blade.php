@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios inscritos')

@section('contenido_principal')
    <h1>Usuarios inscritos</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('admin_usuarios_suscritos_reporte_temporada', ['id_temporada'=>$_GET['id_temporada']]) }}" download="reporte_usuarios_general.xls">Descargar EXCEL</a>
    <hr>
    <div class="row">
        <div class="col-6">
            <form class="d-flex" action="{{ route('admin_usuarios_puntos_extra') }}" method="GET">
                <input type="hidden" name="id_temporada" value="{{$_GET['id_temporada']}}">
                <div class="form-group me-2">
                    <input type="text" class="form-control" name="search" placeholder="Buscar...">
                </div>
                <div class="form-group me-2">
                <select name="region" id="" class="form-control">
                    <option value="">Cualquier región</option>
                    <option value="RoLA">RoLA</option>
                    <option value="México">México</option>
                </select>
                </div>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>
        <div class="col-6">
        </div>
    </div>
    
    <hr>
    <table class="table table-stripped">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Disty</th>
            <th>Puntos</th>
        </tr>
        <?php $i=1; ?>
        @foreach ($suscriptores as $suscripcion)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$suscripcion->nombre_usuario}} {{$suscripcion->apellidos}}</td>
                    <td>{{$suscripcion->email}} </td>
                    <td>{{$suscripcion->nombre_distribuidor}}<br>{{$suscripcion->region}}</td>
                    <td>
                        <form class="d-flex border-bottom mb-3" action="{{ route('admin_usuarios_agregar_puntos_extra') }}" method="post">
                            @csrf
                            <input type="hidden" name="IdUsuario" value="{{$suscripcion->id_usuario}}">
                            <input type="hidden" name="IdTemporada" value="{{$suscripcion->id_temporada}}">
                            <input type="hidden" name="IdCuenta" value="{{$temporada->id_cuenta}}">
                            <input type="hidden" name="Search" value="@if(isset($_GET['search'])){{ $_GET['search'] }} @endif">
                            <input type="hidden" name="Region" value="@if(isset($_GET['region'])){{ $_GET['region'] }} @endif">
                            
                            <div class="form-group me-2">
                                <input type="text" name="Concepto" class="form-control" placeholder="Concepto">
                            </div>
                            <div class="form-group me-2">
                                <input type="number" step="1" name="Puntos" class="form-control" placeholder="Puntos">
                            </div>
                            <button type="submit" class="btn btn-success btn-sm">
                                Agregar
                            </button>
                        </form>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Concepto</td>
                                    <th>Puntos</th>
                                    <th>Fecha</th>
                                    <th>Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suscripcion->puntos_extra as $puntos_extra)
                                <tr>
                                    <td>{{$puntos_extra->concepto}}</td>
                                    <td>{{$puntos_extra->puntos}}</td>
                                    <td>{{$puntos_extra->fecha_registro}}</td>
                                    <td>
                                        <form class="form-confirmar" action="{{ route('admin_usuarios_borrar_puntos_extra', $puntos_extra->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="IdUsuario" value="{{$suscripcion->id_usuario}}">
                                            <input type="hidden" name="IdTemporada" value="{{$suscripcion->id_temporada}}">
                                            <input type="hidden" name="Search" value="@if(isset($_GET['search'])){{ $_GET['search'] }} @endif">
                                            <input type="hidden" name="Region" value="@if(isset($_GET['region'])){{ $_GET['region'] }} @endif">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Borrar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php $i++; ?>
        @endforeach
    </table>
    <?php 
        $appends = array();
        $appends['id_temporada'] = $_GET['id_temporada'];
        if(isset($_GET['search']) && !empty($_GET['search'])){
            $appends['search'] = $_GET['search'];
        }
        if(isset($_GET['region']) && !empty($_GET['region'])){
            $appends['region'] = $_GET['region'];
        }
    ?>
    {{ $suscriptores->appends($appends)->links() }}
@endsection