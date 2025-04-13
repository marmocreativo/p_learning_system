
<div class="container">
    <h2>Resultado de la Importación de Sucursales</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Distribuidor</th>
                <th>Sucursal</th>
                <th>Acción</th>
                <th>¿Ya existe?</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resultados as $item)
                <tr>
                    <td>{{ $item['distribuidor'] }}</td>
                    <td>{{ $item['sucursal'] }}</td>
                    <td>{{ $item['accion'] }}</td>
                    <td>{{ $item['existe'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
<a href="{{route('distribuidores.suscritos', ['id_temporada'=>$id_temporada])}}">Volver a los distribuidores</a>
</div>
