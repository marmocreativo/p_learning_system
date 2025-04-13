<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>País</th>
            <th>Región</th>
            <th>Nivel</th>
            <th>Acción</th>
            <th>¿Ya existe?</th>
            <th>¿Suscripción?</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resultados as $r)
            <tr>
                <td>{{ $r['nombre'] }}</td>
                <td>{{ $r['pais_excel'] }}</td>
                <td>{{ $r['region_excel'] }}</td>
                <td>{{ $r['nivel_excel'] }}</td>
                <td>{{ $r['accion'] }}</td>
                <td>{{ $r['ya_existe'] }}</td>
                <td>{{ $r['suscripcion'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<hr>
<a href="{{route('distribuidores.suscritos', ['id_temporada'=>$id_temporada])}}">Volver a los distribuidores</a>