
<div class="container">
    <h2>Resultado de la Importación de Puntos extra</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Correo</th>
                <th>Concepto</th>
                <th>Puntos</th>
                <th>¿Ya existe?</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resultados as $item)
                <tr>
                    <td>{{ $item['correo'] }}</td>
                    <td>{{ $item['concepto'] }}</td>
                    <td>{{ $item['puntos'] }}</td>
                    <td>{{ $item['existe'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
<a href="{{route('admin_usuarios_puntos_extra', ['id_temporada'=>$id_temporada])}}">Volver al listado de puntos extra</a>
</div>
