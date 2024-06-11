<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Place the first <script> tag in your HTML's <head> -->
    <script src="https://cdn.tiny.cloud/1/y3nn7mnqo19xsacsvznxqarsmohkoz42yat38khcnolpk6bf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
    <style>


        /* HTML: <div class="loader"></div> */
        #loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999; /* Asegúrate de que esté por encima de otros elementos */
        }
        .loader {
        width: 4px;
        color: #000;
        aspect-ratio: 1;
        border-radius: 50%;
        box-shadow: 
            19px -19px 0 0px, 38px -19px 0 0px, 57px -19px 0 0px,
            19px 0     0 5px, 38px 0     0 5px, 57px 0     0 5px,
            19px 19px  0 0px, 38px 19px  0 0px, 57px 19px  0 0px;
        transform: translateX(-38px);
        animation: l26 2s infinite linear;
        }
        @keyframes l26 {
        12.5% {box-shadow: 
            19px -19px 0 0px, 38px -19px 0 0px, 57px -19px 0 5px,
            19px 0     0 5px, 38px 0     0 0px, 57px 0     0 5px,
            19px 19px  0 0px, 38px 19px  0 0px, 57px 19px  0 0px}
        25%   {box-shadow: 
            19px -19px 0 5px, 38px -19px 0 0px, 57px -19px 0 5px,
            19px 0     0 0px, 38px 0     0 0px, 57px 0     0 0px,
            19px 19px  0 0px, 38px 19px  0 5px, 57px 19px  0 0px}
        50%   {box-shadow: 
            19px -19px 0 5px, 38px -19px 0 5px, 57px -19px 0 0px,
            19px 0     0 0px, 38px 0     0 0px, 57px 0     0 0px,
            19px 19px  0 0px, 38px 19px  0 0px, 57px 19px  0 5px}
        62.5% {box-shadow: 
            19px -19px 0 0px, 38px -19px 0 0px, 57px -19px 0 0px,
            19px 0     0 5px, 38px 0     0 0px, 57px 0     0 0px,
            19px 19px  0 0px, 38px 19px  0 5px, 57px 19px  0 5px}
        75%   {box-shadow: 
            19px -19px 0 0px, 38px -19px 0 5px, 57px -19px 0 0px,
            19px 0     0 0px, 38px 0     0 0px, 57px 0     0 5px,
            19px 19px  0 0px, 38px 19px  0 0px, 57px 19px  0 5px}
        87.5% {box-shadow: 
            19px -19px 0 0px, 38px -19px 0 5px, 57px -19px 0 0px,
            19px 0     0 0px, 38px 0     0 5px, 57px 0     0 0px,
            19px 19px  0 5px, 38px 19px  0 0px, 57px 19px  0 0px}
        }
    </style>
</head>
  <body>
   <div id="loader-container" class="d-none"> <div class="loader"></div></div>
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
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const loader = document.getElementById('loader-container');
                loader.classList.add('d-none');

                
                function startLoader() {
                    const loader = document.getElementById('loader-container');
                    loader.classList.remove('d-none');
                }
                
                const links = document.querySelectorAll('.link-loader');
                 const forms = document.querySelectorAll('.form-loader');
                const confirmButtons = document.querySelectorAll('.btn-confirmar');
                const confirmForms = document.querySelectorAll('.form-confirmar');

                links.forEach(link => {
                    link.addEventListener('click', (event) => {
                        const isDownloadLink = link.hasAttribute('download') || link.href.endsWith('.xls') || link.href.endsWith('.xlsx');
                        if (!isDownloadLink) {
                            event.preventDefault();
                            startLoader();
                            setTimeout(() => {
                                window.location.href = link.href;
                            }, 500);
                        }
                    });
                })

                forms.forEach(form => {
                    form.addEventListener('submit', (event) => {
                        event.preventDefault();
                        startLoader();
                        setTimeout(() => {
                            form.submit();
                        }, 500);
                    });
                });
                
            
                // Añade un event listener a cada botón
                confirmButtons.forEach(function (button) {
                    button.addEventListener('click', function (event) {
                        // Previene la acción por defecto del enlace
                        event.preventDefault();
            
                        // Almacena la URL del enlace
                        const url = event.target.href;
            
                        // Muestra la alerta de confirmación
                        swal({
                            title: '¿Estás seguro?',
                            text: "Esta acción no se puede deshacer",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí, confirmar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                startLoader();
                                // Si el usuario confirma, redirige a la URL
                                window.location.href = url;
                            }
                        });
                    });
                });

                 // Selecciona todos los formularios con la clase 'form-confirmar'
                    
                // Añade un event listener a cada formulario
                confirmForms.forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        // Previene el envío por defecto del formulario
                        event.preventDefault();
                        // Muestra la alerta de confirmación
                        swal({
                            title: '¿Estás seguro?',
                            text: "Esta acción no se puede deshacer",
                            icon: 'warning',
                            buttons: ["Cancelar", "Confirmar"],
                        }).then((result) => {
                            if (result) {
                                startLoader();
                                // Si el usuario confirma, envía el formulario
                                form.submit();
                            }
                        });
                    });
                });
            });

            
            window.addEventListener('pageshow', (event) => {
                if (event.persisted) {
                    const loader = document.getElementById('loader-container');
                    loader.classList.add('d-none');
                }
            });
            </script>
            
    </body>
</html>