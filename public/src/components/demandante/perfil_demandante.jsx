
import React, { useEffect, useState } from "react";
import { Link, useNavigate } from 'react-router-dom';
import "../../../css/demandante/styles.css";
import "../../../css/styles.css";

function PerfilDemandante() {
    const navigate = useNavigate();
    const [demandanteOriginal, setDemandanteOriginal] = useState({
        nombre: '',
        ape1: '',
        ape2: '',
        email: ''
      });
      
      const [demandanteEdicion, setDemandanteEdicion] = useState({
        nombre: '',
        ape1: '',
        ape2: '',
        tel_movil: '',
        contrasena_hash: ''
      });
    const [titulos, setTitulos] = useState([
        { titulo_id: '', centro: '', año: '', cursando: '' },
        { titulo_id: '', centro: '', año: '', cursando: '' }
    ]);
    const [allTitulos, setAllTitulos] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [cvError, setCvError] = useState('');
    const [cvSuccess, setCvSuccess] = useState('');
    const [fieldErrors, setFieldErrors] = useState({
        nombre: '',
        ape1: '',
        ape2: '',
        tel_movil: '',
        contrasena_hash: '',
        titulos: []
    });
    const [success, setSuccess] = useState('');
    const [cvForm, setCvForm] = useState({
        resumen: '',
        experiencia: '',
        formacion: '',
        habilidades: ''
    });
    const [cvPdfUrl, setCvPdfUrl] = useState('');
    const [cvFile, setCvFile] = useState(null);

    // Verificar autenticación
    useEffect(() => {
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        if (!usuario) {
            navigate('/login');
            return;
        }
        
        const cargarPerfil = async () => {
            try {
                setLoading(true);
                const response = await fetch(`/api/demandante/perfil/${usuario.id_dem}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Datos originales (para mostrar en el header)
                    setDemandanteOriginal({
                        nombre: data.demandante.nombre,
                        ape1: data.demandante.ape1,
                        ape2: data.demandante.ape2 || '',
                        email: data.demandante.email
                    });
                    
                    // Datos para edición (formulario)
                    setDemandanteEdicion({
                        nombre: data.demandante.nombre,
                        ape1: data.demandante.ape1,
                        ape2: data.demandante.ape2 || '',
                        tel_movil: data.demandante.tel_movil,
                        email: data.demandante.email,
                        contrasena_hash: ''
                    });

                    const titulosFormateados = data.titulos.map(titulo => ({
                        ...titulo,
                        año: titulo.año ? `${titulo.año.substring(0, 4)}-01-01` : ''
                    }));

                    setTitulos([
                        titulosFormateados[0] || { titulo_id: '', centro: '', año: '', cursando: '' },
                        titulosFormateados[1] || { titulo_id: '', centro: '', año: '', cursando: '' }
                    ]);
                    
                    // Todos los títulos disponibles
                    setAllTitulos(data.allTitulos || []);
                } else {
                    setError(data.error || 'Error al cargar perfil');
                }
            } catch (err) {
                setError('Error de conexión');
            } finally {
                setLoading(false);
            }
        };
        
        cargarPerfil();
    }, [navigate]);

    useEffect(() => {
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        if (!usuario?.id_dem) return;

        const cargarCv = async () => {
            try {
                const response = await fetch(`/api/demandante/cv/${usuario.id_dem}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    setCvError(data.error || 'Error al cargar CV');
                    return;
                }

                setCvForm({
                    resumen: data.cv_form?.resumen || '',
                    experiencia: data.cv_form?.experiencia || '',
                    formacion: data.cv_form?.formacion || '',
                    habilidades: data.cv_form?.habilidades || ''
                });

                setCvPdfUrl(data.cv_pdf_url || '');
            } catch (err) {
                setCvError('Error de conexión');
            }
        };

        cargarCv();
    }, []);

    const handleDatosChange = (e) => {
        const { name, value } = e.target;
        setDemandanteEdicion(prev => ({ ...prev, [name]: value }));
    };

    const handleTituloChange = (index, field, value) => {
        const nuevosTitulos = [...titulos];
        nuevosTitulos[index] = { ...nuevosTitulos[index], [field]: value };
        setTitulos(nuevosTitulos);
    };

    const handleCvFormChange = (e) => {
        const { name, value } = e.target;
        setCvForm(prev => ({ ...prev, [name]: value }));
    };

    const handleCvFormSubmit = async (e) => {
        e.preventDefault();
        setCvError('');
        setCvSuccess('');

        try {
            const usuario = JSON.parse(localStorage.getItem('usuario'));
            const response = await fetch(`/api/demandante/cv-form/${usuario.id_dem}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({ cv_form: cvForm })
            });

            const data = await response.json();

            if (!response.ok) {
                setCvError(data.error || 'Error al guardar CV');
                return;
            }

            setCvSuccess('CV actualizado correctamente');
        } catch (err) {
            setCvError('Error de conexión');
        }
    };

    const handleCvPdfSubmit = async (e) => {
        e.preventDefault();
        setCvError('');
        setCvSuccess('');

        if (!cvFile) {
            setCvError('Selecciona un archivo PDF');
            return;
        }

        try {
            const usuario = JSON.parse(localStorage.getItem('usuario'));
            const formData = new FormData();
            formData.append('cv_pdf', cvFile);

            const response = await fetch(`/api/demandante/cv-pdf/${usuario.id_dem}`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                setCvError(data.error || 'Error al subir CV');
                return;
            }

            setCvPdfUrl(data.cv_pdf_url || '');
            setCvFile(null);
            setCvSuccess('CV PDF subido correctamente');
        } catch (err) {
            setCvError('Error de conexión');
        }
    };

    const handleSubmitDatos = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError('');
        setSuccess('');
        setFieldErrors({
            nombre: '',
            ape1: '',
            ape2: '',
            tel_movil: '',
            contrasena_hash: ''
        });
        
        try {
            const usuario = JSON.parse(localStorage.getItem('usuario'));
            
            const datosParaEnviar = {
                nombre: demandanteEdicion.nombre,
                ape1: demandanteEdicion.ape1,
                ape2: demandanteEdicion.ape2,
                tel_movil: demandanteEdicion.tel_movil,
                contrasena_hash: demandanteEdicion.contrasena_hash
            };
            
            const response = await fetch(`/api/demandante/actualizar-datos/${usuario.id_dem}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(datosParaEnviar)
            });
            
            const data = await response.json();
            
            if (response.ok) {
                
                setSuccess('Datos actualizados correctamente');

                setDemandanteOriginal({
                    nombre: demandanteEdicion.nombre,
                    ape1: demandanteEdicion.ape1,
                    ape2: demandanteEdicion.ape2,
                    email: demandanteEdicion.email
                    });
            } else {
                setError(data.error || data.message || 'Error al actualizar datos');
            }

        } catch (error) {
            setError('Error de conexión');
        } finally {
            setLoading(false);
        }
    };

    const handleSubmitTitulos = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError('');
        setSuccess('');
        
        try {
            const usuario = JSON.parse(localStorage.getItem('usuario'));
            const titulosParaEnviar = titulos
                .filter(t => t.titulo_id !== '')
                .map(titulo => ({
                    ...titulo,
                    id: titulo.id || null, // Incluir ID si existe
                    año: titulo.año || null // Asegurar formato de fecha
            }));
            
            const response = await fetch(`/api/demandante/guardar-titulos/${usuario.id_dem}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({ 
                    titulos: titulosParaEnviar 
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                setSuccess('Títulos actualizados correctamente');
            } else {
                setError(data.error || 'Error al actualizar títulos');
            }
        } catch (error) {
            setError('Error de conexión');
        } finally {
            setLoading(false);
        }
    };

    const handleLogout = () => {
        localStorage.removeItem('usuario');
        localStorage.removeItem('token');
        navigate('/');
    };

    const handleOfertasInscritas = () => {
        navigate('/demandante/ofertas_inscritas');
    };


    if (loading) return <div className="loading">Cargando...</div>;

    return (
        <div className="DIV-DEMANDANTE PERFIL">
            <header className="HEADER-PERFIL">
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
        
            <div className="DEMANDANTE">
                <div className="perfil-container">
                    <div className="perfil-info">
                        <img src="/img/email.png" alt="Email"/>
                        <div className="perfil-texto">
                            <p>{demandanteOriginal.nombre} {demandanteOriginal.ape1} {demandanteOriginal.ape2}</p>
                            <p>{demandanteOriginal.email}</p>
                        </div>
                    </div>
            
                    <div className="btn-perfil">
                        <button type="button" className="act-ofertas-apun" onClick={handleOfertasInscritas}>
                            OFERTAS APUNTADAS
                        </button>
                        <button type="button" className="btn-logout" onClick={handleLogout}>
                            CERRAR SESIÓN
                        </button>
                    </div>
                </div>
        
                <form onSubmit={handleSubmitDatos}>
                    <table className="table-perfil">
                        <tbody>
                            <tr><td colSpan="2"><h1>Datos Personales</h1></td></tr>
                            <tr>
                                <td>
                                    <label>Nombre</label>
                                    <input 
                                        type="text" 
                                        name="nombre" 
                                        required 
                                        value={demandanteEdicion.nombre}
                                        onChange={handleDatosChange}
                                    />
                                </td>
                                <td>
                                    <label>Primer Apellido</label>
                                    <input 
                                        type="text" 
                                        name="ape1" 
                                        required 
                                        value={demandanteEdicion.ape1}
                                        onChange={handleDatosChange}
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Segundo Apellido</label>
                                    <input 
                                        type="text" 
                                        name="ape2" 
                                        required 
                                        value={demandanteEdicion.ape2}
                                        onChange={handleDatosChange}
                                    />
                                </td>
                                <td>
                                    <label>Teléfono</label>
                                    <input 
                                        type="number" 
                                        name="tel_movil" 
                                        required
                                        value={demandanteEdicion.tel_movil}
                                        onChange={handleDatosChange}
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td colSpan="2">
                                    <label>Contraseña</label>
                                    <input 
                                        type="password" 
                                        name="contrasena_hash"
                                        required
                                        value={demandanteEdicion.contrasena_hash}
                                        onChange={handleDatosChange}
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {error && <div className="error">{error}</div>}
                    {success && <div className="success">{success}</div>}
                    <div className="btn-datos">
                        <button type="submit" className="act-datos">{loading ? 'PROCESANDO...' : 'ACTUALIZAR DATOS'}</button>
                    </div>
                </form>
              
                <form onSubmit={handleCvFormSubmit}>
                    <table className="table-perfil">
                        <tbody>
                            <tr><td colSpan="2"><h1>Mi CV (Formulario)</h1></td></tr>
                            <tr>
                                <td colSpan="2">
                                    <label>Resumen</label>
                                    <textarea
                                        name="resumen"
                                        rows="4"
                                        value={cvForm.resumen}
                                        onChange={handleCvFormChange}
                                        placeholder="Breve resumen profesional"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td colSpan="2">
                                    <label>Experiencia</label>
                                    <textarea
                                        name="experiencia"
                                        rows="4"
                                        value={cvForm.experiencia}
                                        onChange={handleCvFormChange}
                                        placeholder="Experiencia laboral relevante"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td colSpan="2">
                                    <label>Formación</label>
                                    <textarea
                                        name="formacion"
                                        rows="4"
                                        value={cvForm.formacion}
                                        onChange={handleCvFormChange}
                                        placeholder="Formación académica"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td colSpan="2">
                                    <label>Habilidades</label>
                                    <textarea
                                        name="habilidades"
                                        rows="4"
                                        value={cvForm.habilidades}
                                        onChange={handleCvFormChange}
                                        placeholder="Habilidades técnicas y personales"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {cvError && <div className="error">{cvError}</div>}
                    {cvSuccess && <div className="success">{cvSuccess}</div>}
                    <div className="btn-datos">
                        <button type="submit" className="act-datos">GUARDAR CV</button>
                    </div>
                </form>

                <form onSubmit={handleCvPdfSubmit}>
                    <table className="table-perfil">
                        <tbody>
                            <tr><td colSpan="2"><h1>Mi CV (PDF)</h1></td></tr>
                            <tr>
                                <td>
                                    <label>Subir CV en PDF</label>
                                    <input
                                        type="file"
                                        accept="application/pdf"
                                        onChange={(e) => setCvFile(e.target.files?.[0] || null)}
                                    />
                                </td>
                                <td>
                                    {cvPdfUrl ? (
                                        <a href={cvPdfUrl} target="_blank" rel="noreferrer">Ver CV actual</a>
                                    ) : (
                                        <span>No hay PDF subido</span>
                                    )}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {cvError && <div className="error">{cvError}</div>}
                    {cvSuccess && <div className="success">{cvSuccess}</div>}
                    <div className="btn-datos">
                        <button type="submit" className="act-datos">SUBIR PDF</button>
                    </div>
                </form>

                <form onSubmit={handleSubmitTitulos}>
                    <table className="table-titulos">
                        <tbody>
                            
                            <tr>
                                <td colspan="2">
                                    <h1>Mis Títulos</h1>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h2>Primer Título</h2>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="nombre">Nombre</label>
                                    <select
                                        value={titulos[0].titulo_id}
                                        onChange={(e) => handleTituloChange(0, 'titulo_id', e.target.value)}
                                        required
                                    >
                                        <option value="" disabled>Seleccione un título</option>
                                        {allTitulos.map(titulo => (
                                            <option key={titulo.id} value={titulo.id}>
                                                {titulo.nombre}
                                            </option>
                                        ))}
                                    </select>
                                </td>
                                <td>
                                    <label>Centro</label>
                                    <input
                                        type="text"
                                        value={titulos[0].centro}
                                        onChange={(e) => handleTituloChange(0, 'centro', e.target.value)}
                                        required
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Situación del Curso</label>
                                    <select
                                        value={titulos[0].cursando}
                                        onChange={(e) => handleTituloChange(0, 'cursando', e.target.value)}
                                        required
                                    >
                                        <option value="" disabled>Seleccione</option>
                                        <option value="Cursando">Cursando</option>
                                        <option value="Finalizado">Finalizado</option>
                                        <option value="Abandonado">Abandonado</option>
                                    </select>
                                </td>
                                <td>
                                    <label>Año</label>
                                    <input
                                        type="date"
                                        value={titulos[0].año}
                                        onChange={(e) => handleTituloChange(0, 'año', e.target.value)}
                                        required
                                    />
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
                                    <select
                                        value={titulos[1].titulo_id}
                                        onChange={(e) => handleTituloChange(1, 'titulo_id', e.target.value)}
                                    >
                                        <option value="" disabled>Seleccione un título</option>
                                        {allTitulos.map(titulo => (
                                            <option key={titulo.id} value={titulo.id}>
                                                {titulo.nombre}
                                            </option>
                                        ))}
                                    </select>
                                </td>
                                <td>
                                    <label>Centro</label>
                                    <input
                                        type="text"
                                        value={titulos[1].centro}
                                        onChange={(e) => handleTituloChange(1, 'centro', e.target.value)}
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Situación del Curso</label>
                                    <select
                                        value={titulos[1].cursando}
                                        onChange={(e) => handleTituloChange(1, 'cursando', e.target.value)}
                                    >
                                        <option value="" disabled>Seleccione</option>
                                        <option value="Cursando">Cursando</option>
                                        <option value="Finalizado">Finalizado</option>
                                        <option value="Abandonado">Abandonado</option>
                                    </select>
                                </td>
                                <td>
                                    <label>Año</label>
                                    <input
                                        type="date"
                                        value={titulos[1].año}
                                        onChange={(e) => handleTituloChange(1, 'año', e.target.value)}
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {error && <div className="error">{error}</div>}
                    {success && <div className="success">{success}</div>}
                    <div className="btn-info">
                        <button type="submit" className="act-info">{loading ? 'PROCESANDO...' : 'GUARDAR TÍTULOS'}</button>
                    </div>
                </form>
            </div>
        
        
            <footer className="global-footer footer-perfil">
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

export default PerfilDemandante;
