<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            background-color: white;
            padding: 20px;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 ¡Equipo Creado!</h1>
        </div>
        <div class="content">
            <h2>Hola</h2>
            <p>Tu equipo <strong>{{ $equipo->nombre }}</strong> ha sido creado exitosamente.</p>
            
            <p><strong>Detalles del equipo:</strong></p>
            <ul>
                <li>Nombre: {{ $equipo->nombre }}</li>
                <li>Evento: {{ $equipo->evento->nombre }}</li>
                <li>Fecha de creación: {{ $equipo->created_at->format('d/m/Y') }}</li>
            </ul>
            
            <p>Ahora puedes invitar a más participantes a tu equipo.</p>
            
            <a href="{{ url('/equipos/' . $equipo->id) }}" class="button">Ver mi equipo</a>
        </div>
    </div>
</body>
</html>
