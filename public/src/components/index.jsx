import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import "../../css/styles.css";

const Index = () => {
    const navigate = useNavigate();

    return (
        <body className="index-body">
            <div className="different-bg">
            <header className="index-header">
                <div className="index-logo">
                <img src="/img/logo-purp.png" alt="Logo" />
                </div>
            </header>

            <div className="container index-container">
                <h1>Inicia Sesión</h1>
                <p className="intro">
                Bienvenido al punto de encuentro donde el talento y las oportunidades se conectan.
                Ya seas una persona en busca de tu próximo desafío profesional o una empresa en busca
                de los mejores perfiles, aquí encontrarás las herramientas y el apoyo necesario para
                lograrlo. Únete a nuestra comunidad y empieza a construir el futuro que deseas.
                </p>
                <p className="highlight">¡EMPIEZA CUANTO ANTES!</p>
                <button className="index-btn" onClick={() => navigate('/login')}>Inicia Sesión</button>
                <p className="register">
                ¿No tienes una cuenta?
                <Link to="/registro/demandante">Regístrate para trabajar</Link> 
                |
                <Link to="/registro/empresa">Regístrate para ofrecer trabajo</Link>
                </p>
            </div>

            <footer className="index-footer">
                <div className="footer-container">
                <div className="footer-section">
                    <h4>Información General</h4>
                    <ul>
                    <li><a href="#">Quiénes somos</a></li>
                    <li><a href="#">Nuestro propósito</a></li>
                    <li><a href="#">Contacto</a></li>
                    </ul>
                </div>
                <div className="footer-section">
                    <h4>Ayuda al Usuario</h4>
                    <ul>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Plataforma</a></li>
                    <li><a href="#">Soporte técnico</a></li>
                    </ul>
                </div>
                <div className="footer-section">
                    <h4>Legal y Privacidad</h4>
                    <ul>
                    <li><a href="#">Términos y condiciones</a></li>
                    <li><a href="#">Política de privacidad</a></li>
                    <li><a href="#">Aviso legal</a></li>
                    </ul>
                </div>
                <div className="footer-section subscribe">
                    <h4>Suscríbete</h4>
                    <div>
                    <input type="email" placeholder="Email" />
                    <button>
                        <img src="/img/suscribe-purp.png" alt="Suscribirse" />
                    </button>
                    </div>
                    <p>Suscríbete para enterarte de las nuevas ofertas de empleo.</p>
                </div>
                </div>

                <div className="footer-copy">
                <p>
                    © 2025 Sara & Irati. Derechos reservados.{" "}
                    <a href="#">Política de Privacidad</a>{" "}
                    <a href="#">Términos de Servicio</a>
                </p>
                <div className="social-icons">
                    <img src="/img/fc-white.png" alt="Facebook" />
                    <img src="/img/in-white.png" alt="LinkedIn" />
                    <img src="/img/tw-white.png" alt="Twitter" />
                    <img src="/img/pint-white.png" alt="Pinterest" />
                </div>
                </div>
            </footer>
            </div>
        </body>
    );
};

export default Index;
