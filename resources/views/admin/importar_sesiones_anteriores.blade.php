@extends('plantillas/plantilla_admin')

@section('titulo', 'Importación sesiones anteriores')

@section('contenido_principal')
<h1>Comparar sesiones</h1>
    @if(isset($rows))
        <table class="table table-bordered">
            <thead>
                <tr>
                    
                    <th>id_visualizacion</th>
                    <th>correo</th>
                    <th>correo_registrado</th>
                    <th>fecha</th>
                    <th>fecha_registrada</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>
                            {{ $row['id_visualizacion'] }}
                        </td>
                        <td>
                            {{ $row['correo'] }}
                        </td>
                        <td>
                            {{ $row['correo_registrado'] }}
                        </td>
                        <td>
                            {{ $row['fecha'] }}
                        </td>
                        <td>
                            {{ $row['fecha_registrada'] }}
                        </td>
                       
                       
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection