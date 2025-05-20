<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo')</title>
    <link rel="stylesheet" href="{{ asset('css/mdb.min.css') }}">
    <link
      href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('css/estilos_front.css') }}">
</head>
  <body>
<body>
    @yield('contenido_principal')
    
    <script src="{{ asset('js/mdb.umd.min.js') }}"></script>
</body>
</html>