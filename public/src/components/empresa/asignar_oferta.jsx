import React, { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import "../../../css/empresa/styles.css";
import "../../../css/styles.css";

function AsignarOferta() {
    const { id } = useParams();
    const navigate = useNavigate();
    const [oferta, setOferta] = useState(null);
    const [inscritos, setInscritos] = useState([]);
    const [noInscritos, setNoInscritos] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [successMessage, setSuccessMessage] = useState('');

    useEffect(() => {
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        if (!usuario || !usuario.id_emp) {
            navigate('/login', { replace: true });
            return;
        }

        const cargarDatos = async () => {
            try {
                setLoading(true);
                setError('');
                
                const response = await fetch(`/api/empresa/asignar_oferta/${id}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });
                
                if (!response.ok) {
                    const errorData = await response.json();
                    setError(errorData.error || `Error ${response.status}`);
                }

                const data = await response.json();
                setOferta(data.oferta);
                setInscritos(data.inscritos);
                setNoInscritos(data.noInscritos);
            } catch (err) {
                setError(err.message);
                console.error("Error:", err);
            } finally {
                setLoading(false);
            }
        };

        cargarDatos();
    }, [id, navigate]);

    const handleAsignar = async (idDemandante) => {
        try {
            setError('');
            setSuccessMessage('');
            
            const response = await fetch(`/api/empresa/asignar_oferta/${id}/asignar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({
                    id_demandante: idDemandante
                })
            });
    
            const data = await response.json();
            
            if (!response.ok) {
                setError(data.error || 'Error al adjudicar');
            }
    
            setSuccessMessage(data.message);
            navigate('/empresa/dashboard_empresa');
    
        } catch (err) {
            console.error("Error al adjudicar:", err);
            setError(err.message);
        }
    };

    const handleLogout = () => {
        localStorage.removeItem('usuario');
        localStorage.removeItem('token');
        navigate('/', { replace: true });
    };

    return (
        <div className="OFERTAS-DEL-CENTRO">
            <header className="HEADER">
                <div className="logo">
                    <Link to="/empresa/dashboard_empresa">
                        <img src="/img/logo-purp.png" alt="Logo" />
                    </Link>
                </div>
                <div className="user">
                    <img src="/img/user.png" alt="Usuario" />
                    <div className="desplegable">
                        <button onClick={handleLogout}>Cerrar sesión</button>
                    </div>
                </div>
            </header>

            <div className="CONTENIDO">
                <div className="OFERTAS">
                    {loading && <div className="loading">Cargando datos...</div>}

                    {oferta && (
                        <>
                            <h1>{oferta.nombre}</h1>
                            
                            <h2>Inscritos</h2>
                            <table>
                                <tbody>
                                    {inscritos.length > 0 ? (
                                        inscritos.map((demandante) => (
                                            <tr key={`inscrito-${demandante.id}`}>
                                                <td>{demandante.nombre_completo}</td>
                                                <td>{demandante.email}</td>
                                                <td>{demandante.tel_movil}</td>
                                                <td>
                                                    <button className="btn-modificar" onClick={() => handleAsignar(demandante.id)}>
                                                        ASIGNAR
                                                    </button>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="4" style={{textAlign: 'center'}}>
                                                No hay demandantes inscritos
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>

                            <h2>Titulación Requerida - No inscritos</h2>
                            <table>
                                <tbody>
                                    {noInscritos.length > 0 ? (
                                        noInscritos.map((demandante) => (
                                            <tr key={`no-inscrito-${demandante.id}`}>
                                                <td>{demandante.nombre_completo}</td>
                                                <td>{demandante.email}</td>
                                                <td>{demandante.tel_movil}</td>
                                                <td>
                                                    <button 
                                                        className="btn-modificar"
                                                        onClick={() => handleAsignar(demandante.id)}
                                                    >
                                                        ASIGNAR
                                                    </button>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="4" style={{textAlign: 'center'}}>
                                                No hay demandantes con la titulación requerida
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </>
                    )}
                    
                    {error && <div className="error">{error}</div>}
                    {successMessage && <div className="success">{successMessage}</div>}
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
                    <img src="/img/fc-white.png" />
                    <img src="/img/in-white.png" />
                    <img src="/img/tw-white.png" />
                    <img src="/img/pint-white.png" />
                </div>
                </div>
            </footer>
        </div>
    );
};

export default AsignarOferta;