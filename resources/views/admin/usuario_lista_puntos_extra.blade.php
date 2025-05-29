@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios inscritos')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Puntos extra <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
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
            <li class="breadcrumb-item">Puntos extra</li>
        </ol>
    </nav>
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
            <form action="{{ route('puntos_extra_masivo') }}" class="d-flex" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_temporada" value="{{$_GET['id_temporada']}}">
                <div class="form-group">
                    <label for="file">Archivo de puntos extra</label>
                    <input type="file" name="file" accept=".xlsx">
                </div>
                <button type="submit" class="btn btn-primary">Cargar</button>
            </form>
        </div>
    </div>
    
    <hr>
    <table class="table table-stripped table-bordered table-sm">
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
                    <td>{{$i}} {{$suscripcion->id_suscripcion}}</td>
                    <td>{{$suscripcion->nombre_usuario}} {{$suscripcion->apellidos}}</td>
                    <td>{{$suscripcion->email}} </td>
                    <td>{{$suscripcion->nombre_distribuidor}}<br>{{$suscripcion->region}}</td>
                    <td>
                        <div class="btn-group mb-4">
                            @php
                                $total_puntos = 0;
                            @endphp
                             @foreach($suscripcion->puntos_extra as $puntos_extra)
                                 @php
                                     $total_puntos += $puntos_extra->puntos;
                                 @endphp
                             @endforeach


                            <button class="btn btn-light disabled">{{ $total_puntos }} PUNTOS</button>
                            <button class="btn btn-warning btn-sm" data-mdb-collapse-init data-mdb-ripple-init href="#form_{{$suscripcion->id_suscripcion}}">Agregar puntos extra</button>
                            <button class="btn btn-danger btn-sm" data-mdb-collapse-init data-mdb-ripple-init href="#desglose_{{$suscripcion->id_suscripcion}}">Ver todos los puntos extra</button>
                        </div>
                        <div id="form_{{$suscripcion->id_suscripcion}}" class="collapse mb-4">
                            <form class="mb-3" action="{{ route('admin_usuarios_agregar_puntos_extra') }}" method="post">
                                @csrf
                                <input type="hidden" name="IdUsuario" value="{{$suscripcion->id_usuario}}">
                                <input type="hidden" name="IdTemporada" value="{{$suscripcion->id_temporada}}">
                                <input type="hidden" name="IdCuenta" value="{{$temporada->id_cuenta}}">
                                <input type="hidden" name="Search" value="@if(isset($_GET['search'])){{ $_GET['search'] }} @endif">
                                <input type="hidden" name="Region" value="@if(isset($_GET['region'])){{ $_GET['region'] }} @endif">
                                <div class="d-flex  w-75">
                                <div class="form-group me-2">
                                    <input type="text" name="Concepto" class="form-control" placeholder="Concepto">
                                </div>
                                <div class="form-group me-2">
                                    <input type="number" step="1" name="Puntos" class="form-control" placeholder="Puntos">
                                </div>
                                <button type="submit" class="btn btn-success btn-sm">
                                    Agregar
                                </button>
                                </div>
                            </form>
                        </div>
                        <div id="desglose_{{$suscripcion->id_suscripcion}}"  class="collapse mb-4">
                            <table class="table table-sm table-bordered table-stripped">
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
                        </div>
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