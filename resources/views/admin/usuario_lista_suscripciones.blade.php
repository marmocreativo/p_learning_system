@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios inscritos')

@section('contenido_principal')
    <h1>Usuarios inscritos</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('admin_usuarios.suscripcion', ['id_temporada'=>$_GET['id_temporada']]) }}">Inscribir usuario</a>
    <hr>
    <a href="{{ route('admin_usuarios_suscritos_reporte_temporada', ['id_temporada'=>$_GET['id_temporada']]) }}" download="reporte_usuarios_general.xls">Descargar EXCEL</a>
    <hr>
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
            <form action="{{ route('admin_usuarios.importar') }}" class="d-flex" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_temporada" value="{{$_GET['id_temporada']}}">
                <div class="form-group">
                    <input type="file" name="file" accept=".xlsx">
                </div>
                <div class="form-group">
                    <select class="form-control" name="accion" id="accion">
                        <option value="comparar">Comparar</option>
                        <option value="agregar">Agregar</option>
                        <option value="actualizar">Actualizar</option>
                        <option value="checar_suscripciones">Checar suscripciones</option>
                        <option value="borrar_suscripciones">Borrar suscripciones</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Import</button>
            </form>
        </div>
    </div>
    
    <hr>
    <table class="table table-stripped">
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
            <th>Region</th>
            <th>Temp {{$temporada->nombre}}</th>
            <th>Temp Anterior</th>
            <th>University</th>
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
                    <td>{{$suscripcion->region}}</td>
                    <td>{{$suscripcion->temporada_completa}}</td>
                    <td>{{$suscripcion->champions_a}}</td>
                    <td>{{$suscripcion->champions_b}}</td>
                    
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
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#formulario{{$suscripcion->id}}">
                            Editar
                        </button>
                        <a href="{{route('admin_usuarios.reporte_sesiones', $suscripcion->id_suscripcion)}}" class="btn btn-info">
                            Reporte
                        </a>
                        <!-- Modal -->
                            <div class="modal fade" id="formulario{{$suscripcion->id}}" tabindex="-1" aria-labelledby="formulario{{$suscripcion->id}}Label" aria-hidden="true">
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
                            <a href="{{ route('admin_usuarios.borrar_tokens', ['id' => $suscripcion->id_usuario]) }}" class="btn btn-outline-danger">
                                Cerrar sesión
                            </a>
                        <form action="{{route('admin_usuarios.desuscribir', $suscripcion->id_suscripcion)}}" class="form-confirmar" method="POST">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="id_temporada" value='{{$_GET['id_temporada']}}'>
                            <button type="submit" class="btn btn-danger">Desuscribir</button>
                        </form>

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