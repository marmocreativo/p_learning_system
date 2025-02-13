@extends('plantillas/plantilla_admin')

@section('titulo', 'Usuarios registrados')

@section('contenido_principal')
    <h1>Comparar usuarios</h1>
    @if(isset($rows))
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Ventas/Especialista</th>
                    <th>Lider</th>
                    <th>Disty</th>
                    <th>Nivel Disty</th>
                    <th>Usuario</th>
                    <th>Region</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td class="@if($row['nombre_coincide']) text-success @else text-danger @endif">
                            {{ $row['nombre'] }}
                            <br>
                            <span style="color:#000">{{ $row['nombre_registrado'] }}</span>
                        </td>
                        <td class="@if($row['apellidos_coincide']) text-success @else text-danger @endif">
                            {{ $row['apellidos'] }}
                            <br>
                            <span style="color:#000">{{ $row['apellidos_registrado'] }}</span>
                        </td>
                        <td class="@if($row['registrado']) text-success @else text-danger @endif"  >
                            {{ $row['correo'] }}
                            <br>
                            <span style="color:#000">{{ $row['correo_registrado'] }}</span>
                        </td>
                        <td class="@if($row['nivel_coincide']) text-success @else text-danger @endif">
                            {{ $row['nivel_usuario'] }}
                            <br>
                            <span style="color:#000">{{ $row['nivel_usuario_registrado'] }}</span>
                        </td>
                        <td class="@if($row['lider_coincide']) text-success @else text-danger @endif">
                            {{ $row['lider'] }}
                            <br>
                            <span style="color:#000">{{ $row['lider_registrado'] }}</span>
                        </td>
                        <td class="@if($row['disty_coincide']) text-success @else text-danger @endif"  >
                            {{ $row['disty'] }}
                            <br>
                            <span style="color:#000">{{ $row['disty_registrado'] }}</span>
                        </td>
                        <td class="@if($row['nivel_disty_coincide']) text-success @else text-danger @endif">
                            {{ $row['nivel_disty'] }}
                            <br>
                            <span style="color:#000">{{ $row['nivel_disty_registrado'] }}</span>
                        </td>
                        <td class="@if($row['usuario_coincide']) text-success @else text-danger @endif">
                            {{ $row['usuario'] }}
                            <br>
                            <span style="color:#000">{{ $row['usuario_registrado'] }}</span>
                        </td>
                        <td class="@if($row['region_coincide']) text-success @else text-danger @endif"  >
                            {{ $row['region'] }}
                            <br>
                            <span style="color:#000">{{ $row['region_registrado'] }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection