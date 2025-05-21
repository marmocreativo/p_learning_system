@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuentas del sistema')

@section('contenido_principal')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Distribuidores <span class="badge badge-primary">Global</span></h1>
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="{{ route('distribuidores.create') }}" class="btn btn-primary">Crear Distribuidor</a>
        </div>
    </div>

    <nav aria-label="breadcrumb mb-3">
            <li class="breadcrumb-item">Distribuidores</li>
        </ol>
    </nav>

    <div class="mb-3">
        
    </div>

    <form action="{{ route('distribuidores') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="busqueda" class="form-control" placeholder="Buscar distribuidor..." value="{{ request('busqueda') }}">
            <button type="submit" class="btn btn-outline-secondary">Buscar</button>
        </div>
    </form>

    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>PAIS</th>
                <th>REGION</th>
                <th>DEFAULT PASS</th>
                <th>NIVEL</th>
                <th>EDIT</th>
                <th>BORRAR</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($distribuidores as $distribuidor)
                <tr>
                    <td>{{ $distribuidor->id }}</td>
                    <td>{{ $distribuidor->nombre }}</td>
                    <td>{{ $distribuidor->pais }}</td>
                    <td>{{ $distribuidor->region }}</td>
                    <td>{{ $distribuidor->default_pass }}</td>
                    <td>{{ $distribuidor->nivel }}</td>
                    <td>
                        <a href="{{ route('distribuidores.show', $distribuidor->id) }}">Ver detalles</a> |
                        <a href="{{ route('distribuidores.edit', $distribuidor->id) }}">Editar</a>
                    </td>
                    <td>
                        <form action="{{ route('distribuidores.destroy', $distribuidor->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-link">Borrar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $distribuidores->appends(['busqueda' => request('busqueda')])->links() }}
@endsection
