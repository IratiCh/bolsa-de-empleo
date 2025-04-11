import React, { useEffect, useState } from "react";
import { Link, useNavigate, useParams } from 'react-router-dom';
import "../../../css/empresa/styles.css";
import "../../../css/styles.css";

function CrearOferta() {
  const navigate = useNavigate();
  // Estado inicial para almacenar los datos del formulario de creación de oferta.
    const [formData, setFormData] = useState({
        nombre: '',
        breve_desc: '',
        desc: '',
        num_puesto: '',
        horario: '',
        obs: '',
        id_tipo_cont: '',
        titulos: []
    });
    // Estado para almacenar los tipos de contrato disponibles.
    const [tiposContrato, setTiposContrato] = useState([]);
    // Estado para almacenar los títulos disponibles para seleccionar.
    const [titulos, setTitulos] = useState([]);
    // Estado para indicar si los datos aún se están cargando.
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');

    // Verifica si el usuario está autenticado. Si no, lo redirige al inicio de sesión.
    if (!localStorage.getItem('usuario')) {
      navigate('/login', { replace: true });
    } else { 
      const usuario = JSON.parse(localStorage.getItem('usuario'));
      if (!usuario.id_emp) {
        navigate('/login', { replace: true });
      }

    }

    // Función para cargar los tipos de contrato y los títulos disponibles desde el backend.
    useEffect(() => {
      const fetchData = async () => {
          try {
              setLoading(true);
              
              // Solicita los tipos de contrato al servidor.
              const tiposResponse = await fetch('/api/ofertas/tipos-contrato');
              const tiposData = await tiposResponse.json();
              
              // Actualiza el estado con los tipos de contrato obtenidos.
              if (tiposData.success) {
                  setTiposContrato(tiposData.tipos);
              }
              
              // Solicita los títulos al servidor.
              const titulosResponse = await fetch('/api/ofertas/titulos');
              const titulosData = await titulosResponse.json();
              
              // Actualiza el estado con los títulos obtenidos.
              if (titulosData.success) {
                  setTitulos(titulosData.titulos);
              }
              
          } catch (error) {
            // Maneja errores durante la solicitud al servidor.
              setError('Error al cargar datos iniciales');
          } finally {
              setLoading(false);
          }
      };
      
      // Ejecuta la función para cargar los datos al montar el componente.
      fetchData();
  }, []);

  const handleChange = (e) => {
    // Función para actualizar los valores del formulario según los cambios del usuario.
      const { name, value } = e.target;
      setFormData(prev => ({
          ...prev,
          // Actualiza el campo correspondiente en el estado formData.
          [name]: value
      }));
  };

  // Función para actualizar los títulos seleccionados en el formulario.
  const handleSelectTitulo = (e, index) => {
      const { value } = e.target;
      // Copia la lista actual de títulos seleccionados.
      const newTitulos = [...formData.titulos];
      // Actualiza el título en el índice correspondiente.
      newTitulos[index] = value;
      setFormData(prev => ({
          ...prev,
          // Actualiza el estado con la nueva lista de título.
          titulos: newTitulos
      }));
  };

  const handleSubmit = async (e) => {
    // Previene el comportamiento predeterminado del formulario (recarga de la página).
      e.preventDefault();
      // Resetea los mensajes y activa el estado de carga.
      setError('');
      setSuccess('');
      setLoading(true);

      // Verifica si el usuario está autenticado y pertenece al rol de empresa.
      try {
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        if (!usuario || !usuario.id_emp) {
          navigate('/login', { replace: true });
          return;
        }

        // Utiliza el método HTTP POST para enviar los datos del formulario al servidor.
        const response = await fetch('/api/ofertas/crear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                ...formData,
                // Filtra los títulos para eliminar entradas vacías.
                titulos: formData.titulos.filter(t => t),
                // Incluye el ID de la empresa en el cuerpo de la solicitud.
                id_emp: usuario.id_emp
            })
          });
          
          // Parsea la respuesta del servidor.
          const data = await response.json();

          // Si la solicitud fue exitosa, muestra un mensaje de éxito.
          if (response.ok) {
            setSuccess('Oferta creada correctamente');
            // Reinicia los campos del formulario.
            setError('');
            setFormData({ 
              nombre: '',
              breve_desc: '',
              desc: '',
              num_puesto: '',
              horario: '',
              obs: '',
              id_tipo_cont: '',
              titulos: []
            });
          }else {
            // Si hay un error en la solicitud, muestra el mensaje correspondiente.
            setError(data.message || 'Error al crear oferta');
            setSuccess('');
          }
      } catch (error) {
          // Captura errores durante la comunicación con el servidor.s
          setError(error.message);
      } finally {
          setLoading(false);
      }
  };

  // Función para cerrar sesión y limpiar los datos del usuario.
  const handleLogout = () => {
      localStorage.removeItem('usuario');
      navigate('/', { replace: true });
      window.location.reload();
  };

    return (
        <div className="OFERTAS-DEL-CENTRO">

        <header className="HEADER">
          <div className="logo">
            <Link to="/empresa/dashboard_empresa">
              <img src="/img/logo-purp.png" />
            </Link>
          </div>
          <div className="user">
            <img src="/img/user.png" />
            <div className="desplegable">
            <button onClick={handleLogout}>Cerrar sesión</button>
            </div>
          </div>
    
        </header>
    
        <div className="CONTENIDO">
          <div className="OFERTAS">
            <h1>Crear Nueva Oferta</h1>
            <form onSubmit={handleSubmit}>
              <table className="table-ofert">
                <tbody>
                  <tr>
                    <td colspan="2">
                      <label htmlFor="text">Nombre</label>
                      <input type="text" id="nombre" name="nombre" required placeholder="Nombre" value={formData.nombre} onChange={handleChange}/>
                    </td>
                    <td colspan="2">
                      <label htmlFor="text">Tipo de Contrato</label>
                      <select name="id_tipo_cont"
                          required
                          value={formData.id_tipo_cont}
                          onChange={handleChange}
                      >
                        <option value="" selected disabled>Tipo de Contrato</option>
                        {tiposContrato.map(tipo => (
                          <option key={tipo.id} value={tipo.id}>
                              {tipo.nombre}
                          </option>
                        ))}
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <label htmlFor="breve_desc">Breve descripción</label>
                      <input type="text" id="breve_desc" name="breve_desc" required placeholder="Breve descripción" value={formData.breve_desc} onChange={handleChange}/>
                    </td>
                    <td>
                      <label htmlFor="num_puesto">Puestos</label>
                      <input type="number" id="num_puesto" name="num_puesto" required placeholder="Puestos" value={formData.num_puesto} onChange={handleChange}/>
                    </td>
                    <td>
                      <label htmlFor="horario">Horario</label>
                      <input type="text" id="horario" name="horario" required placeholder="Horario" value={formData.horario} onChange={handleChange}/>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <label htmlFor="text">Título Necesario</label>
                      <select name="titulo_principal" 
                          required 
                          value={formData.titulos[0] || ''} 
                          onChange={(e) => handleSelectTitulo(e, 0)} 
                      >
                        <option value="" selected disabled>Título Necesario</option>
                        {titulos.map(titulo => (
                          <option key={titulo.id} value={titulo.id}>
                              {titulo.nombre}
                          </option>
                        ))}
                      </select>
                    </td>
                    <td colspan="2">
                      <label htmlFor="text">Otro Título Necesario</label>
                      <select name="titulo_secundario" 
                          value={formData.titulos[1] || ''}
                          onChange={(e) => handleSelectTitulo(e, 1)}
                      >
                        <option value="" selected disabled>Otro Título Necesario</option>
                        {titulos.map(titulo => (
                          <option key={titulo.id} value={titulo.id}>
                              {titulo.nombre}
                          </option>
                        ))}
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <label htmlFor="text">Descripción</label>
                      <textarea name="desc" required rows="10" cols="50" placeholder="Descripción" value={formData.desc} onChange={handleChange}></textarea>
                    </td>
                    <td colspan="2">
                      <label htmlFor="text">Observaciones</label>
                      <textarea name="obs" required rows="10" cols="50" placeholder="Observaciones" value={formData.obs} onChange={handleChange}></textarea>
                    </td>
                  </tr>
                </tbody>
              </table>
              {error && <div className="error">{error}</div>}
              {success && <div className="success">{success}</div>}
              <div className="btn-oferta">
                <button type="submit" className="crear-oferta">CREAR OFERTA</button>
              </div>
            </form>
          </div>
        </div>
    
    
        <footer className="global-footer">
            <div className="footer-container">
                <div className="footer-section global-section">
                <h4>Información General</h4>
                <ul>
                    <li><a href="#">Quiénes somos</a></li>
                    <li><a href="#">Nuestro propósito</a></li>
                    <li><a href="#">Contacto</a></li>
                </ul>
                </div>
                <div className="footer-section global-section">
                <h4>Ayuda al Usuario</h4>
                <ul>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Plataforma</a></li>
                    <li><a href="#">Soporte técnico</a></li>
                </ul>
                </div>
                <div className="footer-section global-section">
                <h4>Legal y Privacidad</h4>
                <ul>
                    <li><a href="#">Términos y condiciones</a></li>
                    <li><a href="#">Política de privacidad</a></li>
                    <li><a href="#">Aviso legal</a></li>
                </ul>
                </div>
                <div className="footer-section global-section subscribe">
                <h4>Suscríbete</h4>
                <div>
                    <input className="global-input-subscribe" type="email" placeholder="Email"/>
                    <button>
                    <img src="/img/suscribe-purp.png" />
                    </button>
                </div>
                <p>Suscríbete para enterarte de las nuevas ofertas de empleo.</p>
                </div>
            </div>
            <div className="footer-copy">
                <p>© 2025 Sara & Irati. Derechos reservados.
                <a href="#">Política de Privacidad</a>
                <a href="#">Términos de Servicio</a>
                </p>
                <div className="social-icons">
                    <img src="/img/fc-gray.png" />
                    <img src="/img/in-gray.png" />
                    <img src="/img/tw-gray.png" />
                    <img src="/img/pint-gray.png" />
                </div>
            </div>
        </footer>
      </div>
    );
};

export default CrearOferta;