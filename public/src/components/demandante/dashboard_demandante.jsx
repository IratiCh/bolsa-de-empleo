import React, { useEffect, useState } from "react";
import { Link, useNavigate, useParams } from 'react-router-dom';
import "../../../css/demandante/styles.css";
import "../../../css/styles.css";

function DashboardDemandante() {

    const navigate = useNavigate();
    const [ofertas, setOfertas] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [searchTerm, setSearchTerm] = useState('');
    const [filteredOfertas, setFilteredOfertas] = useState([]);


    // Verificar autenticación
    if (!localStorage.getItem('usuario')) {
        navigate('/login', { replace: true });
    } else {
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        if (!usuario.id_dem) {
            navigate('/login', { replace: true });
        }
    }

    // Obtener ofertas
    useEffect(() => {
        
        const fetchOfertas = async () => {
            try {
                const usuario = JSON.parse(localStorage.getItem('usuario'));
                if (!usuario || !usuario.id_dem) {
                    navigate('/login', { replace: true });
                    return;
                }

                const response = await fetch(`/api/demandante/ofertas?id_dem=${usuario.id_dem}`);
                
                if (response.ok) {

                    const data = await response.json();
                    setOfertas(data);
                    setFilteredOfertas(data);
                    
                }else{
                    setError("Error al cargar las ofertas");
                }
            } catch (error) {
                setError(error.message);
            } finally {
                setLoading(false);
            }
        };
        fetchOfertas();
    }, [navigate]);

    // Manejar búsqueda
    const handleSearch = (e) => {
        e.preventDefault();
        if (searchTerm.trim() === '') {
            setFilteredOfertas(ofertas);
        } else {
            const filtered = ofertas.filter(oferta =>
                oferta.nombre.toLowerCase().includes(searchTerm.toLowerCase())
            );
            setFilteredOfertas(filtered);
        }
    };

    // Manejar limpiar búsqueda
    const handleClearSearch = () => {
        setSearchTerm('');
        setFilteredOfertas(ofertas);
    };

    // Redirigir a detalles de oferta
    const handleVerOferta = (id) => {
        navigate(`/demandante/oferta/${id}`);
    };

    // Formatear fecha
    const formatDate = (dateString) => {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('es-ES', options);
    };

    return(

        <div className="DIV-DEMANDANTE">

            <header className="HEADER-DASH">
                <div className="logo">
                    <img src="/img/logo-purp.png" />
                </div>
                <div className="user">
                    <Link to="/demandante/perfil_demandante">
                        <img src="/img/user.png" />
                    </Link>
                </div>
                <div className="dash">
                    <h1>Encuentra tu trabajo ideal según tu formación</h1>
                    <img src="/img/dash-demandante.png" />
                </div>

            </header>

            <div className="CONTENIDO">

                <form className="buscador" onSubmit={handleSearch}>
                    <img src="/img/buscar.png" alt="Búsqueda" />
                    <input className="search-input" type="text" placeholder="Busca tu Trabajo Ideal" value={searchTerm} onChange={(e) => setSearchTerm(e.target.value)}/>
                    <img src="/img/linea.png" alt="Línea" />
                    <input className="limpiar" type="reset" value="Limpiar" onClick={handleClearSearch}/>
                    <div className="cont-buscar">
                        <button className="btn-buscar">Buscar</button>
                    </div>
                </form>



                <h1 className="title-dash">Encuentra un Nuevo Trabajo</h1>
                
                
                <div className="lista-ofertas">
                    
                {loading && <div className="loading">Cargando ofertas...</div>}
                {error && <div className="error">{error}</div>}

                {filteredOfertas.length > 0 ? (
                    filteredOfertas.map((oferta) => (
                        <div key={oferta.id} className="card-ofer">
                            <div className="card-header">
                                <h3 onClick={() => handleVerOferta(oferta.id)}>{oferta.nombre}</h3>
                            </div>
                            <div className="card-details">
                                    <p>{oferta.breve_desc}</p>
                                    <div className="info-section">
                                    <p>{formatDate(oferta.fecha_pub)}</p>
                                    <img src="/img/separador.png" alt="Separador"/>
                                    <p>{oferta.tipo_contrato}</p>
                                </div>
                                <div className="info-empresa">
                                    <img src="/img/ofer-abierta.png" alt="Oferta abierta" />
                                    <h4>{oferta.empresa}</h4>
                                </div>
                            </div>
                        </div>
                        ))
                    ) : (
                        !loading && <div>No se encontraron ofertas</div>
                    )}

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

export default DashboardDemandante;