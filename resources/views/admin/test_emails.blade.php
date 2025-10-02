@extends('plantillas/plantilla_admin')

@section('titulo', 'Pruebas de Email')

@section('contenido_principal')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Panel de Pruebas de Email</h1>
        <a class="btn btn-outline-primary" href="{{ route('admin') }}">Volver al Panel</a>
    </div>

    <nav aria-label="breadcrumb mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Admin</a></li>
            <li class="breadcrumb-item active">Pruebas de Email</li>
        </ol>
    </nav>

    <hr>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
        </div>
    @endif

    @if(session('resultados'))
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list-check me-2"></i>
                    Resultados del Envío Masivo
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach(session('resultados') as $tipo => $resultado)
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                                @if(str_contains($resultado, 'Error'))
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                @else
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                @endif
                                <div>
                                    <strong class="text-capitalize">{{ str_replace('_', ' ', $tipo) }}:</strong>
                                    <br>
                                    <small class="text-muted">{{ $resultado }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Single Email Test -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-envelope me-2"></i>
                        Email Individual
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Envía un email específico para probar un tipo particular</p>
                    
                    <form method="POST" action="{{ route('admin.test_emails') }}">
                        @csrf
                        
                        <!-- Email Input -->
                        <div class="form-group mb-4">
                            <label class="form-label" for="single-email">
                                <i class="fas fa-at me-1"></i>
                                Email de destino
                            </label>
                            <input type="email" id="single-email" name="email" class="form-control" required />
                            
                        </div>
                        
                        <!-- Email Type Select -->
                        <div class="form-outline mb-4">
                            <select class="form-select" name="tipo_email" id="tipo-email" required>
                                <option value="" disabled selected>Selecciona un tipo de email</option>
                                <optgroup label="Canje de Productos">
                                    <option value="confirmacion_canje">Confirmación Canje (Administrador)</option>
                                    <option value="confirmacion_canje_usuario">Confirmación Canje (Usuario)</option>
                                </optgroup>
                                <optgroup label="Autenticación">
                                    <option value="restaurar_pass">Restaurar Contraseña</option>
                                    <option value="cambio_pass">Cambio de Contraseña</option>
                                </optgroup>
                                <optgroup label="Champions Program">
                                    <option value="confirmacion_nivel_champions">Confirmación Nivel Champions</option>
                                    <option value="finalizacion_champions">Finalización Champions</option>
                                    <option value="inscripcion_champions">Inscripción Champions</option>
                                    <option value="desafio_champions">Desafío Champions</option>
                                </optgroup>
                                <optgroup label="Trivias y Competencias">
                                    <option value="ganador_trivia">Ganador de Trivia</option>
                                    <option value="direccion_trivia">Dirección para Premio</option>
                                </optgroup>
                                <optgroup label="Usuarios">
                                    <option value="registro_usuario">Registro de Nuevo Usuario</option>
                                </optgroup>
                            </select>
                            <label class="form-label" for="tipo-email">
                                <i class="fas fa-list me-1"></i>
                                Tipo de Email
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i>
                            Enviar Email de Prueba
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bulk Email Test -->
        <div class="col-lg-6 mb-4">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-envelope-bulk me-2"></i>
                        Envío Masivo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atención:</strong> Esto enviará todos los tipos de email disponibles
                    </div>
                    
                    <form method="POST" action="{{ route('admin.test_all_emails') }}">
                        @csrf
                        
                        <!-- Email Input -->
                        <div class="form-group mb-4">
                            <label class="form-label" for="bulk-email">
                                <i class="fas fa-at me-1"></i>
                                Email de destino
                            </label>
                            <input type="email" id="bulk-email" name="email" class="form-control" required />
                            
                        </div>

                        <!-- Email Types List -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Se enviarán los siguientes tipos:</h6>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><i class="fas fa-check text-success me-2"></i>Confirmaciones de Canje</td>
                                            <td><span class="badge bg-secondary">2</span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-check text-success me-2"></i>Autenticación</td>
                                            <td><span class="badge bg-secondary">2</span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-check text-success me-2"></i>Champions Program</td>
                                            <td><span class="badge bg-secondary">4</span></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-check text-success me-2"></i>Trivias y Usuarios</td>
                                            <td><span class="badge bg-secondary">3</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-warning btn-lg w-100" 
                                onclick="return confirm('¿Estás seguro de enviar TODOS los emails de prueba?')">
                            <i class="fas fa-rocket me-2"></i>
                            Enviar Todos los Emails
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Información Importante
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card card-body text-center">
                        <i class="fas fa-shield-alt text-success fa-2x mb-2"></i>
                        <h6 class="mb-1">Datos de Prueba</h6>
                        <small class="text-muted">Todos los emails contienen datos ficticios claramente marcados como prueba</small>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-body text-center">
                        <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                        <h6 class="mb-1">Envío Pausado</h6>
                        <small class="text-muted">El envío masivo incluye pausas de 1 segundo entre emails</small>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-body text-center">
                        <i class="fas fa-bug text-info fa-2x mb-2"></i>
                        <h6 class="mb-1">Solo Desarrollo</h6>
                        <small class="text-muted">Esta función debe usarse únicamente en entornos de desarrollo</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection