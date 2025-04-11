import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import "../../../css/centro/styles.css";
import "../../../css/styles.css";

const DashboardCentro = () => {
    const navigate = useNavigate();
    // Estado local para almacenar la lista de empresas pendientes por validar.
    const [empresas, setEmpresas] = useState([]);
    // Estado local para indicar si los datos aún se están cargando.
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    // Verifica si el usuario está autenticado. 
    // Si no hay información en el almacenamiento local, redirige al inicio de sesión.
    if (!localStorage.getItem('usuario')) {
        navigate('/login', { replace: true });
    }

    useEffect(() => {
        const fetchEmpresas = async () => {
            // Función para obtener la lista de empresas desde el servidor.
            try {
                // Solicita al backend las empresas pendientes de validación.
                const response = await fetch('/api/centro/empresas-pendientes');
                
                // Si la respuesta no es exitosa, actualiza el estado con un mensaje de error.
                if (!response.ok) {
                    setError('Error al cargar empresas');
                }
                
                // Convierte la respuesta en formato JSON y actualiza el estado de empresas.
                const data = await response.json();
                setEmpresas(data);
            } catch (err) {
                setError(err.message);
            } finally {
                // Independientemente del resultado, marca el estado de carga como falso.
                setLoading(false);
            }
        };

        // Ejecuta la función de obtención de datos cuando el componente se monta.
        fetchEmpresas();
    }, []);

    // Función para validar o rechazar una empresa, dependiendo de la acción solicitada.
    const handleValidacion = async (empresaId, accion) => {
        try {
            const response = await fetch(`/api/centro/validar-empresa/${empresaId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ accion }),
            });
    
            const data = await response.json();
    
            // Si la respuesta fue exitosa, actualiza el estado de la empresa validada.
            if (response.ok && data.success) {
                setEmpresas(empresas.filter(empresa => empresa.id !== empresaId));
            } else {
                // Muestra un mensaje de error si la validación no fue exitosa.
                setError(data.message || 'Error al procesar la validación');
            }
        } catch (err) {
            setError('Error de conexión al validar empresa');
            console.error('Error:', err);
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
                <img src="/img/logo-purp.png" />
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
                <table>
                    <thead>
                        <tr>
                            <th colspan="3"><h1>Empresas por validar</h1></th>
                        </tr>
                    </thead>
                    {loading && <div>Cargando empresas...</div>}
                    {error && <div className="error">{error}</div>}
                    <tbody>
                        {empresas.map(empresa => (
                            <tr key={empresa.id}>
                                <td>{empresa.nombre}</td>
                                <td>{empresa.cif}</td>
                                <td>{empresa.telefono}</td>
                                <td>
                                    <button className="btn-aceptar" onClick={() => handleValidacion(empresa.id, 'aceptar')}>ACEPTAR</button>
                                    <button className="btn-cancelar" onClick={() => handleValidacion(empresa.id, 'rechazar')}>CANCELAR</button>
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

export default DashboardCentro;