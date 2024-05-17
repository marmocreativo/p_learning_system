<table>
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Email</th>
        <th>Distribuidor</th>
        <th>Inicios de Sesión</th>
        <th>Activo</th>
        <th>Participante</th>
    </tr>
    </thead>
    <tbody>
    @foreach($listado_usuarios as $usuario)
        <tr>
            <td>{{ $usuario->nombre }}</td>
            <td>{{ $usuario->apellidos }}</td>
            <td>{{ $usuario->email }}</td>
            <td>{{ $usuario->nombre_distribuidor }}</td>
            <td>{{ $usuario->inicios_sesion }}</td>
            <td>{{ $usuario->activo }}</td>
            <td>{{ $usuario->participante }}</td>
            
        </tr>
    @endforeach
    </tbody>
</table>
