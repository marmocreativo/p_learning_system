@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios inscritos')

@section('contenido_principal')
    <h1>Usuarios inscritos</h1>
    <a href="{{ route('temporadas.show', $_GET['id_temporada']) }}">Volver a la temporada</a>
    <hr>
    <a href="{{ route('admin_usuarios.suscripcion', ['id_temporada'=>$_GET['id_temporada']]) }}">Inscribir usuario</a>
    <hr>
    <table class="table table-stripped">
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Ventas/Especialista</th>
            <th>Lider</th>
            <th>Whatsapp</th>
            <th>Disty</th>
            <th>Nivel Disty</th>
            <th>Region</th>
            <th>Usuario</th>
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
                    <td>{{$suscripcion->nivel_usuario}}</td>
                    <td>{{$suscripcion->funcion}}</td>
                    <td>{{$suscripcion->whatsapp}}</td>
                    <td>{{$suscripcion->nombre_distribuidor}}</td>
                    <td>{{$suscripcion->nivel_distribuidor}}</td>
                    <td>{{$suscripcion->region}}</td>
                    <td>{{$suscripcion->legacy_id}}</td>
                    <td>
                        {{$suscripcion->default_pass}}<br>
                        <form action="{{route('admin_usuarios.restaurar_pass', $suscripcion->id)}}" method="POST">
                            @csrf
                            @method('put')
                            <input type="hidden" name="id_temporada" value='{{$_GET['id_temporada']}}'>
                            <input type="hidden" name="id_usuario" value='{{$suscripcion->id_usuario}}'>
                            <input type="hidden" name="id_distribuidor" value='{{$suscripcion->id_distribuidor}}'>
                            <button type="submit" class="btn btn-link">Restaurar pass</button>
                        </form>
                    </td>
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
    {{ $suscripciones->appends($appends)->links() }}
@endsection