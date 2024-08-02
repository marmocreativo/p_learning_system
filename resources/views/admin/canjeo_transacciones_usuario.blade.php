@extends('plantillas/plantilla_admin')

@section('titulo', 'Canjeo Transacciones Usuario')

@section('contenido_principal')
    <h1>Transacciones {{$usuario->nombre}} {{$usuario->apellidos}}</h1>
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas.show', $temporada->id)}}">Temporada</a></li>
                  <li class="breadcrumb-item">Transacciones usuario</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @foreach ($transacciones as $transaccion)
                <div class="card">
                    <div class="card-header">
                        <h4>Folio: {{ str_pad($transaccion->id, 6, '0', STR_PAD_LEFT) }}</h4>
                    </div>
                    <div class="card-body bg-light">
                        <table class="table table-bordered mb-3">
                            <thead>
                                <tr>
                                    <th>
                                        <h6>Fecha Registro</h6>
                                        <p>{{$transaccion->fecha_registro}}</p>
                                    </th>
                                    <th>
                                        <h6>Fecha Confirmación</h6>
                                        <p>@if($transaccion->fecha_confirmacion) {{$transaccion->fecha_confirmacion}} @else - @endif</p>
                                    </th>
                                    <th>
                                        <h6>Fecha Envio</h6>
                                        <p>@if($transaccion->fecha_confirmacion) {{$transaccion->fecha_envio}} @else - @endif</p>
                                    </th>
                                    <th>
                                        <h6>Créditos</h6>
                                        <p>{{$transaccion->creditos}} </p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3">
                                        <h3>Productos:</h3>
                                        <table class="table table-bordered">
                                            @foreach ($transaccion->productos as $producto)
                                                <tr>
                                                    <td><b>{{$producto->nombre}}</b><br>{{$producto->variacion}}</td>
                                                    <td>{{$producto->cantidad}}</td>
                                                    <td>{{$producto->creditos_unitario}}</td>
                                                    <td>{{$producto->creditos_totales}}</td>
                                                </tr>
                                            @endforeach
                                            
                                        </table>
                                    </td>
                                    <td>
                                        <p><b>Recibe:</b> {{$transaccion->direccion_nombre}}</p>
                                        @php
                                            $direccion = '';
                                            $direccion .= $transaccion->direccion_calle.', ';
                                            $direccion .= $transaccion->direccion_numero.', ';
                                            $direccion .= $transaccion->direccion_numeroint.', ';
                                            $direccion .= $transaccion->direccion_colonia.', ';
                                            $direccion .= $transaccion->direccion_ciudad.', ';
                                            $direccion .= $transaccion->direccion_codigo_postal;
                                        @endphp
                                        <p><b>Dirección:</b> {{$direccion}}</p>
                                        <p><b>Horario:</b> {{$transaccion->direccion_horario}}</p>
                                        <p><b>Referencia:</b> {{$transaccion->direccion_referencia}}</p>
                                        <p><b>Notas:</b> {{$transaccion->direccion_notas}}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
            
        </div>
    </div>
    
@endsection