import React, { useEffect, useState } from "react";
import { Link, useNavigate } from 'react-router-dom';
import "../../../css/demandante/styles.css";
import "../../../css/styles.css";

function PerfilDemandante() {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        nombre: '',
        ape1: '',
        ape2: '',
        tel_movil: '',
        email: '',
        contrasena_hash: ''
    });
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');

    useEffect(() => {
        const cargarPerfil = async () => {
            try {
                const usuario = JSON.parse(localStorage.getItem('usuario'));
                if (!usuario?.id_dem) {
                    navigate('/login');
                    return;
                }

                const response = await fetch(`/api/demandante/perfil/${usuario.id_dem}`);
                const data = await response.json();
                
                if (!response.ok) setError(data.error || 'Error en la respuesta');
                if (!data.demandante) setError('Estructura de datos incorrecta');

                setFormData({
                    nombre: data.demandante.nombre,
                    ape1: data.demandante.ape1,
                    ape2: data.demandante.ape2,
                    tel_movil: data.demandante.tel_movil,
                    email: data.demandante.email,
                    contrasena_hash: ''
                });

            } catch (err) {
                setError(err.message);
                console.error("Error:", err);
            } finally {
                setLoading(false);
            }
        };

        cargarPerfil();
    }, [navigate]);


    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError('');
        setSuccess('');
        
        try {
            const usuario = JSON.parse(localStorage.getItem('usuario'));
            const response = await fetch(`/api/demandante/actualizar/${usuario.id_dem}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();
            
            if (!response.ok) {
                setError(data.error || 'Error al actualizar');
            }
            
            setSuccess('Perfil actualizado correctamente');
            
            // Actualizar email en localStorage si cambió
            if (data.email_updated) {
                const updatedUser = {...usuario, email: formData.email};
                localStorage.setItem('usuario', JSON.stringify(updatedUser));
            }

        } catch (error) {
            setError(error.message);
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

    if (loading) return <div className="loading">Cargando perfil...</div>;

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
                            <p>{formData.nombre} {formData.ape1} {formData.ape2}</p>
                            <p>{formData.email}</p>
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
        
                <form onSubmit={handleSubmit}>
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
                                        value={formData.nombre}
                                        onChange={handleChange}
                                    />
                                </td>
                                <td>
                                    <label>Primer Apellido</label>
                                    <input 
                                        type="text" 
                                        name="ape1" 
                                        required 
                                        value={formData.ape1}
                                        onChange={handleChange}
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Segundo Apellido</label>
                                    <input 
                                        type="text" 
                                        name="ape2" 
                                        value={formData.ape2}
                                        onChange={handleChange}
                                    />
                                </td>
                                <td>
                                    <label>Teléfono</label>
                                    <input 
                                        type="tel" 
                                        name="tel_movil" 
                                        required
                                        pattern="[0-9]{9}"
                                        maxLength="9"
                                        value={formData.tel_movil}
                                        onChange={handleChange}
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td colSpan="2">
                                    <label>Contraseña</label>
                                    <input 
                                        type="password" 
                                        name="contrasena_hash"
                                        value={formData.contrasena_hash}
                                        onChange={handleChange}
                                        placeholder="Nueva contraseña"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    {error && <div className="error">{error}</div>}
                    {success && <div className="success">{success}</div>}
                    
                    <div className="btn-datos">
                        <button type="submit" disabled={loading} className="act-datos">
                            {loading ? 'Actualizando...' : 'ACTUALIZAR DATOS'}
                        </button>
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
                    <img src="/img/fc-white.png" />
                    <img src="/img/in-white.png" />
                    <img src="/img/tw-white.png" />
                    <img src="/img/pint-white.png" />
                    </div>
                </div>
            </footer>
      </div>
    );
}

export default PerfilDemandante;