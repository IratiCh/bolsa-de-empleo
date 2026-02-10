import React, { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import "../../../css/empresa/styles.css";
import "../../../css/styles.css";

function AsignarOferta() {
    // Obtener el parámetro id 
    const { id } = useParams();
    const navigate = useNavigate();
    // Estado para almacenar los detalles de la oferta actual.
    const [oferta, setOferta] = useState(null);
    // Estado para almacenar la lista de candidatos inscritos en la oferta.
    const [inscritos, setInscritos] = useState([]);
    // Estado para almacenar la lista de posibles candidatos no inscritos.
    const [noInscritos, setNoInscritos] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [successMessage, setSuccessMessage] = useState('');
    const [externoNombre, setExternoNombre] = useState('');
    const [cvByDemandante, setCvByDemandante] = useState({});
    const [cvLoading, setCvLoading] = useState(false);
    const [cvError, setCvError] = useState('');
    const [cvExpanded, setCvExpanded] = useState({});

    useEffect(() => {
        // Obtiene la información del usuario autenticado desde el almacenamiento local.
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        if (!usuario || !usuario.id_emp) {
            navigate('/login', { replace: true });
            return;
        }

        // Función para cargar los datos de la oferta, inscritos y no inscritos.
        const cargarCv = async (demandantes) => {
            const ids = Array.from(new Set(demandantes.map(d => d.id)));
            if (ids.length === 0) {
                return;
            }
            setCvLoading(true);
            setCvError('');
            try {
                const results = await Promise.all(
                    ids.map(async (idDem) => {
                        try {
                            const res = await fetch(`/api/demandante/cv/${idDem}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                                }
                            });
                            const data = await res.json();
                            if (!res.ok) {
                                return [idDem, { error: true }];
                            }
                            return [idDem, data];
                        } catch {
                            return [idDem, { error: true }];
                        }
                    })
                );

                setCvByDemandante(prev => ({
                    ...prev,
                    ...Object.fromEntries(results)
                }));
            } catch (err) {
                setCvError('Error al cargar CV');
            } finally {
                setCvLoading(false);
            }
        };

        const cargarDatos = async () => {
            try {
                setLoading(true);
                setError('');
                
                // Solicita los datos de la oferta al backend utilizando el ID de la oferta.
                const response = await fetch(`/api/empresa/asignar_oferta/${id}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // Incluye el token de autorización para validar al usuario.
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });
                
                // Si la solicitud no es exitosa, muestra un mensaje de error.
                if (!response.ok) {
                    const errorData = await response.json();
                    setError(errorData.error || `Error ${response.status}`);
                }

                const data = await response.json();
                // Almacena los datos de la oferta
                setOferta(data.oferta);
                // Almacena la lista de candidatos inscritos.
                setInscritos(data.inscritos);
                // Almacena la lista de candidatos no inscritos.
                setNoInscritos(data.noInscritos);
                await cargarCv([...(data.inscritos || []), ...(data.noInscritos || [])]);
            } catch (err) {
                // Captura errores durante la comunicación con el servidor.
                setError(err.message);
                console.error("Error:", err);
            } finally {
                setLoading(false);
            }
        };

        // Ejecuta la función para cargar los datos cuando el componente se monta.
        cargarDatos();
    }, [id, navigate]);

    // Función para asignar un demandante a la oferta actual.
    const handleAsignar = async (idDemandante, externo = '') => {
        try {
            // Limpia mensajes previos.
            setError('');
            setSuccessMessage('');
            
            // Realiza una solicitud POST para asignar el demandante.
            const response = await fetch(`/api/empresa/asignar_oferta/${id}/asignar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({
                    id_demandante: idDemandante || null,
                    externo_nombre: externo || null
                })
            });
    
            const data = await response.json();
            
            // Si hay un error en la asignación, muestra el mensaje correspondiente.
            if (!response.ok) {
                setError(data.error || 'Error al adjudicar');
            }
    
            // Muestra el mensaje de éxito.
            setSuccessMessage(data.message);
            // Redirige al panel principal de la empresa.
            navigate('/empresa/dashboard_empresa');
    
        } catch (err) {
            // Captura errores durante la solicitud de asignación.
            console.error("Error al adjudicar:", err);
            setError(err.message);
        }
    };

    const handleAsignarExterno = async (e) => {
        e.preventDefault();
        if (!externoNombre.trim()) {
            setError('Indica el nombre del candidato externo');
            return;
        }
        await handleAsignar(null, externoNombre.trim());
    };

    // Función para cerrar sesión y limpiar datos del usuario.
    const handleLogout = () => {
        localStorage.removeItem('usuario');
        localStorage.removeItem('token');
        navigate('/', { replace: true });
    };

    const hasCvForm = (cvData) => {
        if (!cvData || !cvData.cv_form) return false;
        return Object.values(cvData.cv_form).some((value) => {
            if (value === null || value === undefined) return false;
            const str = String(value).trim();
            return str.length > 0;
        });
    };

    const renderCvCell = (idDemandante) => {
        const cvData = cvByDemandante[idDemandante];
        if (!cvData && cvLoading) {
            return <span>Cargando...</span>;
        }
        if (!cvData) {
            return <span>Sin CV</span>;
        }
        if (cvData.error) {
            return <span className="error">Error CV</span>;
        }

        const showForm = hasCvForm(cvData);
        const showPdf = !!cvData.cv_pdf_url;

        if (!showForm && !showPdf) {
            return <span>Sin CV</span>;
        }

        return (
            <div>
                {showForm ? (
                    <button
                        type="button"
                        className="btn-modificar"
                        onClick={() =>
                            setCvExpanded(prev => ({
                                ...prev,
                                [idDemandante]: !prev[idDemandante]
                            }))
                        }
                    >
                        {cvExpanded[idDemandante] ? 'Ocultar formulario' : 'Ver formulario'}
                    </button>
                ) : (
                    <span>Sin formulario</span>
                )}
                {" | "}
                {showPdf ? (
                    <a href={cvData.cv_pdf_url} target="_blank" rel="noreferrer">PDF</a>
                ) : (
                    <span>Sin PDF</span>
                )}
            </div>
        );
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
                            {oferta.abierta !== 0 && (
                                <div className="error">La oferta está cerrada. Solo puedes consultar solicitudes.</div>
                            )}
                            
                            <h2>Inscritos</h2>
                            <table>
                                <tbody>
                                    {inscritos.length > 0 ? (
                                        inscritos.map((demandante) => {
                                            const cvData = cvByDemandante[demandante.id];
                                            const showFormInline = cvExpanded[demandante.id] && hasCvForm(cvData);
                                            return (
                                                <React.Fragment key={`inscrito-${demandante.id}`}>
                                                    <tr>
                                                        <td>{demandante.nombre_completo}</td>
                                                        <td>{demandante.email}</td>
                                                        <td>{demandante.tel_movil}</td>
                                                        <td>{renderCvCell(demandante.id)}</td>
                                                        <td>
                                                            <button className="btn-modificar" onClick={() => handleAsignar(demandante.id)} disabled={oferta.abierta !== 0}>
                                                                ASIGNAR
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    {showFormInline && (
                                                        <tr>
                                                            <td colSpan="5">
                                                                <div className="cv-inline">
                                                                    <h4>CV (Formulario)</h4>
                                                                    <p><strong>Resumen:</strong> {cvData?.cv_form?.resumen || 'No disponible'}</p>
                                                                    <p><strong>Experiencia:</strong> {cvData?.cv_form?.experiencia || 'No disponible'}</p>
                                                                    <p><strong>Formación:</strong> {cvData?.cv_form?.formacion || 'No disponible'}</p>
                                                                    <p><strong>Habilidades:</strong> {cvData?.cv_form?.habilidades || 'No disponible'}</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    )}
                                                </React.Fragment>
                                            );
                                        })
                                    ) : (
                                        <tr>
                                            <td colSpan="5" style={{textAlign: 'center'}}>
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
                                        noInscritos.map((demandante) => {
                                            const cvData = cvByDemandante[demandante.id];
                                            const showFormInline = cvExpanded[demandante.id] && hasCvForm(cvData);
                                            return (
                                                <React.Fragment key={`no-inscrito-${demandante.id}`}>
                                                    <tr>
                                                        <td>{demandante.nombre_completo}</td>
                                                        <td>{demandante.email}</td>
                                                        <td>{demandante.tel_movil}</td>
                                                        <td>{renderCvCell(demandante.id)}</td>
                                                        <td>
                                                            <button 
                                                                className="btn-modificar"
                                                                onClick={() => handleAsignar(demandante.id)}
                                                                disabled={oferta.abierta !== 0}
                                                            >
                                                                ASIGNAR
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    {showFormInline && (
                                                        <tr>
                                                            <td colSpan="5">
                                                                <div className="cv-inline">
                                                                    <h4>CV (Formulario)</h4>
                                                                    <p><strong>Resumen:</strong> {cvData?.cv_form?.resumen || 'No disponible'}</p>
                                                                    <p><strong>Experiencia:</strong> {cvData?.cv_form?.experiencia || 'No disponible'}</p>
                                                                    <p><strong>Formación:</strong> {cvData?.cv_form?.formacion || 'No disponible'}</p>
                                                                    <p><strong>Habilidades:</strong> {cvData?.cv_form?.habilidades || 'No disponible'}</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    )}
                                                </React.Fragment>
                                            );
                                        })
                                    ) : (
                                        <tr>
                                            <td colSpan="5" style={{textAlign: 'center'}}>
                                                No hay demandantes con la titulación requerida
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>

                            <h2>Candidato externo</h2>
                            <form onSubmit={handleAsignarExterno}>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input
                                                    type="text"
                                                    placeholder="Nombre completo"
                                                    value={externoNombre}
                                                    onChange={(e) => setExternoNombre(e.target.value)}
                                                    disabled={oferta.abierta !== 0}
                                                />
                                            </td>
                                            <td>
                                                <button className="btn-modificar" type="submit" disabled={oferta.abierta !== 0}>
                                                    ASIGNAR EXTERNO
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </>
                    )}
                    
                    {error && <div className="error">{error}</div>}
                    {cvError && <div className="error">{cvError}</div>}
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
