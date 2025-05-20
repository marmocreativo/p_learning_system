@extends('plantillas/plantilla_login')

@section('title', 'Login')

@section('contenido_principal')
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        
        <div class="text-center mb-4" style="background: linear-gradient(75deg, #004976 5%, #253746); padding: 20px;">
            <img src="https://www.panduitlatam.com/img/logo-panduit-w.png" alt="Logo" class="img-fluid">
        </div>

        <form action="{{ url('login/verificar') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="Email" class="form-label">Correo</label>
                <input type="email" id="Email" name="Email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="Password" class="form-label">Contraseña</label>
                <input type="password" id="Password" name="Password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Iniciar Sesión
            </button>
        </form>
    </div>
</div>
@endsection
