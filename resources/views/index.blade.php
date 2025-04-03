<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicia Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

</head>
<body class="index-body">
    <div class="different-bg">
        <header class="index-header">
            <div class="index-logo">
                <img src="{{ asset('img/logo-purp.png') }}"/>
            </div>
        </header>
        <div class="container index-container">
            <h1>Inicia Sesión</h1>
            <p class="intro">Bienvenido al punto de encuentro donde el talento 
                y las oportunidades se conectan. Ya seas una persona en busca de 
                tu próximo desafío profesional o una empresa en busca de los mejores 
                perfiles, aquí encontrarás las herramientas y el apoyo necesario para 
                lograrlo. Únete a nuestra comunidad y empieza a construir el futuro que deseas.
            </p>
            <p class="highlight">¡EMPIEZA CUANTO ANTES!</p>
            <button class="index-btn">Inicia Sesión</button>
            <p class="register">¿No tienes una cuenta? 
                <a href="registro_demandante.blade.php">Regístrate para trabajar</a> 
                | 
                <a href="registro_empresa.blade.php">Regístrate para ofrecer trabajo</a></p>
        </div>
    </div>
    <footer class="index-footer">
        <div class="footer-container">
            <div class="footer-section">
                <h4>Información General</h4>
                <ul>
                    <li><a href="#">Quiénes somos</a></li>
                    <li><a href="#">Nuestro propósito</a></li>
                    <li><a href="#">Contacto</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Ayuda al Usuario</h4>
                <ul>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Plataforma</a></li>
                    <li><a href="#">Soporte técnico</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Legal y Privacidad</h4>
                <ul>
                    <li><a href="#">Términos y condiciones</a></li>
                    <li><a href="#">Política de privacidad</a></li>
                    <li><a href="#">Aviso legal</a></li>
                </ul>
            </div>
            <div class="footer-section subscribe">
                <h4>Suscríbete</h4>
                <div>
                    <input type="email" placeholder="Email">
                    <button>
                        <img src="{{ asset('img/suscribe-purp.png') }}"/>
                    </button>
                </div> 
                <p>Suscríbete para enterarte de las nuevas ofertas de empleo.</p>
            </div>
        </div>
        <div class="footer-copy">
            <p>© 2025 Sara & Irati. Derechos reservados. 
                <a href="#">Política de Privacidad</a> 
                <a href="#">Términos de Servicio</a>
            </p>
            <div class="social-icons">
                <img src="{{ asset('img/fc-white.png') }}"/>
                <img src="{{ asset('img/in-white.png') }}"/>
                <img src="{{ asset('img/tw-white.png') }}"/>
                <img src="{{ asset('img/pint-white.png') }}"/>
            </div>
        </div>
    </footer>
</body>
</html>
