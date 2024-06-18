<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>
    <h1>Registro</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="phone_number">Número de teléfono:</label>
        <input type="tel" id="phone_number" name="phone_number" required><br><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="password_confirmation">Confirmar contraseña:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required><br><br>
        <button type="submit">Registrarse</button>
    </form>
</body>
</html>