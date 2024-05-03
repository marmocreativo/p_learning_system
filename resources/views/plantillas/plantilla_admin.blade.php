<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Place the first <script> tag in your HTML's <head> -->
    <script src="https://cdn.tiny.cloud/1/y3nn7mnqo19xsacsvznxqarsmohkoz42yat38khcnolpk6bf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar TinyMCE en las áreas de texto con la clase TextEditor
        tinymce.init({
            selector: "textarea.TextEditor",
            plugins: "autolink lists link code",
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | code" ,
            menubar: false,
        });
    });
    </script>
    <link rel="stylesheet" href="{{ asset('css/estilos_admin.css') }}">
</head>
  <body>
        <div class="contenedor_flex">
            <div class="menu_admin">
                <a href="{{ route('login.logout')}}">Cerrar Sesión</a>
            </div>
            <div class="cuerpo_admin">
                <div class="barra_lateral">
                    <ul class="list">
                        <li><a href="{{ route('cuentas') }}">Cuentas</a></li>
                        <li><a href="{{ route('distribuidores') }}">Distribuidores</a></li>
                        <li><a href="{{ route('admin_usuarios') }}">Usuarios</a></li>
                        <li><a href="{{ route('clases') }}">Clases</a></li>
                        <li><a href="{{ route('configuraciones') }}">Configuraciones</a></li>
                        <li><a href="{{ route('admin.base_de_datos') }}">Base de datos</a></li>
                    </ul>
                </div>
                <div class="contenedor_principal">
                    @yield('contenido_principal')
                </div>
            </div>
            <div class="footer">

            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>