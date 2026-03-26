import React, { useEffect, useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import "../../../css/centro/styles.css";
import "../../../css/styles.css";

const GestionDemandantes = () => {
    const navigate = useNavigate();
    const [demandantes, setDemandantes] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");

    useEffect(() => {
        if (!localStorage.getItem("usuario"))
            navigate("/login", { replace: true });

        const fetchDemandantes = async () => {
            try {
                const res = await fetch("/api/centro/demandantes");
                if (!res.ok) throw new Error("Error al cargar demandantes");
                const data = await res.json();
                setDemandantes(data);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        fetchDemandantes();
    }, [navigate]);

    const handleEliminar = async (id) => {
        if (!window.confirm("¿Seguro que quieres eliminar este demandante?"))
            return;
        try {
            const res = await fetch(`/api/centro/demandantes/${id}`, {
                method: "DELETE",
            });
            if (res.ok) setDemandantes(demandantes.filter((d) => d.id !== id));
            else setError("Error al eliminar");
        } catch (err) {
            setError(err.message);
        }
    };

    const handleLogout = () => {
        localStorage.removeItem("usuario");
        navigate("/", { replace: true });
        window.location.reload();
    };

    return (
        <div className="DIV-CENTRO">
            <header className="HEADER">
                <div className="logo">
                    <img src="/img/logo-blue.png" />
                    <div className="navegar-izq">
                        <Link to="/centro/dashboard_centro">Inicio</Link>
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
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    <h1>Demandantes</h1>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="gestion">
                            {loading && (
                                <tr>
                                    <td colSpan="2">Cargando demandantes...</td>
                                </tr>
                            )}
                            {error && (
                                <tr>
                                    <td colSpan="2" className="error">
                                        {error}
                                    </td>
                                </tr>
                            )}
                            {demandantes.map((d) => (
                                <tr key={d.id}>
                                    <td>
                                        {d.nombre + " " + d.ape1 + " " + d.ape2}
                                    </td>
                                    <td>{d.dni}</td>
                                    <td>{d.tel_movil}</td>
                                    <td>{d.email}</td>
                                    <td>
                                        <button
                                            className="btn-elim"
                                            onClick={() => handleEliminar(d.id)}
                                        >
                                            ELIMINAR
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
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

export default GestionDemandantes;
