import React, { useEffect, useState } from "react";
import { Link, useNavigate, useParams } from 'react-router-dom';
import "../../../css/empresa/styles.css";
import "../../../css/styles.css";

function DashboardEmpresa() {
    const navigate = useNavigate();
    // Estado local que almacena la lista de ofertas laborales.
    const [ofertas, setOfertas] = useState([]);
    // Estado local para indicar si los datos aún se están cargando.
    const [loading, setLoading] = useState(true);
    // Estado local para manejar mensajes de error.
    const [error, setError] = useState('');

    // Comprueba si el usuario está autenticado. Si no, lo redirige al inicio de sesión.
    if (!localStorage.getItem('usuario')) {
        // Si el usuario no pertenece al rol de empresa, lo redirige al inicio de sesión.
        navigate('/login', { replace: true });
      } else { 
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        if (!usuario.id_emp) {
          navigate('/login', { replace: true });
        }
  
    }

    // Función para cargar las ofertas abiertas desde el backend.
    useEffect(() => {
        const fetchOfertas = async () => {
            try {
                // Comprueba la existencia de un usuario autenticado y que pertenece a una empresa.
                const usuario = JSON.parse(localStorage.getItem('usuario'));
                if (!usuario || !usuario.id_emp) {
                    navigate('/login', { replace: true });
                    return;
                }

                // Solicita las ofertas laborales abiertas del backend para la empresa específica.
                const response = await fetch(`/api/ofertas/abiertas?id_emp=${usuario.id_emp}`);

                // Si la solicitud falla, establece un mensaje de error.
                if (!response.ok){ 
                    setError("Error al cargar las ofertas");
                }

                const data = await response.json();
                // Actualiza el estado con la lista de ofertas obtenida.
                setOfertas(data);
            } catch (error) {
                // Captura errores de conexión o problemas en la solicitud al backend.
                setError("Error al cargar ofertas:", error);
            } finally {
                setLoading(false);
            }
        };
        // Llama a la función para cargar las ofertas cuando el componente se monta.
        fetchOfertas();
    }, []);

    // Función para cerrar una oferta laboral específica.
    const cerrarOferta = async (id) => {
        try {
            // Realiza una solicitud PUT para actualizar el estado de la oferta en el backend.
            const response = await fetch(`/api/ofertas/${id}/cerrar`, {
                method: "PUT",
                headers: {
                "Content-Type": "application/json",
            },
        });
        // Muestra un mensaje de error si la solicitud falla.
        if (!response.ok){
            setError("Error al cerrar la oferta");
        }

        // Actualiza el estado de la oferta como cerrada (-1)
        setOfertas((prevOfertas) =>
            prevOfertas.map((oferta) =>
            oferta.id === id ? { ...oferta, abierta: -1 } : oferta
            )
        );
        } catch (error) {
            setError("Error al cerrar la oferta:", error);
        }
    };

    // Redirige al usuario al formulario para crear una nueva oferta laboral.
    const handleCrearOferta = () => {
        navigate('/empresa/crear_oferta');
    };

    // Redirige al usuario al formulario para asignar la oferta a un candidato.
    const handleAsignarOferta = (id) => {
        navigate(`/empresa/asignar_oferta/${id}`);
    };

    const handleLogout = () => {
        // Limpiar el almacenamiento local
        localStorage.removeItem('usuario');
        // Redirigir al inicio y evita volver atrás
        navigate('/', { replace: true });
        // Forzar recarga si es necesario
        window.location.reload();
    };

    // Función que retorna los botones según el estado de la oferta laboral.
    const renderBotonesOferta = (oferta) => {
        switch(oferta.abierta) {
            case -1: // Oferta cerrada por otro motivos
                return (
                    <>
                        <button className="btn-modificar" disabled>
                            ASIGNAR
                        </button>
                        <button className="btn-cerrar" disabled>
                            CERRADA
                        </button>
                    </>
                );
            case 0: // Oferta abierta
                return (
                    <>
                        <button
                            className="btn-modificar"
                            onClick={() => handleAsignarOferta(oferta.id)}
                        >
                            ASIGNAR
                        </button>
                        <button
                            className="btn-cerrar"
                            onClick={() => cerrarOferta(oferta.id)}
                        >
                            CERRAR
                        </button>
                    </>
                );
            case 1: // Oferta asignada (cerrada)
                return (
                    <>
                        <button className="btn-modificar" disabled>
                            ASIGNADA
                        </button>
                        <button className="btn-cerrar" disabled>
                            CERRAR
                        </button>
                    </>
                );
            default:
                return null;
        }
    };

  return (
    <div className="OFERTAS-DEL-CENTRO">
      <header className="HEADER">
        <div className="logo">
          <img src="/img/logo-purp.png" alt="Logo" />
        </div>
        <div className="user">
          <img src="/img/user.png" alt="User" />
          <div className="desplegable">
            <button onClick={handleLogout}>Cerrar sesión</button>
          </div>
        </div>
      </header>

      <div className="CONTENIDO">
        <div className="OFERTAS">
          <table>
            <thead>
              <tr>
                <th colSpan="2">
                  <h1>Mis Ofertas Abiertas</h1>
                </th>
                <th>
                  <button type="submit" className="BOTON-CREAR"
                  onClick={() => handleCrearOferta()}>
                    CREAR OFERTA
                  </button>
                </th>
              </tr>
            </thead>
            {loading && <div>Cargando empresas...</div>}
            {error && <div className="error">{error}</div>}
            <tbody>
              {ofertas.map((oferta) => (
                <tr key={oferta.id}>
                  <td>{oferta.nombre}</td>
                  <td>{oferta.breve_desc}</td>
                  <td>
                    {renderBotonesOferta(oferta)}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
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
}

export default DashboardEmpresa;

