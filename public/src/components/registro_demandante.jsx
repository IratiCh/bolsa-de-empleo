import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import "../../css/styles.css";

const RegistroDemandante = () => {

    // Estado inicial del formulario, que contiene los datos del demandante.
    const [formData, setFormData] = useState({
        nombre: '',
        ape1: '',
        ape2: '',
        dni: '',
        email: '',
        contrasena_hash: '',
        tel_movil: ''
    });

    // Estado para almacenar mensajes de error
    const [errorMessage, setErrorMessage] = useState('');
    // Estado para almacenar mensajes de éxito
    const [successMessage, setSuccessMessage] = useState('');

    // Actualiza el estado del formulario dinámicamente cuando el usuario llena los campos.
    const handleChange = (e) => {
        setFormData({
            ...formData,
            // Utiliza el atributo "name" del input para identificar qué valor se debe modificar.
            [e.target.name]: e.target.value
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            // Realiza una solicitud POST al backend para registrar al demandante.
            const response = await fetch('/api/auth/register_demandante', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            });

            // Convierte la respuesta en formato json
            const data = await response.json();

            // Si la solicitud fue exitosa, muestra un mensaje de éxito.
            if (response.ok) {
                setSuccessMessage('Usuario registrado correctamente. ¡Ya puedes iniciar sesión!');
                setErrorMessage('');
                // Reinicia el estado del formulario para limpiarlo.
                setFormData({ 
                    nombre: '',
                    ape1: '',
                    ape2: '',
                    dni: '',
                    email: '',
                    contrasena_hash: '',
                    tel_movil: ''
                });
            } else {
                // Si hubo un error en la solicitud, muestra el mensaje correspondiente.
                setErrorMessage(data.message || 'Error en el registro'); 
                setSuccessMessage('');
            }
        } catch (error) {
             // Maneja errores de conexión o problemas inesperados.
            setErrorMessage('Hubo un error, por favor intenta nuevamente.');
            setSuccessMessage('');
        }
    };

    return (
        <div className="registro-body">
            <div className="container registro-container">
                <div className="left-section">
                    <div className="logo">
                        <img src="/img/logo-white.png"/>
                    </div>
                    <h2>Encuentra tu trabajo ideal según tu formación</h2>
                    <img className="foto-registro" src="/img/ejemplo-registro-demandante.png" alt="Registro Demandante"/>
                </div>
                <div className="right-section-register">
                    <h1>Crear Cuenta</h1>
                    <form onSubmit={handleSubmit}>
                        <div>
                            <div className="input-group-register">
                                <label htmlFor="text">Nombre</label>
                                <input type="text" id="nombre" name="nombre" required onChange={handleChange}/>
                            </div>
                            <div className="input-group-register">
                                <label htmlFor="text">Primer Apellido</label>
                                <input type="text" id="apellido1" name="ape1" required onChange={handleChange}/>
                            </div>
                        </div>

                        <div>
                            <div className="input-group-register">
                                <label htmlFor="text">Segundo Apellido</label>
                                <input type="text" id="apellido2" name="ape2" required onChange={handleChange}/>
                            </div>
                            <div className="input-group-register">
                                <label htmlFor="text">DNI</label>
                                <input type="text" id="dni" name="dni" required onChange={handleChange}/>
                            </div>
                        </div>

                        <div>
                            <div className="input-group-register">
                                <label htmlFor="email">Email</label>
                                <input type="email" id="email" className="register-dem-email" name="email" required onChange={handleChange}/>
                            </div>
                        </div>
                        
                        <div>
                            <div className="input-group-register">
                                <label htmlFor="password">Contraseña</label>
                                <input type="password" id="password" name="contrasena_hash" required onChange={handleChange}/>
                            </div>
                            <div className="input-group-register">
                                <label htmlFor="number">Teléfono</label>
                                <input type="number" id="telefono" name="tel_movil" required onChange={handleChange}/>
                            </div>
                        </div>
                        {errorMessage && <div className="error">{errorMessage}</div>}
                        {successMessage && <div className="success">{successMessage}</div>}
                        <div className="register-btn-container">
                            <button type="submit" className="register-btn">Crear Cuenta</button>

                            <div className="register-login">
                                <p>¿Ya tienes una cuenta?
                                    <Link to="/login">Inicia Sesión</Link>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default RegistroDemandante;
