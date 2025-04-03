<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="{{ asset('css/demandante/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
  <div class="DIV-DEMANDANTE PERFIL">

    <header class="HEADER-PERFIL">
      <div class="logo">
        <a href="dashboard_demandante.blade.php">
          <img src="{{ asset('img/logo-purp.png') }}" />
        </a>
      </div>
      <div class="user">
        <a href="perfil_demandante.blade.php">
          <img src="{{ asset('img/user.png') }}" />
      </div>

    </header>

    <div class="DEMANDANTE">

      <div class="perfil-container">
        <div class="perfil-info">
          <img src="{{ asset('img/email.png') }}" />
          <div class="perfil-texto">
            <p>Nombre Apellidos</p>
            <p>Email@email.email</p>
          </div>
        </div>

        <div class="btn-perfil">
          <a href="ofertas_inscritas.blade.php">
            <button type="submit" class="act-ofertas-apun">OFERTAS APUNTADAS</button>
          </a>
          <a href="../index.blade.php">
            <button type="submit" class="btn-logout">CERRAR SESIÓN</button>
          </a>
        </div>
      </div>

      <form>
        <table class="table-perfil">
          <tbody>
            <h1>Datos Personales</h1>
            <tr>
              <td colspan="2">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Nombre">
              </td>
              <td colspan="2">
                <label for="text">Primer Apellido</label>
                <input type="text" id="apellido1" name="apellido1" required placeholder="Primer Apellido">
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <label for="apellido2">Segundo Apellido</label>
                <input type="text" id="apellido2" name="apellido2" required placeholder="Segundo Apellido">
              </td>
              <td>
                <label for="telefono">Teléfono</label>
                <input type="number" id="telefono" name="telefono" required placeholder="Teléfono">
              </td>
            </tr>
            <tr>
              <td>
                <label for="passw">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="Contraseña">
              </td>
            </tr>
          </tbody>
        </table>
        <div class="btn-datos">
          <button type="submit" class="act-datos">ACTUALIZAR DATOS</button>
        </div>
      </form>


      <form>
        <table class="table-titulos">
          <tbody>
            <h1>Mis Títulos</h1>
            <tr>
              <td colspan="2">
                <h2>Primer Título</h2>
              </td>
            </tr>
            <tr>
              <td>
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Nombre">
              </td>
              <td>
                <label for="centro">Centro</label>
                <input type="text" id="centro" name="centro" required placeholder="Centro">
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label for="situacionCurso">Situación del Curso</label>
                <select name="select">
                  <option value="value1" selected disabled>Situación del Curso</option>
                  <option value="value1">Value 1</option>
                  <option value="value2">Value 2</option>
                  <option value="value3">Value 3</option>
                </select>
              </td>
              <td>
                <label for="año">Año de Adquisición</label>
                <input type="date" id="año" name="año" required placeholder="Año">
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <h2>Segundo Título</h2>
              </td>
            </tr>
            <tr>
              <td>
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Nombre">
              </td>
              <td>
                <label for="centro">Centro</label>
                <input type="text" id="centro" name="centro" required placeholder="Centro">
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label for="situacionCurso">Situación del Curso</label>
                <select name="select">
                  <option value="value1" selected disabled>Situación del Curso</option>
                  <option value="value1">Value 1</option>
                  <option value="value2">Value 2</option>
                  <option value="value3">Value 3</option>
                </select>
              </td>
              <td>
                <label for="año">Año de Adquisición</label>
                <input type="date" id="año" name="año" required placeholder="Año">
              </td>
            </tr>
          </tbody>
        </table>
        <div class="btn-info">
          <button type="submit" class="act-info">GUARDAR INFORMACIÓN</button>
        </div>
      </form>
    </div>


    <footer class="global-footer footer-perfil">
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