<div class="container">
    <h2>Resultado de la Importación de Usuarios</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>¿Ya existe usuario?</th>
                <th>¿Ya está suscrito a la temporada?</th>
                <th>Suscripcion ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resultados as $item)
                <tr>
                    <td>{{ $item['nombre'] }}</td>
                    <td>{{ $item['apellidos'] }}</td>
                    <td>{{ $item['email'] }}</td>
                    <td>{{ $item['existe'] }}</td>
                    <td>{{ $item['suscripcion'] }}</td>
                    <td>{{ $item['id_suscripcion'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
<a href="{{route('admin_usuarios_suscritos', ['id_temporada'=>$id_temporada])}}">Volver a los usuarios</a>
</div>
