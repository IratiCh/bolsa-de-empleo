<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="{{ asset('css/centro/styles.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
</head>

<body>
  <div class="DIV-CENTRO">

    <header class="HEADER">
      <div class="logo">
        <a href="dashboard_centro.blade.php">
          <img src="{{ asset('img/logo-purp.png') }}" />
        </a>
      </div>

      <div class="navegar">
        <a href="gestion_titulos.blade.php">Gestión Títulos</a>
        <img src="{{ asset('img/separador.png') }}" />
        <a href="informes.blade.php">Informes</a>
      </div>

      <div class="user">
        <img src="{{ asset('img/user.png') }}" />
        <div class="desplegable">
          <a href="../index.blade.php">Cerrar sesión</a>
        </div>
      </div>

    </header>

    <div class="CONTENIDO">
        <div class="CENTRO">
            <h1>Modificar Título</h1>
            <form>
                <label for="text">Nombre</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Nombre">
                
                <div class="btn-titulo">
                    <button type="submit" class="mod-titulo">MODIFICAR TÍTULO</button>
                </div>
            </form>
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