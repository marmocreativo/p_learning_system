@extends('plantillas/plantilla_login')

@section('title', 'Login')

@section('contenido_principal')
    
    <div class="contenedor_principal">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-8">
                        
                        <div class="contenedor_login">
                            <form action="login/verificar" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="Email">Correo</label>
                                    <input type="text" class="form-control" name="Email" id="Email">
                                </div>
                                
                                <div class="form-group">
                                    <label for="Password">Contraseña</label>
                                    <input type="password" class="form-control" name="Password" id="Password">
                                </div>
                                <hr>
                                <button type="submit" class="btn btn-primary">
                                    Iniciar Sesión
                                </button>
                                <hr>
                                <a href="{{ route('login.registro_form') }}">Registrarme</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        
    </div>
@endsection