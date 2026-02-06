import React, { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import "../../../css/centro/styles.css";
import "../../../css/styles.css";


const Informes = () => {
    const navigate = useNavigate();
    const [notificaciones, setNotificaciones] = useState([]);
    const [historico, setHistorico] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        if (!localStorage.getItem('usuario')) {
            navigate('/login', { replace: true });
        }
    }, [navigate]);

    useEffect(() => {
        const cargarDatos = async () => {
            try {
                setLoading(true);
                setError('');
                
                const [notifRes, histRes] = await Promise.all([
                    fetch('/api/centro/notificaciones'),
                    fetch('/api/centro/historico-ofertas')
                ]);

                const notifData = await notifRes.json();
                const histData = await histRes.json();

                if (!notifRes.ok) {
                    setError(notifData.error || 'Error al cargar notificaciones');
                } else {
                    setNotificaciones(notifData.notificaciones || []);
                }

                if (!histRes.ok) {
                    setError(histData.error || 'Error al cargar histórico');
                } else {
                    setHistorico(histData.ofertas || []);
                }

            } catch (err) {
                setError('Error de conexión');
            } finally {
                setLoading(false);
            }
        };

        cargarDatos();
    }, []);

    const marcarLeida = async (id) => {
        try {
            const response = await fetch(`/api/centro/notificaciones/${id}/leida`, {
                method: 'PUT'
            });
            if (response.ok) {
                setNotificaciones(prev => prev.filter(n => n.id !== id));
            }
        } catch (err) {
            setError('Error al marcar notificación');
        }
    };

    const marcarTodas = async () => {
        try {
            const response = await fetch('/api/centro/notificaciones/leidas', {
                method: 'PUT'
            });
            if (response.ok) {
                setNotificaciones([]);
            }
        } catch (err) {
            setError('Error al marcar notificaciones');
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

    const handleLogout = () => {
        // Limpiar el almacenamiento local
        localStorage.removeItem('usuario');
        // Redirigir al inicio y evita volver atrás
        navigate('/', { replace: true });
        // Forzar recarga si es necesario
        window.location.reload();
    };

    return (
        <div className="DIV-CENTRO">
        
            <header className="HEADER">
                <div className="logo">
                    <Link to="/centro/dashboard_centro">
                        <img src="/img/logo-purp.png" />
                    </Link>
                </div>
            
                <div className="navegar">
                    <Link to="/centro/gestion_titulos">Gestión Títulos</Link>
                    <img src="/img/separador.png" alt="Separador"/>
                    <Link to="/centro/informes">Informes</Link>
                </div>
            
                <div className="user">
                    <img src="/img/user.png" />
                    <div className="desplegable">
                        <button onClick={handleLogout}>Cerrar sesión</button>
                    </div>
                </div>
    
            </header>
    
            <div className="CONTENIDO">
                {loading && <div>Cargando...</div>}
                {error && <div className="error">{error}</div>}

                <div className="CENTRO">
                    <table>
                        <thead>
                            <tr>
                                <th colSpan="3"><h1>Notificaciones pendientes</h1></th>
                                <th>
                                    <button className="btn-aceptar" onClick={marcarTodas}>MARCAR TODAS</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {notificaciones.length > 0 ? (
                                notificaciones.map(n => (
                                    <tr key={n.id}>
                                        <td>{n.mensaje}</td>
                                        <td>{formatDate(n.fecha)}</td>
                                        <td>
                                            <button className="btn-aceptar" onClick={() => marcarLeida(n.id)}>LEÍDA</button>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                !loading && (
                                    <tr>
                                        <td colSpan="3" style={{textAlign: 'center'}}>No hay notificaciones</td>
                                    </tr>
                                )
                            )}
                        </tbody>
                    </table>
                </div>

                <div className="CENTRO">
                    <table>
                        <thead>
                            <tr>
                                <th colSpan="3"><h1>Histórico de ofertas</h1></th>
                            </tr>
                        </thead>
                        <tbody>
                            {historico.length > 0 ? (
                                historico.map(oferta => (
                                    <tr key={oferta.id}>
                                        <td>{oferta.nombre}</td>
                                        <td>{oferta.empresa || 'Empresa'}</td>
                                        <td>{formatDate(oferta.fecha_cierre)}</td>
                                    </tr>
                                ))
                            ) : (
                                !loading && (
                                    <tr>
                                        <td colSpan="3" style={{textAlign: 'center'}}>No hay histórico</td>
                                    </tr>
                                )
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
    };
        
export default Informes;
