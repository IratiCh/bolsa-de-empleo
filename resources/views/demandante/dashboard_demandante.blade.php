<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="{{ asset('css/demandante/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    <div class="DIV-DEMANDANTE">

        <header class="HEADER-DASH">
            <div class="logo">
                <img src="{{ asset('img/logo-purp.png') }}" />
            </div>
            <div class="user">
                <a href="perfil_demandante.blade.php">
                    <img src="{{ asset('img/user.png') }}" />
                </a>
            </div>
            <div class="dash">
                <h1>Encuentra tu trabajo ideal según tu formación</h1>
                <img src="{{ asset('img/dash-demandante.png') }}" />
            </div>

        </header>

        <div class="CONTENIDO">

            <form class="buscador">
                <img src="{{ asset('img/buscar.png') }}" alt="Búsqueda" />
                <input class="search-input" type="text" placeholder="Busca tu Trabajo Ideal" />
                <img src="{{ asset('img/linea.png') }}" alt="Línea" />
                <input class="limpiar" type="reset" value="Limpiar" />
                <div class="cont-buscar">
                    <button class="btn-buscar">Buscar</button>
                </div>
            </form>



            <h1 class="title-dash">Encuentra un Nuevo Trabajo</h1>
            <div class="lista-ofertas">
                <!-- Card 1 -->
                <div class="card-ofer">
                    <div class="card-header">
                        <h3>Trabajo</h3>
                    </div>
                    <div class="card-details">
                        <p>Breve descripción</p>
                        <div class="info-section">
                            <p>Fecha publicación</p>
                            <img src="{{ asset('img/separador.png') }}" />
                            <p>Tipo contrato</p>
                        </div>
                        <div class="info-empresa">
                            <img src="{{ asset('img/ofer-abierta.png') }}" />
                            <h4>Nombre empresa</h4>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card-ofer">
                    <div class="card-header">
                        <h3>Trabajo</h3>
                    </div>
                    <div class="card-details">
                        <p>Breve descripción</p>
                        <div class="info-section">
                            <p>Fecha publicación</p>
                            <img src="{{ asset('img/separador.png') }}" />
                            <p>Tipo contrato</p>
                        </div>
                        <div class="info-empresa">
                            <img src="{{ asset('img/ofer-abierta.png') }}" />
                            <h4>Nombre empresa</h4>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card-ofer">
                    <div class="card-header">
                        <h3>Trabajo</h3>
                    </div>
                    <div class="card-details">
                        <p>Breve descripción</p>
                        <div class="info-section">
                            <p>Fecha publicación</p>
                            <img src="{{ asset('img/separador.png') }}" />
                            <p>Tipo contrato</p>
                        </div>
                        <div class="info-empresa">
                            <img src="{{ asset('img/ofer-abierta.png') }}" />
                            <h4>Nombre empresa</h4>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="card-ofer">
                    <div class="card-header">
                        <h3>Trabajo</h3>
                    </div>
                    <div class="card-details">
                        <p>Breve descripción</p>
                        <div class="info-section">
                            <p>Fecha publicación</p>
                            <img src="{{ asset('img/separador.png') }}" />
                            <p>Tipo contrato</p>
                        </div>
                        <div class="info-empresa">
                            <img src="{{ asset('img/ofer-abierta.png') }}" />
                            <h4>Nombre empresa</h4>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="card-ofer">
                    <div class="card-header">
                        <h3>Trabajo</h3>
                    </div>
                    <div class="card-details">
                        <p>Breve descripción</p>
                        <div class="info-section">
                            <p>Fecha publicación</p>
                            <img src="{{ asset('img/separador.png') }}" />
                            <p>Tipo contrato</p>
                        </div>
                        <div class="info-empresa">
                            <img src="{{ asset('img/ofer-abierta.png') }}" />
                            <h4>Nombre empresa</h4>
                        </div>
                    </div>
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