<!-- resources/views/auth/verify.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Verificación</title>
</head>
<body>
    <h1>Verificación</h1>
    <form method="POST" action="{{ route('verify') }}">
        @csrf
        <label for="verification_code">Código de verificación:</label>
        <input type="text" id="verification_code" name="verification_code" required><br><br>
        <label for="phone_number">Número de teléfono:</label>
        <input type="tel" id="phone_number" name="phone_number" required value="{{ session('phone_number') }}" readonly><br><br>
        <button type="submit">Verificar</button>
    </form>
</body>
</html>
