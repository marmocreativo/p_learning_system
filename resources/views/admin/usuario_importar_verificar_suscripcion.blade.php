@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios registrados')

@section('contenido_principal')
    <h1>Comparar usuarios</h1>
    @if(isset($rows))
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID USUARIO</th>
                    <th>ID TEMPORADA</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>
                            {{ $row['id'] }}
                        </td>
                        <td>
                            {{ $row['id_usuario'] }}
                        </td>
                        <td>
                            {{ $row['id_temporada'] }}
                        </td>
                       
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection