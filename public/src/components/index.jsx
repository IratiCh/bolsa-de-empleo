import React from 'react'; // Importación de React para construir componentes funcionales.
import { Link, useNavigate } from 'react-router-dom'; // Importación de herramientas de navegación y enlaces para una SPA.
import "../../css/styles.css"; // Importación de estilos CSS para aplicar diseño y formato visual.

const Index = () => {
    // Permite la navegación entre páginas.
    const navigate = useNavigate();

    return (
        <div className="index-body">
            <div className="different-bg">
                <header className="index-header">
                    <div className="index-logo">
                    <img src="/img/logo-blue.png" alt="Logo" />
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
                    <Link to="/registro_demandante">Regístrate para trabajar</Link> 
                    |
                    <Link to="/registro_empresa">Regístrate para ofrecer trabajo</Link>
                    </p>
                </div>
            </div>
            <footer className="index-footer">
                <div className="footer-container">
                    <p>
                        © 2026 Sara & Irati. Derechos reservados.
                    </p>
                </div>
            </footer>
        </div>
    );
};

export default Index;
