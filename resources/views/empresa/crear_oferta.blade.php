<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="{{ asset('css/empresa/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
  <div class="OFERTAS-DEL-CENTRO">

    <header class="HEADER">
      <div class="logo">
        <a href="dashboard_empresa.blade.php">
          <img src="{{ asset('img/logo-purp.png') }}" />
        </a>
      </div>
      <div class="user">
        <img src="{{ asset('img/user.png') }}" />
        <div class="desplegable">
          <a href="../index.html">Cerrar sesión</a>
        </div>
      </div>

    </header>

    <div class="CONTENIDO">
      <div class="OFERTAS">
        <h1>Crear Nueva Oferta</h1>

        <form>
          <table class="table-ofert">
            <tbody>
              <tr>
                <td colspan="2">
                  <label for="text">Nombre</label>
                  <input type="text" id="nombre" name="nombre" required placeholder="Nombre">
                </td>
                <td colspan="2">
                  <label for="text">Tipo de Contrato</label>
                  <select name="select">
                    <option value="value1" selected disabled>Tipo de Contrato</option>
                    <option value="value1">Value 1</option>
                    <option value="value2">Value 2</option>
                    <option value="value3">Value 3</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <label for="text">Breve descripción</label>
                  <input type="text" id="nombre" name="nombre" required placeholder="Breve descripción">
                </td>
                <td>
                  <label for="puestos">Puestos</label>
                  <input type="number" id="puestos" name="puestos" required placeholder="Puestos">
                </td>
                <td>
                  <label for="horario">Horario</label>
                  <input type="text" id="horario" name="horario" required placeholder="Horario">
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <label for="text">Título Necesario</label>
                  <select name="select">
                    <option value="value1" selected disabled>Título Necesario</option>
                    <option value="value1">Value 1</option>
                    <option value="value2">Value 2</option>
                    <option value="value3">Value 3</option>
                  </select>
                </td>
                <td colspan="2">
                  <label for="text">Otro Título Necesario</label>
                  <select name="select">
                    <option value="value1" selected disabled>Otro Título Necesario</option>
                    <option value="value1">Value 1</option>
                    <option value="value2">Value 2</option>
                    <option value="value3">Value 3</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <label for="text">Descripción</label>
                  <textarea name="desc" rows="10" cols="50" placeholder="Descripción"></textarea>
                </td>
                <td colspan="2">
                  <label for="text">Observaciones</label>
                  <textarea name="obs" rows="10" cols="50" placeholder="Observaciones"></textarea>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="btn-oferta">
            <button type="submit" class="crear-oferta">CREAR OFERTA</button>
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