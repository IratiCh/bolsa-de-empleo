import React, { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import "../../../css/centro/styles.css";
import "../../../css/styles.css";


const GestionTitulos = () => {
    const navigate = useNavigate();
    const [titulos, setTitulos] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        if (!localStorage.getItem('usuario')) {
            navigate('/login', { replace: true });
        }
    
        // Cargar títulos
        const fetchTitulos = async () => {
            try {
                const response = await fetch('/api/centro/titulos');
                
                if (!response.ok) {
                    throw new Error('Error al cargar títulos');
                }
                
                const data = await response.json();
                setTitulos(data);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        fetchTitulos();
    }, [navigate]);
    
    const handleEliminar = async (id) => {
        
        try {
            const response = await fetch(`/api/centro/titulos/${id}`, {
                method: 'DELETE'
            });
    
            if (response.ok) {
                setTitulos(titulos.filter(titulo => titulo.id !== id));
            } else {
                throw new Error('Error al eliminar');
            }
        } catch (err) {
            setError(err.message);
        }
    };

    const handleCrearTitulo = () => {
        navigate('/centro/crear_titulo');
    };

    const handleModificar = (id) => {
        navigate(`/centro/modificar_titulo/${id}`);
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
                    <Link to="/centro/gestion_titulo">Gestión Títulos</Link>
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
                <div className="CENTRO">

                    <table>
                        <thead>
                            <tr>
                                <th><h1>Títulos</h1></th>
                                <th className="crear-tit">
                                    <button 
                                    className="BOTON-CREAR"
                                    onClick={() => handleCrearTitulo()}>
                                        CREAR TÍTULO
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        {loading && <div>Cargando empresas...</div>}
                        {error && <div className="error">{error}</div>}
                        <tbody>
                            {titulos.map(titulo => (
                                 <tr key={titulo.id}>
                                    <td>{titulo.nombre}</td>
                                    <td>
                                        <button 
                                            className="btn-modificar"
                                            onClick={() => handleModificar(titulo.id)}>
                                            MODIFICAR
                                        </button>
                                        <button 
                                            className="btn-elim"
                                            onClick={() => handleEliminar(titulo.id)}
                                        >
                                            ELIMINAR
                                        </button>
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
    };
        
export default GestionTitulos;