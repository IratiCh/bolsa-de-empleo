<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="{{ asset('css/demandante/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    <div class="DIV-DEMANDANTE">

        <header class="HEADER">
            <div class="logo">
                <a href="dashboard_demandante.blade.php">
                    <img src="{{ asset('img/logo-purp.png') }}" />
                </a>
            </div>
            <div class="user">
                <a href="perfil_demandante.blade.php">
                    <img src="{{ asset('img/user.png') }}" />
                </a>
            </div>

        </header>

        <div class="CONTENIDO">
            <div class="DEMANDANTE">
                <h1>Título de la Oferta</h1>
                <div class="det-ofer-container">
                    <div class="left-oferta">
                        <p>
                            Descripcion Descripcion Descripcion Descripcion Descripcion
                            Descripcion Descripcion Descripcion Descripcion Descripcion
                            Descripcion Descripcion Descripcion Descripcion Descripcion
                        </p>
                        <h2>Observaciones</h2>
                        <p>
                            Observaciones Observaciones Observaciones Observaciones Observaciones
                            Observaciones Observaciones Observaciones Observaciones Observaciones
                            Observaciones Observaciones Observaciones Observaciones Observaciones
                        </p>
                        <h2>Horario</h2>
                        <p>Horario de trabajo</p>
                        <h2>Tipo de Contrato</h2>
                        <p>Mañana/Tarde</p>
                    </div>
                    <div class="right-oferta">
                        <div class="right-section-ofer">
                            <h3>Nombre Empresa</h3>
                            <p>Localidad</p>
                        </div>
                        <div class="right-section-ofer">
                            <h3>Fechas</h3>
                            <p>Fecha publicación</p>
                        </div>
                        <div class="right-section-ofer">
                            <h3>Vacantes</h3>
                            <p>Número puestos</p>
                        </div>
                        <div class="right-section-ofer">
                            <h3>Título Necesario</h3>
                            <p>Nombre del título</p>
                        </div>
                    </div>
                </div>
                <div class="btn-insc">
                    <button type="submit" class="inscribirse-ofer">INSCRIBIRSE</button>
                </div>
            </div>
        </div>


        <footer class="global-footer">
            <div class="footer-container">
                <div class="footer-section global-section">
                    <h4>Información General</h4>
                    <ul>
                        <li><a href="#">Quiénes somos</a></li>
                        <li><a href="#">Nuestro propósito</a></li>
                        <li><a href="#">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-section global-section">
                    <h4>Ayuda al Usuario</h4>
                    <ul>
                        <li><a href="#">Preguntas frecuentes</a></li>
                        <li><a href="#">Plataforma</a></li>
                        <li><a href="#">Soporte técnico</a></li>
                    </ul>
                </div>
                <div class="footer-section global-section">
                    <h4>Legal y Privacidad</h4>
                    <ul>
                        <li><a href="#">Términos y condiciones</a></li>
                        <li><a href="#">Política de privacidad</a></li>
                        <li><a href="#">Aviso legal</a></li>
                    </ul>
                </div>
                <div class="footer-section global-section subscribe">
                    <h4>Suscríbete</h4>
                    <div>
                        <input class="global-input-subscribe" type="email" placeholder="Email">
                        <button>
                            <img src="{{ asset('img/suscribe-purp.png') }}" />
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
                    <img src="{{ asset('img/fc-white.png') }}" />
                    <img src="{{ asset('img/in-white.png') }}" />
                    <img src="{{ asset('img/tw-white.png') }}" />
                    <img src="{{ asset('img/pint-white.png') }}" />
                </div>
            </div>
        </footer>
    </div>
</body>

</html>