<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicia Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="login-body">
    <div class="container login-container">
        <div class="left-section">
            <div class="logo">
                <img src="{{ asset('img/logo-white.png') }}"/>
            </div>
            <h2>Conecta talento y oportunidades en un solo clic</h2>
            <img class="foto-login" src="{{ asset('img/ejemplo-login.png') }}" alt="Inicio sesión">
        </div>
        <div class="right-section">
            <h1>Inicia Sesión</h1>
            <form>
                <div class="input-group">
                    <label for="email" class="login-lab-email">Email</label>
                    <input type="email" id="email" class="login-email" name="email" required>
                </div>
                
                <div class="input-group">
                    <label for="password" class="login-lab-pass">Contraseña</labeñl>
                    <input type="password" id="password" class="login-pass" name="password" required>
                </div>

                <button type="submit" class="login-btn">Inicia Sesión</button>
            </form>
            <div class="login-register">
                <p>¿No tienes una cuenta?</p>
                <p><a href="registro_demandante.blade.php">Regístrate para trabajar</a></p>
                <p><a href="registro_empresa.blade.php">Regístrate para ofrecer trabajo</a></p>
            </div>
        </div>
    </div>
</body>
</html>
