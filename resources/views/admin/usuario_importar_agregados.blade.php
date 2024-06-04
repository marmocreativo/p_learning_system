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
                        <td class="@if($row['usuario_registrado']) text-success @else text-danger @endif">
                            @if($row['usuario_registrado']) Nuevo @else Existente @endif
                        </td>
                        <td class="@if($row['disty_registrado']) text-success @else text-danger @endif">
                            @if($row['disty_registrado']) Nuevo @else Existente @endif
                        </td>
                        <td class="@if($row['suscripcion_registrada']) text-success @else text-danger @endif">
                            @if($row['suscripcion_registrada']) Nuevo @else Existente @endif
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection