import React, { useEffect, useState } from "react";
import { Link, useNavigate } from 'react-router-dom';
import "../../../css/demandante/styles.css";
import "../../../css/styles.css";

function OfertasInscritas() {
    const navigate = useNavigate();
    const [ofertas, setOfertas] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    // Verificar autenticación y cargar ofertas
    useEffect(() => {
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        if (!usuario) {
            navigate('/login', { replace: true });
            return;
        }

        const cargarOfertasInscritas = async () => {
            try {
                setLoading(true);
                setError('');
                
                const response = await fetch(`/api/demandante/ofertas-inscritas?id_dem=${usuario.id_dem}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });
                
                const data = await response.json();

                if (!response.ok) {
                    setError(data.error || 'Error al cargar ofertas inscritas');
                }

                setOfertas(data.ofertas || []);
            } catch (err) {
                setError(err.message);
                console.error("Error:", err);
            } finally {
                setLoading(false);
            }
        };

        cargarOfertasInscritas();
    }, [navigate]);

    const handleCancelarInscripcion = async (idOferta) => {

        try {
            const usuario = JSON.parse(localStorage.getItem('usuario'));
            const response = await fetch(`/api/demandante/ofertas/${idOferta}/cancelar-inscripcion`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({
                    id_demandante: usuario.id_dem
                })
            });

            const data = await response.json();
            
            if (!response.ok) {
                setError(data.error || 'Error al cancelar inscripción');
            }

            // Actualizar lista eliminando la oferta cancelada
            setOfertas(ofertas.filter(oferta => oferta.id !== idOferta));
            
        } catch (err) {
            setError(err.message);
            console.error("Error al cancelar inscripción:", err);
        }
    };

    return(
        <div className="DIV-DEMANDANTE">
            <header className="HEADER">
                <div className="logo">
                    <Link to="/demandante/dashboard_demandante">
                        <img src="/img/logo-purp.png" alt="Logo" />
                    </Link>
                </div>
                <div className="user">
                    <Link to="/demandante/perfil_demandante">
                        <img src="/img/user.png" alt="Usuario" />
                    </Link>
                </div>
            </header>

            <div className="CONTENIDO">
                <div className="DEMANDANTE">
                    {loading && <div className="loading">Cargando ofertas inscritas...</div>}
                    {error && <div className="error">{error}</div>}

                    <table>
                        <thead>
                            <tr>
                                <th colSpan="3">
                                    <h1>Gestionar Ofertas Inscritas</h1>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {ofertas.length > 0 ? (
                                ofertas.map((oferta) => (
                                    <tr key={oferta.id}>
                                        <td>
                                            {oferta.nombre}
                                        </td>
                                        <td>
                                            {oferta.breve_desc || 'Sin descripción breve'}
                                        </td>
                                        <td>
                                            {oferta.abierta === 0 ? (
                                                <button 
                                                    className="btn-cancelar"
                                                    onClick={() => handleCancelarInscripcion(oferta.id)}
                                                >
                                                    CANCELAR INSCRIPCIÓN
                                                </button>
                                            ) : (
                                                <button 
                                                    className="btn-cancelar" 
                                                    disabled
                                                >
                                                    OFERTA CERRADA
                                                </button>
                                            )}
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                !loading && (
                                    <tr>
                                        <td colSpan="3" style={{textAlign: 'center'}}>
                                            No estás inscrito en ninguna oferta
                                        </td>
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

export default OfertasInscritas;
