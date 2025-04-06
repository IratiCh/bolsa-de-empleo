import React from 'react';
import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import "../../css/styles.css";

const RegistroEmpresa = () => {

    const [formData, setFormData] = useState({
        nombre: '',
        localidad: '',
        cif: '',
        email: '',
        contrasena_hash: '',
        telefono: ''
    });

    const [errorMessage, setErrorMessage] = useState('');
    const [successMessage, setSuccessMessage] = useState('');

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            const response = await fetch('/api/auth/register_empresa', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            });

            const data = await response.json();

            if (response.ok) {
                setSuccessMessage('Usuario registrado. Espere a que se valide la cuenta antes de iniciar sesión.');
                setErrorMessage('');
                setFormData({ 
                    nombre: '',
                    localidad: '',
                    cif: '',
                    email: '',
                    contrasena_hash: '',
                    telefono: ''
                });
            } else {
                setErrorMessage(data.message || 'Error en el registro'); 
                setSuccessMessage('');
            }
        } catch (error) {
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
                    <img className="foto-registro" src="/img/ejemplo-registro-empresa.png" alt="Registro Empresa"/>
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
                                <label htmlFor="password">Contraseña</label>
                                <input type="password" id="password" name="contrasena_hash" required onChange={handleChange}/>
                            </div>
                        </div>

                        <div>
                            <div className="input-group-register">
                                <label htmlFor="cif">CIF</label>
                                <input type="text" id="cif" name="cif" required onChange={handleChange}/>
                            </div>
                            <div className="input-group-register">
                                <label htmlFor="number">Teléfono</label>
                                <input type="number" id="telefono" name="telefono" required onChange={handleChange}/>
                            </div>
                        </div>

                        <div>
                            <div className="input-group-register">
                                <label htmlFor="email">Email</label>
                                <input type="email" id="email" className="register-dem-email" name="email" required onChange={handleChange}/>
                            </div>
                            <div className="input-group-register">
                                <label htmlFor="text">Localidad</label>
                                <input type="text" id="localidad" name="localidad" required onChange={handleChange}/>
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


export default RegistroEmpresa;