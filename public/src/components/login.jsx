import React from 'react';
import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import "../../css/styles.css";

const Login = () => {
    const navigate = useNavigate();    
    
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    
    const handleSubmit = async (e) => {
        e.preventDefault();

        // Lógica para enviar los datos de inicio de sesión al backend
        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            if (response.ok) {
                // Redirigir al usuario si la autenticación es exitosa
                navigate('/dashboard'); // Asegúrate de que la ruta esté correctamente definida
            } else {
                // Manejar errores, mostrar un mensaje de error si es necesario
                alert(data.message || 'Error de inicio de sesión');
            }
        } catch (error) {
            alert('Hubo un error, por favor intenta nuevamente.');
        }
    };

    return (
        <body className="login-body">
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
                            <label for="email" className="login-lab-email">Email</label>
                            <input type="email" id="email" className="login-email" name="email" value={email} onChange={(e) => setEmail(e.target.value)} required/>
                        </div>
                        
                        <div className="input-group">
                            <label for="password" className="login-lab-pass">Contraseña</label>
                            <input type="password" id="password" className="login-pass" name="password" value={password} onChange={(e) => setPassword(e.target.value)} required/>
                        </div>

                        <button type="submit" className="login-btn">Inicia Sesión</button>
                    </form>
                    <div className="login-register">
                        <p>¿No tienes una cuenta?</p>
                        <p><Link to="/registro/demandante">Regístrate para trabajar</Link></p>
                        <p><Link to="/registro/empresa">Regístrate para ofrecer trabajo</Link></p>
                    </div>
                </div>
            </div>
        </body>
    );
};
    
export default Login;