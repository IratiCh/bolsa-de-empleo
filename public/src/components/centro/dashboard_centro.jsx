import React, { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import "../../../css/centro/styles.css";
import "../../../css/styles.css";

const DashboardCentro = () => {
    const navigate = useNavigate();
    // Estado local para almacenar la lista de empresas pendientes por validar.
    const [empresas, setEmpresas] = useState([]);
    // Estado local para indicar si los datos aún se están cargando.
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");

    // Verifica si el usuario está autenticado.
    // Si no hay información en el almacenamiento local, redirige al inicio de sesión.
    if (!localStorage.getItem("usuario")) {
        navigate("/login", { replace: true });
    }

    const [stats, setStats] = useState({
        empresasPendientes: 0,
        empresas: 0,
        demandantes: 0,
        ofertas: 0,
        titulos: 0,
        notificaciones: 0,
    });

    useEffect(() => {
        const fetchStats = async () => {
            try {
                setLoading(true);

                // Empresas Pendientes
                const resEmpresas = await fetch(
                    "/api/centro/empresas-pendientes",
                );
                const empresasData = await resEmpresas.json();

                // Empresas Totales
                const resEmpresasTotal = await fetch(
                    "/api/centro/empresas-count",
                );
                const empresasTotal = await resEmpresasTotal.json();

                // Demandantes
                const resDemandantes = await fetch(
                    "/api/centro/demandantes-count",
                );
                const demandantesData = await resDemandantes.json();

                // Ofertas
                const resOfertas = await fetch("/api/centro/ofertas-count");
                const ofertasData = await resOfertas.json();

                // Titulos
                const resTitulos = await fetch("/api/centro/titulos");
                const titulos = await resTitulos.json();

                // Notificaciones
                const resNotif = await fetch("/api/centro/notificaciones");
                const notifData = await resNotif.json();

                setStats({
                    empresasPendientes: empresasData.length || 0,
                    empresas: empresasTotal.count || 0,
                    demandantes: demandantesData.count || 0,
                    ofertas: ofertasData.count || 0,
                    titulos: titulos.length || 0,
                    notificaciones: notifData.notificaciones?.length || 0,
                });
                setEmpresas(empresasData);
            } catch (err) {
                setError("Error al cargar dashboard");
            } finally {
                setLoading(false);
            }
        };

        fetchStats();
    }, []);

    const handleLogout = () => {
        // Limpiar el almacenamiento local
        localStorage.removeItem("usuario");
        // Redirigir al inicio y evita volver atrás
        navigate("/", { replace: true });
        // Forzar recarga si es necesario
        window.location.reload();
    };

    return (
        <div className="DIV-CENTRO">
            <header className="HEADER">
                <div className="logo">
                    <img src="/img/logo-blue.png" />
                    <div className="navegar-izq">
                        <Link to="#">Inicio</Link>
                    </div>
                </div>

                <div className="navegar">
                    <Link to="/centro/gestion_titulos">Gestión Títulos</Link>
                    <img src="/img/separador.png" alt="Separador" />
                    <Link to="/centro/informes">Informes</Link>
                    <img src="/img/separador.png" alt="Separador" />
                    <button onClick={handleLogout}>Cerrar sesión</button>
                </div>

                <div className="user">
                    <img src="/img/user.png" />
                </div>
            </header>

            <div className="CONTENIDO">
                <div className="CENTRO">
                    <div className="dashboard-cards">
                        <div className="card">
                            <Link to="/centro/activar_empresas">
                                <h2>{stats.empresasPendientes}</h2>
                                Empresas pendientes
                            </Link>
                        </div>

                        <div className="card">
                            <Link to="/centro/gestion_empresas">
                                <h2>{stats.empresas}</h2>
                                Empresas Totales
                            </Link>
                        </div>

                        <div className="card">
                            <Link to="/centro/gestion_demandantes">
                                <h2>{stats.demandantes}</h2>
                                Demandantes Totales
                            </Link>
                        </div>

                        <div className="card">
                            <Link to="/centro/gestion_ofertas">
                                <h2>{stats.ofertas}</h2>
                                Ofertas totales
                            </Link>
                        </div>

                        <div className="card">
                            <Link to="/centro/gestion_titulos">
                                <h2>{stats.titulos}</h2>
                                Titulos
                            </Link>
                        </div>

                        <div className="card">
                            <Link to="/centro/informes">
                                <h2>{stats.notificaciones}</h2>
                                Notificaciones
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <footer className="global-footer">
                <div className="footer-container">
                    <p>© 2026 Sara & Irati. Derechos reservados.</p>
                </div>
            </footer>
        </div>
    );
};

export default DashboardCentro;
