import React, { useEffect, useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import "../../../css/centro/styles.css";
import "../../../css/styles.css";

const GestionOfertas = () => {
    const navigate = useNavigate();
    const [ofertas, setOfertas] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");

    useEffect(() => {
        if (!localStorage.getItem("usuario"))
            navigate("/login", { replace: true });

        const fetchOfertas = async () => {
            try {
                const res = await fetch("/api/centro/ofertas");
                if (!res.ok) throw new Error("Error al cargar ofertas");
                const data = await res.json();
                setOfertas(data);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        fetchOfertas();
    }, [navigate]);

    const handleEliminar = async (id) => {
        if (!window.confirm("¿Seguro que quieres eliminar esta oferta?"))
            return;
        try {
            const res = await fetch(`/api/centro/ofertas/${id}`, {
                method: "DELETE",
            });
            if (res.ok) setOfertas(ofertas.filter((o) => o.id !== id));
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
                                    <h1>Ofertas</h1>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="gestion">
                            {loading && (
                                <tr>
                                    <td colSpan="2">Cargando ofertas...</td>
                                </tr>
                            )}
                            {error && (
                                <tr>
                                    <td colSpan="2" className="error">
                                        {error}
                                    </td>
                                </tr>
                            )}
                            {ofertas.map((o) => (
                                <tr key={o.id}>
                                    <td>{o.nombre}</td>
                                    <td>{o.breve_desc}</td>
                                    <td>{o.fecha_pub}</td>
                                    <td>{o.empresa.nombre}</td>
                                    <td>
                                        <button
                                            className="btn-elim"
                                            onClick={() => handleEliminar(o.id)}
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

export default GestionOfertas;
