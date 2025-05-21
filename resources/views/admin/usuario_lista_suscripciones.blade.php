@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios inscritos')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Usuarios participantes <span class="badge badge-light">{{$temporada->nombre}}</span> <span class="badge badge-primary">{{$cuenta->nombre}}</span></h1>
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="{{ route('admin_usuarios.suscripcion', ['id_temporada'=>$_GET['id_temporada']]) }}" class="btn btn-success">Inscribir usuario</a>
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
            <li class="breadcrumb-item">Participantes</li>
        </ol>
    </nav>
    <hr>
    @if ($errors->has('error'))
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
    @endif
    <div class="row">
        <div class="col-6">
            <form class="d-flex" action="{{ route('admin_usuarios_suscritos') }}" method="GET">
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
            <form action="{{ route('imp_usuarios_2025') }}" class="d-flex" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_temporada" value="{{$_GET['id_temporada']}}">
                <div class="form-group">
                    <label for="file">Suscribir usuarios</label>
                    <input type="file" name="file" accept=".xlsx">
                </div>
                <button type="submit" class="btn btn-primary">Importar</button>
            </form>
        </div>
    </div>
    
    <hr>
    <table class="table table-striped table-bordered table-sm">
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Usuario</th>
            <th>V/E</th>
            <th>Lider</th>
            <th>Disty</th>
            <th>Sucursal</th>
            <th>Region</th>
            <th>Campions</th>
            <th>TyC</th>
            <th>Pass</th>
            <th>Controles</th>
        </tr>
        <?php $i=1; ?>
        @foreach ($suscriptores as $suscripcion)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$suscripcion->id_usuario}}</td>
                    <td>{{$suscripcion->nombre_usuario}}</td>
                    <td>{{$suscripcion->apellidos}}</td>
                    <td>{{$suscripcion->email}} </td>
                    <td>{{$suscripcion->legacy_id}}</td>
                    <td>{{$suscripcion->nivel_usuario}}</td>
                    <td>{{$suscripcion->funcion}}</td>
                    <td>{{$suscripcion->nombre_distribuidor}}<hr>{{$suscripcion->nivel}}</td>
                    <td>{{$suscripcion->id_sucursal}}</td>
                    <td>{{$suscripcion->region}}</td>
                    <td>
                        {!! ($suscripcion->champions_a === 'si' && $suscripcion->champions_b === 'si') 
                            ? '<span class="badge bg-success">Habilitado</span>' 
                            : '<span class="badge bg-light">Deshabilitado</span>' !!}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($suscripcion->fecha_terminos)->translatedFormat('d \d\e F \d\e Y') }}</td>
                    
                    <td>
                        @if(!$suscripcion->pass_restaurado)
                        <form action="{{route('admin_usuarios.restaurar_pass', $suscripcion->id)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('put')
                            <input type="hidden" name="id_temporada" value='{{$_GET['id_temporada']}}'>
                            <input type="hidden" name="id_usuario" value='{{$suscripcion->id_usuario}}'>
                            <input type="hidden" name="id_distribuidor" value='{{$suscripcion->id_distribuidor}}'>
                            <button type="submit" class="btn btn-danger">Restaurar pass</button>
                        </form>
                        @endif
                        @if($suscripcion->pass_restaurado)
                        <span class="badge bg-secondary">Default</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#formulario{{$suscripcion->id_suscripcion}}">
                            Editar
                            </button>
                            <a href="{{route('admin_usuarios.reporte_sesiones', $suscripcion->id_suscripcion)}}" class="btn btn-info btn-sm">
                                Reporte
                            </a>
                            
                                <a href="{{ route('admin_usuarios.borrar_tokens', ['id' => $suscripcion->id_usuario]) }}" class="btn btn-outline-danger btn-sm">
                                    Cerrar sesión
                                </a>
                        </div>
                        
                        <form action="{{route('admin_usuarios.desuscribir', $suscripcion->id_suscripcion)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="id_temporada" value='{{$_GET['id_temporada']}}'>
                            <button type="submit" class="btn btn-danger btn-sm w-100 mt-4">Desuscribir</button>
                        </form>
                        <!-- Modal -->
                            <div class="modal fade" id="formulario{{$suscripcion->id_suscripcion}}" tabindex="-1" aria-labelledby="formulario{{$suscripcion->id}}Label" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-lg">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form action="{{ route('admin_usuarios.suscribir_full_update', $suscripcion->id_suscripcion) }}" method="POST">
                                            <input type="hidden" name="IdTemporada" value="{{$suscripcion->id_temporada}}">
                                            <input type="hidden" name="IdUsuario" value="{{$suscripcion->id_usuario}}">
                                            @method('PUT')
                                            @csrf
                                            <h6>Datos del usuario</h6>
                                            <div class="form-group">
                                                <label for="Nombre">Nombre</label>
                                                <input class="form-control" type="text" name="Nombre" value="{{$suscripcion->nombre_usuario}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="Apellidos">Apellidos</label>
                                                <input class="form-control" type="text" name="Apellidos" value="{{$suscripcion->apellidos}}">
                                            </div>
                                            <div class="form-group">
                                                <label for="Whatsapp">Whatsapp</label>
                                                <input class="form-control" type="text" name="Whatsapp" value="{{$suscripcion->whatsapp}}">
                                            </div>
                                            <hr>
                                            <h6>Datos del distribuidor</h6>
                                            <div class="form-group">
                                                <label for="IdDistribuidor">Distribuidor</label>
                                                <select class="form-control" name="IdDistribuidor">
                                                    @foreach ($distribuidores as $distribuidor)
                                                        <option value="{{ $distribuidor->id }}" @if($distribuidor->id==$suscripcion->id_distribuidor) selected @endif> {{ $distribuidor->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="NivelDistribuidor">Nivel Distribuidor ({{$suscripcion->nivel}})</label>
                                                <select name="NivelDistribuidor" class="form-control">
                                                    <option value="Oyente" @if($suscripcion->nivel=='Oyente') selected @endif>Oyente</option>
                                                    <option value="Basico" @if($suscripcion->nivel=='Basico') selected @endif>Básico</option>
                                                    <option value="Medio" @if($suscripcion->nivel=='Medio') selected @endif>Medio</option>
                                                    <option value="Completo" @if($suscripcion->nivel=='Completo') selected @endif>Completo</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="IdSucursal">Sucursal</label>
                                                <select class="form-control" name="IdSucursal">
                                                </select>
                                            </div>
                                            <hr>
                                            <h6>Datos de la suscripción</h6>
                                            <div class="form-group">
                                                <label for="NivelUsuario">Nivel Usuario</label>
                                                <select class="form-control" name="NivelUsuario">
                                                    <option value="ventas" @if($suscripcion->nivel_usuario=='ventas') selected @endif> Ventas</option>
                                                    <option value="especialista" @if($suscripcion->nivel_usuario=='especialista') selected @endif> Especialista</option>
                                                </select>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="Funcion">Función</label>
                                                        <select class="form-control" name="Funcion">
                                                            <option value="usuario" @if($suscripcion->funcion=='usuario') selected @endif> Usuario</option>
                                                            <option value="lider" @if($suscripcion->funcion=='lider') selected @endif>Lider</option>
                                                            <option value="super_lider" @if($suscripcion->funcion=='super_lider') selected @endif>Super Lider</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="FuncionRegion">Región de Super Lider</label>
                                                        <select class="form-control" name="FuncionRegion">
                                                            <option value="" >Ningúna</option>
                                                            <option value="Interna" @if($suscripcion->funcion_region=='Interna') selected @endif>Interna</option>
                                                            <option value="México" @if($suscripcion->funcion_region=='México') selected @endif>México</option>
                                                            <option value="RoLA" @if($suscripcion->funcion_region=='RoLA') selected @endif>RoLA</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="ChampionsA">Champions A (Vieron las sesiones 2023)</label>
                                                <select class="form-control" name="ChampionsA">
                                                    <option value="no" @if($suscripcion->champions_a=='no') selected @endif> No</option>
                                                    <option value="si" @if($suscripcion->champions_a=='si') selected @endif> Si</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="ChampionsB">Champions B (university)</label>
                                                <select class="form-control" name="ChampionsB">
                                                    <option value="no" @if($suscripcion->champions_b=='no') selected @endif> No</option>
                                                    <option value="si" @if($suscripcion->champions_b=='si') selected @endif> Si</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="CorreoChampions" name="CorreoChampions" value="1">
                                                <label class="form-check-label" for="CorreoChampions">Enviar correo Champions?</label>
                                            </div>
                                            
                                            
                                            <hr>
                                            <button type="submit" class="btn btn-primary">Actualizar</button>
                                        </form>
                                    </div>
                                </div>
                                </div>
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
    <hr>
    <!--
    <h5>Subida masiva de usuarios</h5>
    <form method="POST" action="{{ route('upload-csv') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_temporada" value={{$_GET['id_temporada']}}>
        <input type="file" name="csv_file" accept=".csv">
        <button type="submit">Subir CSV</button>
    </form>
    <hr>
    <h5>Subir visualizaciones pasadas</h5>
    <form method="POST" action="{{ route('registros_pasados.csv') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv">
        <button type="submit">Subir CSV</button>
    </form>
    <hr>
    <h5>Actualizar pass</h5>
    <form method="POST" action="{{ route('actualizar_pass.csv') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv">
        <button type="submit">Subir CSV</button>
    </form>
-->
@endsection