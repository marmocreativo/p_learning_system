
<div class="container">
    <h2>Resultado de la Importación de Skus</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Agregados</th>
                <th>Existentes</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resultados as $item)
                <tr>
                    <td>{{ $item['agregados'] }}</td>
                    <td>{{ $item['existentes'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
<a href="{{route('logros.show', $id_logro)}}">Volver al desafio</a>
</div>
