<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo</title>
    <style>
        body {
            background-color: #eee;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .email-content {
            background-color: #fff;
            width: 600px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #213746;
            padding: 10px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .header img {
            max-width: 100%;
            height: auto;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007BFF;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-content">
            <div class="header">
                <img src="https://pl-electrico.panduitlatam.com/assets/images/micrositio/PLe_HeadLogo_2024.png" alt="Logo">
            </div>
            <h1>{{ $data['titulo'] }}</h1>
            <p>{{ $data['contenido'] }}</p>
            @if (!empty($data['boton_enlace']) && !empty($data['boton_texto']))
                <a href="{{ $data['boton_enlace'] }}" class="button">{{ $data['boton_texto'] }}</a>
            @endif
            <div class="footer">
                &copy; {{ date('Y') }} Todos los derechos reservados.
            </div>
        </div>
    </div>
</body>
</html>
