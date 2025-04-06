import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import "../../css/styles.css";

const Login = () => {
    const navigate = useNavigate();    
    
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [errorMessage, setErrorMessage] = useState('');
    
    const handleSubmit = async (e) => {
        e.preventDefault();

        // Lógica para enviar los datos de inicio de sesión al backend
        try {
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, contrasena_hash: password }),
            });

            const data = await response.json();

            if (response.ok) {
                localStorage.setItem('usuario', JSON.stringify(data));

                switch (data.rol) {
                    case 'demandante':
                        navigate('/demandante/dashboard_demandante');
                        break;
                    case 'empresa':
                        switch (data.validated) {
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
                    default:
                        setErrorMessage('Rol desconocido');
                }
            } else {
                // Manejar errores, mostrar un mensaje de error si es necesario
                setErrorMessage(data.message || 'Error de inicio de sesión');
            }
        } catch (error) {
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