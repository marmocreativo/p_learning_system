@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios inscritos')

@section('contenido_principal')
    <h1>Usuarios inscritos</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('admin_usuarios.suscripcion', ['id_temporada'=>$_GET['id_temporada']]) }}">Inscribir usuario</a>
    <hr>
    <form action="{{ route('admin_usuarios_suscritos') }}" method="GET">
        <input type="hidden" name="id_temporada" value="{{$_GET['id_temporada']}}">
        <input type="text" name="search" placeholder="Buscar...">
        <button type="submit">Buscar</button>
    </form>
    <hr>
    <table class="table table-stripped">
        <tr>
            <td>
                <h3>Usuarios totales</h3>
                <h5>{{$suscriptores_totales}}</h5>
            </td>
            <td>
                <h3>Usuarios activos</h3>
                <h5>{{$suscriptores_activos}}</h5>
            </td>
            <td>
                <h3>Usuarios participantes</h3>
                <h5>{{$suscriptores_participantes}}</h5>
            </td>
        </tr>
    </table>
    <hr>
    <table class="table table-stripped">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Distribuidor</th>
            <th>Permisos</th>
            <th>champions_a</th>
            <th>champions_b</th>
            <th>Controles</th>
        </tr>
        @foreach ($suscripciones as $suscripcion)
                <tr>
                    <td>{{$suscripcion->id_usuario}}</td>
                    <td>{{$suscripcion->nombre}} {{$suscripcion->apellidos}}</td>
                    <td>{{$suscripcion->email}} </td>
                    <td>{{$suscripcion->nombre_distribuidor}}</td>
                    <td>
                        {{$suscripcion->funcion}}
                        @if($suscripcion->funcion === 'usuario')
                            <a href="{{ route('admin_usuarios.cambiar_a_lider', ['id' => $suscripcion->id, 'id_temporada' => $_GET['id_temporada']]) }}">Cambiar a líder</a>
                        @else
                            <a href="{{ route('admin_usuarios.cambiar_a_usuario', ['id' => $suscripcion->id, 'id_temporada' => $_GET['id_temporada']]) }}">Cambiar a usuario</a>
                        @endif
                    </td>
                    <td>{{$suscripcion->champions_a}} </td>
                    <td>{{$suscripcion->champions_b}} </td>
                    <td>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#formulario{{$suscripcion->id}}">
                            Editar
                        </button>
                        <!-- Modal -->
                            <div class="modal fade" id="formulario{{$suscripcion->id}}" tabindex="-1" aria-labelledby="formulario{{$suscripcion->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form action="{{ route('admin_usuarios.suscribir_update', $suscripcion->id) }}" method="POST">
                                            <input type="hidden" name="IdTemporada" value="{{$suscripcion->id_temporada}}">
                                            <input type="hidden" name="IdUsuario" value="{{$suscripcion->id_usuario}}">
                                            @method('PUT')
                                            @csrf
                                            <table class='table table-bordered'>
                                                <tr>
                                                    <th>Nombre:</th>
                                                    <td>{{$suscripcion->nombre}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Apellidos:</th>
                                                    <td>{{$suscripcion->apellidos}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email:</th>
                                                    <td>{{$suscripcion->email}}</td>
                                                </tr>
                                            </table>
                                            <a href="{{ route('admin_usuarios.edit', $suscripcion->id_usuario) }}">Editar datos personales</a>
                                            <div class="form-group">
                                                <label for="IdDistribuidor">Distribuidor</label>
                                                <select class="form-control" name="IdDistribuidor">
                                                    @foreach ($distribuidores as $distribuidor)
                                                        <option value="{{ $distribuidor->id }}" @if($distribuidor->id==$suscripcion->id_distribuidor) selected @endif> {{ $distribuidor->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="NivelUsuario">Nivel Usuario</label>
                                                <select class="form-control" name="NivelUsuario">
                                                    <option value="ventas" @if($suscripcion->id_distribuidor=='ventas') selected @endif> Ventas</option>
                                                    <option value="especialista" @if($suscripcion->id_distribuidor=='especialista') selected @endif> Especialista</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="Funcion">Función</label>
                                                <select class="form-control" name="Funcion">
                                                    <option value="usuario" @if($suscripcion->funcion=='usuario') selected @endif> Usuario</option>
                                                    <option value="lider" @if($suscripcion->funcion=='lider') selected @endif>Lider</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="ChampionsA">Champions A</label>
                                                <select class="form-control" name="ChampionsA">
                                                    <option value="no" @if($suscripcion->champions_a=='no') selected @endif> No</option>
                                                    <option value="si" @if($suscripcion->champions_a=='si') selected @endif> Si</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="ChampionsB">Champions B</label>
                                                <select class="form-control" name="ChampionsB">
                                                    <option value="no" @if($suscripcion->champions_b=='no') selected @endif> No</option>
                                                    <option value="si" @if($suscripcion->champions_b=='si') selected @endif> Si</option>
                                                </select>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-primary">Actualizar</button>
                                        </form>
                                    </div>
                                </div>
                                </div>
                            </div>
                        <form action="{{route('admin_usuarios.desuscribir', $suscripcion->id)}}" method="POST">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="id_temporada" value='{{$_GET['id_temporada']}}'>
                            <button type="submit" class="btn btn-link">Desuscribir</button>
                        </form>
                    </td>
                </tr>
        @endforeach
    </table>
    <?php 
        $appends = array();
        $appends['id_temporada'] = $_GET['id_temporada'];
        if(isset($_GET['search']) && !empty($_GET['search'])){
            $appends['search'] = $_GET['search'];
        }
    ?>
    {{ $suscripciones->appends($appends)->links() }}
    <hr>
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
@endsection