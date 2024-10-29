@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Top 10 Región: <small>{{$_GET['region']}}</small></h1>
    <div class="row mb-3">
        <div class="col-9">
            <form action="{{ route('top_10_region') }}" method="GET" class="d-flex">
                <input type="hidden" name="id" value="{{$temporada->id_cuenta}}">
                @csrf
                <div class="form-group d-flex me-3">
                    <label for="region" class="pt-2 me-2">Región</label>
                    <select name="region" class="form-control">
                        <option value="interna"  @selected(request()->get('region') === 'interna')>Interna</option>
                        <option value="México"  @selected(request()->get('region') === 'México')>México</option>
                        <option value="RoLA"  @selected(request()->get('region') === 'RoLA')>RoLA</option>
                    </select>
                </div>
                <div class="form-group d-flex">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
                

            </form>
        </div>
        <div class="col-3">
            <a href="{{route('top_10_borrar_corte', ['id' => request()->get('id'), 'region' => request()->get('region')])}}" class="btn btn-danger ms-auto">Borrar corte</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Distribuidor</th>
                            <th>Puntaje</th>
                            <th>Premio</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($usuarios_filtrados as $usuario)
                            @php
                                $indice = array_search($usuario['puntaje'], $puntajes_top);
                            @endphp
                            <tr>
                                <td>{{$indice+1}}</td>
                                <td>{{$usuario['nombre']}}</td>
                                <td>{{$usuario['distribuidor']}}</td>
                                <td>{{$usuario['puntaje']}}</td>
                                <td>
                                    @if (!empty($usuario['premio']))
                                    {{$usuario['premio']}}
                                    @else 
                                    @if ($ganadores_distribuidor[$usuario['distribuidor']]<1)
                                        <form action="{{route('actualizar_premio_top_10')}}" method="POST">
                                            <input type="hidden" name="id_suscripcion" value="{{$usuario['suscripcion']}}">
                                            <input type="hidden" name="cuenta" value="{{$_GET['id']}}">
                                            <input type="hidden" name="region" value="{{$_GET['region']}}">
                                            @csrf
                                            <label for="premio">Selecciona el premio</label>
                                            <select name="premio" class="form-control">
                                                <option value="">Ningúno</option>
                                                @if ($ganadores_region[$usuario['region']]<1)
                                                    <option value="experiencia">Experiencia Panduit</option>
                                                @endif
                                                
                                                @if ($ganadores_distribuidor[$usuario['distribuidor']]<1)
                                                <option value="bono">Bonoeconómico</option>
                                                @endif
                                            </select>
                                            <button type="submit" class="btn btn-primary mt-3 w-100">Guardar</button>
                                        </form>  
                                    @endif
                                    @endif

                                    
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    

@endsection