<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Demandante</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body class="registro-body">
    <div class="container registro-container">
        <div class="left-section">
            <div class="logo">
                <img src="img/logo-white.png"/>
            </div>
            <h2>Encuentra tu trabajo ideal según tu formación</h2>
            <img class="foto-registro" src="img/ejemplo-registro-empresa.png" alt="Inicio sesión">
        </div>
        <div class="right-section-register">
            <h1>Crear Cuenta</h1>
            <form method="POST">
                <div>
                    <div class="input-group-register">
                        <label for="text">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="input-group-register">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>

                <div>
                    <div class="input-group-register">
                        <label for="text">CFI</label>
                        <input type="text" id="dni" name="dni" required>
                    </div>
                    <div class="input-group-register">
                        <label for="number">Teléfono</label>
                        <input type="number" id="telefono" name="telefono" required>
                    </div>
                </div>

                <div>
                    <div class="input-group-register">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="register-dem-email" name="email" required>
                    </div>
                    <div class="input-group-register">
                        <label for="text">Localidad</label>
                        <input type="text" id="localidad" name="localidad" required>
                    </div>
                </div>

                <div class="register-btn-container">
                    <button type="submit" class="register-btn">Crear Cuenta</button>

                    <div class="register-login">
                        <p>¿Ya tienes una cuenta?
                            <a href="login.html">Inicia Sesión</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
