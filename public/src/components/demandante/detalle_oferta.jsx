import React, { useEffect, useState } from "react";
import { Link, useNavigate, useParams } from 'react-router-dom';
import "../../../css/demandante/styles.css";
import "../../../css/styles.css";

function DetalleOferta() {
    const { id } = useParams();
    const navigate = useNavigate();
    // Estado local para almacenar los detalles de la oferta.
    const [oferta, setOferta] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    // Estado local para verificar si el usuario está inscrito en la oferta.
    const [isInscrito, setIsInscrito] = useState(false);
    // Estado local para indicar si se está procesando la inscripción.
    const [inscribiendo, setInscribiendo] = useState(false);

    
    // Obtiene la información del usuario desde el almacenamiento local.
    useEffect(() => {
        // Obtiene la información del usuario desde el almacenamiento local.
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        if (!usuario) {
            navigate('/login', { replace: true });
        }
    }, [navigate]);

    // Función para cargar los detalles de la oferta desde el backend.
    useEffect(() => {
        const fetchOferta = async () => {
            try {
                setLoading(true);
                setError('');
                
                // Obtiene la información del usuario autenticado.
                const usuario = JSON.parse(localStorage.getItem('usuario'));
                if (!usuario?.id_dem) {
                    navigate('/login');
                    return;
                }

                // Solicita los detalles de la oferta al servidor.
                const response = await fetch(`/api/demandante/ofertas/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });
                
                const data = await response.json(); // <-- Solo una vez

                // Maneja errores si la solicitud no es exitosa.
                if (!response.ok) {
                    setError(data.error || 'Error al cargar la oferta');
                    return;
                }

                // Maneja el caso en que no se recibieron datos válidos.
                if (!data) {
                    setError('No se recibieron datos de la oferta');
                    return;
                }

                // Almacena los detalles de la oferta en el estado local.
                setOferta(data);

                // Verificar inscripción
                const inscResponse = await fetch(
                    `/api/demandante/ofertas/${id}/inscrito?id_dem=${usuario.id_dem}`, 
                    {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('token')}`
                        }
                    }
                );
                
                const inscData = await inscResponse.json();

                if (!inscResponse.ok) {
                    setError(inscData.error || 'Error al verificar inscripción');
                    return;
                }

                // Actualiza el estado con la información de inscripción.
                setIsInscrito(inscData.inscrito || false);
                                
            } catch (err) {
                setError(err.message);
                console.error("Error detallado:", err);
            } finally {
                setLoading(false);
            }
        };

        // Llama a la función para cargar los datos de la oferta cuando el componente se monta.
        fetchOferta();
    }, [id, navigate]);

    // Función para inscribir al usuario en la oferta.
    const handleInscripcion = async () => {
        setInscribiendo(true);
        setError('');
        
        try {
            // Obtiene la información del usuario desde el almacenamiento local.
            const usuario = JSON.parse(localStorage.getItem('usuario'));
            const response = await fetch(`/api/demandante/ofertas/${id}/inscribirse`, {
                method: 'POST',
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
                setError(data.error || 'Error al inscribirse');
            }
            
            // Actualiza el estado para indicar que el usuario está inscrito.
            setIsInscrito(true);
            
        } catch (err) {
            setError(err.message);
            console.error("Error en inscripción:", err);
        } finally {
            setInscribiendo(false);
        }
    };

    // Función para formatear una fecha en un formato legible.
    const formatDate = (dateString) => {
        if (!dateString) return 'No especificada';
        try {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('es-ES', options);
        } catch {
            return dateString;
        }
    };

    return(
        <div className="DIV-DEMANDANTE">

            <header className="HEADER">
                <div className="logo">
                    <Link to="/demandante/dashboard_demandante">
                        <img src="/img/logo-purp.png" />
                    </Link>
                </div>
                <div className="user">
                    <Link to="/demandante/perfil_demandante">
                        <img src="/img/user.png" />
                    </Link>
                </div>

            </header>

                <div className="CONTENIDO">

                
                {oferta ? (
                    <div className="DEMANDANTE">
                        {loading && <div className="loading">Cargando oferta...</div>}
                        {error && <div className="error">{error}</div>}
                        <h1>{oferta.nombre}</h1>
                        <div className="det-ofer-container">
                            <div className="left-oferta">
                                <p>{oferta.desc}</p>
                                <h2>Observaciones</h2>
                                <p>{oferta.obs || 'No hay observaciones'}</p>
                                <h2>Horario</h2>
                                <p>{oferta.horario || 'Horario no especificado'}</p>
                                <h2>Tipo de Contrato</h2>
                                <p>{oferta.tipo_contrato || (oferta.tipoContrato?.nombre || 'No especificado')}</p>
                            </div>
                            <div className="right-oferta">
                                <div className="right-section-ofer">
                                    <h3>{oferta.empresa_nombre || 'Empresa no especificada'}</h3>
                                    <p>{oferta.empresa_localidad || 'Localidad no especificada'}</p>
                                </div>
                                <div className="right-section-ofer">
                                    <h3>Fechas</h3>
                                    <p>Publicada: {formatDate(oferta.fecha_pub)}</p>
                                    {oferta.fecha_cierre && <p>Cerrada: {formatDate(oferta.fecha_cierre)}</p>}
                                </div>
                                <div className="right-section-ofer">
                                    <h3>Vacantes</h3>
                                    <p>{oferta.num_puesto} puestos disponibles</p>
                                </div>
                                <div className="right-section-ofer">
                                    <h3>Título Necesario</h3>
                                    <p>{oferta.titulos?.join(', ') || 'No se requieren títulos específicos'}</p>
                                </div>
                            </div>
                        </div>
                        <div className="btn-insc">
                            {oferta.abierta !== 0 ? (
                                <button className="oferta-cerrada" disabled>
                                    OFERTA CERRADA
                                </button>
                            ) : isInscrito ? (
                                <button className="inscrito-ofer" disabled>
                                    YA ESTÁS INSCRITO
                                </button>
                            ) : (
                                <button 
                                    className="inscribirse-ofer" 
                                    onClick={handleInscripcion}
                                    disabled={inscribiendo}
                                >
                                    {inscribiendo ? 'INSCRIBIENDO...' : 'INSCRIBIRSE'}
                                </button>
                            )}
                        </div>
                        
                    </div>
                    ) : (
                        <div>No se encontraron datos de la oferta.</div>
                      )}
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

export default DetalleOferta;