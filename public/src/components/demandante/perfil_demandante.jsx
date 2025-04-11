
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
    const [fieldErrors, setFieldErrors] = useState({
        nombre: '',
        ape1: '',
        ape2: '',
        tel_movil: '',
        contrasena_hash: '',
        titulos: []
    });
    const [success, setSuccess] = useState('');

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
                    setErrorMessage(data.message || 'Error al cargar perfil');
                }
            } catch (err) {
                setError('Error de conexión');
            } finally {
                setLoading(false);
            }
        };
        
        cargarPerfil();
    }, [navigate]);

    const handleDatosChange = (e) => {
        const { name, value } = e.target;
        setDemandanteEdicion(prev => ({ ...prev, [name]: value }));
    };

    const handleTituloChange = (index, field, value) => {
        const nuevosTitulos = [...titulos];
        nuevosTitulos[index] = { ...nuevosTitulos[index], [field]: value };
        setTitulos(nuevosTitulos);
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