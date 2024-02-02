@extends('plantillas/plantilla_admin')

@section('titulo', 'Clases del sistema')

@section('contenido_principal')
    <h1>Formulario de configuracion</h1>
    <form action="{{ route('configuraciones.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="Nombre">Nombre</label>
            <input type="text" class="form-control" name="Nombre">
        </div>
        <div class="form-group">
            <label for="Valor">Valor</label>
            <textarea name="Valor" class="form-control" rows="10"></textarea>
        </div>
        
        <div class="form-group">
            <label for="Input"></label>
            <select name="Input" class="form-control" id="Input">
                <option value="varchar">Input de texto</option>
                <option value="textarea">Text área</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Clase">Clase de configuracion</label>
            <select class="form-control" name="Clase">
                @foreach ($clases as $clase)
                    <option value="{{ $clase->nombre_sistema }}" > {{ $clase->nombre_singular}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="Orden">Orden</label>
            <input type="text" class="form-control" name="Orden">
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection