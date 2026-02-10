import React, { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import "../../../css/centro/styles.css";
import "../../../css/styles.css";


const GestionTitulos = () => {
    const navigate = useNavigate();
    // Obtener el parámetro "id" de la URL.
    const { id } = useParams();
    // Estado local para almacenar el nombre del título que se está editando.
    const [nombre, setNombre] = useState('');
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');

    useEffect(() => {

        // Comprueba si el usuario está autenticado. Si no, lo redirige al inicio de sesión.
        if (!localStorage.getItem('usuario')) {
            navigate('/login', { replace: true });
        }

        // Función para cargar los datos del título desde el backend.
        const cargarTitulo = async () => {
            try {
                // Solicitud GET para obtener los datos del título utilizando el ID de la URL.
                const response = await fetch(`/api/centro/titulos/${id}`);
                const data = await response.json();
                
                // Si la solicitud es exitosa y el título existe, actualiza el estado con su nombre.
                if (response.ok && data.success) {
                    setNombre(data.titulo.nombre);
                } else {
                    // Si hay un error en la solicitud, muestra un mensaje de error.
                    setError( data.message || 'Error al cargar título');
                }
            } catch (err) {
                setError('Error de conexión');
            }
        };

        // Llama a la función para cargar el título cuando el componente se monta.
        cargarTitulo();

    }, [navigate, id]);

    // Funcion para modificar los datos del título
    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setSuccess('');

        try {
            // Realiza una solicitud PUT para actualizar el título en el backend.
            const response = await fetch(`/api/centro/titulos/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ nombre })
            });

            const data = await response.json();

            // Si la solicitud es exitosa, establece un mensaje de éxito.
            if (response.ok && data.success) {
                setSuccess('Título modificado correctamente');
            } else {
                // Si ocurre un error, establece el mensaje de error.
                if (data.errors) {
                    setError(data.errors);
                } else {
                    setError(data.message || 'Error al modificar título');
                }
            }
        } catch (err) {
            setError('Error de conexión');
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
                <div className="CENTRO">
                    <h1>Modificar Título</h1>
                    <form onSubmit={handleSubmit}>
                        <label htmlFor="text">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Nombre" value={nombre} onChange={(e) => setNombre(e.target.value)}/>
                        {error && <div className="error">{error}</div>}
                        {success && <div className="success">{success}</div>}
                        <div className="btn-titulo">
                            <button type="submit" className="mod-titulo">MODIFICAR TÍTULO</button>
                        </div>
                    </form>
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
