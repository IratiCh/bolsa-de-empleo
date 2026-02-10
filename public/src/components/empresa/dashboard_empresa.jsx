import React, { useEffect, useState } from "react";
import { Link, useNavigate, useParams } from 'react-router-dom';
import "../../../css/empresa/styles.css";
import "../../../css/styles.css";

function DashboardEmpresa() {
    const navigate = useNavigate();
    // Estado local que almacena la lista de ofertas laborales.
    const [ofertas, setOfertas] = useState([]);
    const [historico, setHistorico] = useState([]);
    // Estado local para indicar si los datos aún se están cargando.
    const [loading, setLoading] = useState(true);
    const [loadingHistorico, setLoadingHistorico] = useState(true);
    // Estado local para manejar mensajes de error.
    const [error, setError] = useState('');
    const [errorHistorico, setErrorHistorico] = useState('');

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

    useEffect(() => {
        const fetchHistorico = async () => {
            try {
                const usuario = JSON.parse(localStorage.getItem('usuario'));
                if (!usuario || !usuario.id_emp) {
                    navigate('/login', { replace: true });
                    return;
                }

                const response = await fetch(`/api/empresa/historico-ofertas?id_emp=${usuario.id_emp}`);
                const data = await response.json();

                if (!response.ok) {
                    setErrorHistorico(data.error || 'Error al cargar histórico');
                    return;
                }

                setHistorico(data.ofertas || []);
            } catch (error) {
                setErrorHistorico('Error al cargar histórico');
            } finally {
                setLoadingHistorico(false);
            }
        };

        fetchHistorico();
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
            oferta.id === id ? { ...oferta, abierta: 1 } : oferta
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
            case 0: // Oferta abierta
                return (
                    <>
                        <button
                            className="btn-modificar"
                            onClick={() => handleAsignarOferta(oferta.id)}
                        >
                            ASIGNAR
                        </button>
                        <button className="btn-modificar" onClick={() => handleAsignarOferta(oferta.id)}>
                            SOLICITUDES
                        </button>
                        <button
                            className="btn-cerrar"
                            onClick={() => cerrarOferta(oferta.id)}
                        >
                            CERRAR
                        </button>
                    </>
                );
            case 1: // Oferta cerrada
                return (
                    <>
                        <button className="btn-modificar" disabled>
                            CERRADA
                        </button>
                        <button className="btn-modificar" onClick={() => handleAsignarOferta(oferta.id)}>
                            SOLICITUDES
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

    const formatDate = (dateString) => {
        if (!dateString) return 'No disponible';
        try {
            return new Date(dateString).toLocaleDateString('es-ES');
        } catch {
            return dateString;
        }
    };

    const formatCandidato = (oferta) => {
        if (!oferta?.candidato_nombre) return 'Sin candidato';
        if (oferta.candidato_tipo === 'externo') {
            return `${oferta.candidato_nombre} (Externo)`;
        }
        return oferta.candidato_nombre;
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
            <tbody>
              {loading && (
                <tr>
                  <td colSpan="3">Cargando ofertas...</td>
                </tr>
              )}
              {error && (
                <tr>
                  <td colSpan="3" className="error">{error}</td>
                </tr>
              )}
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

        <div className="OFERTAS">
          <table>
            <thead>
              <tr>
                <th colSpan="4">
                  <h1>Histórico de Ofertas</h1>
                </th>
              </tr>
            </thead>
            <tbody>
              {loadingHistorico && (
                <tr>
                  <td colSpan="4">Cargando histórico...</td>
                </tr>
              )}
              {errorHistorico && (
                <tr>
                  <td colSpan="4" className="error">{errorHistorico}</td>
                </tr>
              )}
              {historico.map((oferta) => (
                <tr key={`hist-${oferta.id}`}>
                  <td>{oferta.nombre}</td>
                  <td>{formatDate(oferta.fecha_cierre)}</td>
                  <td>{formatCandidato(oferta)}</td>
                  <td>
                    <button className="btn-modificar" onClick={() => handleAsignarOferta(oferta.id)}>
                      SOLICITUDES
                    </button>
                  </td>
                </tr>
              ))}
              {historico.length === 0 && !loadingHistorico && (
                <tr>
                  <td colSpan="4" style={{ textAlign: 'center' }}>No hay histórico</td>
                </tr>
              )}
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
