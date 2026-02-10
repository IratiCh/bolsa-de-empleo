import React, { useEffect, useState } from "react";
import { Link, useNavigate, useParams } from 'react-router-dom';
import "../../../css/demandante/styles.css";
import "../../../css/styles.css";

function DashboardDemandante() {

    const navigate = useNavigate();
    // Estado local que almacena la lista de ofertas laborales.
    const [ofertas, setOfertas] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    // Estado local para almacenar el término de búsqueda ingresado por el usuario.
    const [searchTerm, setSearchTerm] = useState('');
    // Estado local para almacenar las ofertas filtradas con base en el término de búsqueda.
    const [filteredOfertas, setFilteredOfertas] = useState([]);


    // Comprueba si existe un usuario autenticado. Si no, redirige al inicio de sesión.
    if (!localStorage.getItem('usuario')) {
        navigate('/login', { replace: true });
    } else {
        const usuario = JSON.parse(localStorage.getItem('usuario'));
        if (!usuario.id_dem) {
            navigate('/login', { replace: true });
        }
    }

     // Función para cargar las ofertas disponibles desde el backend.
    useEffect(() => {
        
        const fetchOfertas = async () => {
            try {
                // Comprueba la existencia de un usuario autenticado y que pertenece al rol de demandante.
                const usuario = JSON.parse(localStorage.getItem('usuario'));
                if (!usuario || !usuario.id_dem) {
                    navigate('/login', { replace: true });
                    return;
                }

                // Solicita la lista de ofertas para el demandante autenticado.
                const response = await fetch(`/api/demandante/ofertas?id_dem=${usuario.id_dem}`);
                
                // Si la solicitud fue exitosa, actualiza los estados de ofertas y ofertas filtradas.
                if (response.ok) {
                    const data = await response.json();
                    setOfertas(data);
                    setFilteredOfertas(data);
                }else{
                    // Maneja errores si la respuesta no es exitosa.
                    setError("Error al cargar las ofertas");
                }
            } catch (error) {
                // Captura errores durante la solicitud al backend.
                setError(error.message);
            } finally {
                // Finaliza el estado de carga.
                setLoading(false);
            }
        };
        // Llama a la función para cargar las ofertas cuando el componente se monta.
        fetchOfertas();
    }, [navigate]);

    // Función para filtrar las ofertas basándose en el término de búsqueda ingresado.
    const handleSearch = (e) => {
        e.preventDefault();

        // Si no hay término de búsqueda, muestra todas las ofertas.
        if (searchTerm.trim() === '') {
            setFilteredOfertas(ofertas);
        } else {
            // Filtra las ofertas que coinciden con el término de búsqueda.
            const filtered = ofertas.filter(oferta =>
                oferta.nombre.toLowerCase().includes(searchTerm.toLowerCase())
            );
            setFilteredOfertas(filtered);
        }
    };

    // Función para restablecer el término de búsqueda y mostrar todas las ofertas.
    const handleClearSearch = () => {
        setSearchTerm('');
        setFilteredOfertas(ofertas);
    };

    // Función para redirigir al usuario a la página de detalles de una oferta específica.
    const handleVerOferta = (id) => {
        navigate(`/demandante/oferta/${id}`);
    };

    // Función para formatear una fecha en un formato legible.
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
