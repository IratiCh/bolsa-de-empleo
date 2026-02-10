import React, { useState } from 'react'; // Importación de React y useState para manejar estados locales.
import { Link, useNavigate } from 'react-router-dom'; // Importación de herramientas para navegación y enlaces entre rutas.
import "../../css/styles.css"; // Importación de estilos CSS.

const Login = () => {
    // Permite navegar entre rutas.
    const navigate = useNavigate();    
    
    // Estado local para almacenar el correo electrónico del usuario.
    const [email, setEmail] = useState('');
    // Estado local para almacenar la contraseña ingresada.
    const [password, setPassword] = useState('');
    // Estado local para mostrar un mensaje de error en caso de que ocurra un problema.
    const [errorMessage, setErrorMessage] = useState('');
    
    const handleSubmit = async (e) => {
        e.preventDefault();
        // Previene el comportamiento por defecto del formulario (recargar la página).
        
        try {
            const response = await fetch('/api/auth/login', {
                // Envía los datos de inicio de sesión al backend mediante una solicitud HTTP POST.
                method: 'POST',
                // Especifica que los datos enviados están en formato JSON.
                headers: {
                    'Content-Type': 'application/json',
                },
                // Convierte el objeto con los datos del formulario en una cadena JSON.
                body: JSON.stringify({ email, contrasena_hash: password }),
            });

            // Convierte la respuesta en formato JSON.
            const data = await response.json();

            // Si la solicitud fue exitosa:
            if (response.ok) {
                const userData = {
                    id: data.id,
                    nombre: data.nombre,
                    email: data.email,
                    rol: data.rol,
                    // Solo agregar id_emp si existe en la respuesta
                    ...(data.id_emp && { id_emp: data.id_emp }),
                    // Incluye validado solo si está definido.
                    ...(data.validado !== undefined && { validado: data.validado }),
                    // Incluye id_dem solo si está presente en la respuesta.
                    ...(data.id_dem && { id_dem: data.id_dem }),
                };
    
                // Almacena los datos del usuario en el almacenamiento local del navegador.
                localStorage.setItem('usuario', JSON.stringify(userData));
    
                // Redirige al usuario a diferentes rutas dependiendo de su rol.
                switch (data.rol) {
                    case 'demandante':
                        navigate('/demandante/dashboard_demandante');
                        break;
                    case 'empresa':
                        // Maneja diferentes estados de validación para el rol "empresa".
                        switch (data.validado) {
                            case -1:
                                setErrorMessage('Tu cuenta ha sido rechazada. Por favor contacta al administrador.');
                                break;
                            case 0:
                                setErrorMessage('Tu cuenta está pendiente de validación. Por favor espera la confirmación.');
                                break;
                            case 1:
                                navigate('/empresa/dashboard_empresa');
                                break;
                            default:
                                setErrorMessage('Estado de validación desconocido');
                        }
                        break;
                    case 'centro':
                        navigate('/centro/dashboard_centro');
                        break;
                    // Muestra un mensaje de error si el rol no coincide con ninguno de los casos esperados.
                    default:
                        setErrorMessage('Rol desconocido');
                }
            } else {
                // Muestra el mensaje de error proveniente del backend o un mensaje genérico.
                setErrorMessage(data.message || 'Error de inicio de sesión');
            }
        } catch (error) {
            // Muestra un mensaje de error genérico.
            setErrorMessage('Hubo un error, por favor intenta nuevamente.');
        }
    };

    return (
        <div className="login-body">
            <div className="container login-container">
                <div className="left-section">
                    <div className="logo">
                        <img src="/img/logo-white.png"/>
                    </div>
                    <h2>Conecta talento y oportunidades en un solo clic</h2>
                    <img className="foto-login" src="/img/ejemplo-login.png" alt="Inicio sesión"/>
                </div>
                <div className="right-section">
                    <h1>Inicia Sesión</h1>
                    <form onSubmit={handleSubmit}>
                        <div className="input-group">
                            <label htmlFor="email" className="login-lab-email">Email</label>
                            <input type="email" id="email" className="login-email" name="email" value={email} onChange={(e) => setEmail(e.target.value)} required/>
                        </div>
                        
                        <div className="input-group">
                            <label htmlFor="password" className="login-lab-pass">Contraseña</label>
                            <input type="password" id="password" className="login-pass" name="password" value={password} onChange={(e) => setPassword(e.target.value)} required/>
                        </div>
                        {errorMessage && <div className="error">{errorMessage}</div>}
                        <button type="submit" className="login-btn">Inicia Sesión</button>
                    </form>
                    <div className="login-register">
                        <p>¿No tienes una cuenta?</p>
                        <p><Link to="/registro_demandante">Regístrate para trabajar</Link></p>
                        <p><Link to="/registro_empresa">Regístrate para ofrecer trabajo</Link></p>
                    </div>
                </div>
            </div>
        </div>
    );
};
    
export default Login;
