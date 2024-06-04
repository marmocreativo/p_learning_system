@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios registrados')

@section('contenido_principal')
    <h1>Comparar usuarios</h1>
    @if(isset($rows))
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Correo</th>
                    <th>Registro usuario</th>
                    <th>Registro distribuidor</th>
                    <th>Suscripcion</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td class="">
                            {{ $row['correo'] }}
                        </td>
                        <td class="@if($row['usuario_actualizado']) text-success @else text-danger @endif">
                            @if($row['usuario_actualizado']) Actualizado @else No Actualizado @endif
                        </td>
                        <td class="@if($row['disty_actualizado']) text-success @else text-danger @endif">
                            @if($row['disty_actualizado']) Actualizado @else No Actualizado @endif
                        </td>
                        <td class="@if($row['suscripcion_actualizada']) text-success @else text-danger @endif">
                            @if($row['suscripcion_actualizada']) Actualizado @else No Actualizado @endif
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection