import React, { useEffect, useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import "../../../css/centro/styles.css";
import "../../../css/styles.css";

const GestionEmpresas = () => {
    const navigate = useNavigate();
    const [empresas, setEmpresas] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");

    useEffect(() => {
        if (!localStorage.getItem("usuario")) {
            navigate("/login", { replace: true });
        }

        const fetchEmpresas = async () => {
            try {
                const response = await fetch("/api/centro/empresas");
                if (!response.ok) throw new Error("Error al cargar empresas");
                const data = await response.json();
                setEmpresas(data);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        fetchEmpresas();
    }, [navigate]);

    const handleEliminar = async (id) => {
        if (!window.confirm("¿Seguro que quieres eliminar esta empresa?"))
            return;
        try {
            const response = await fetch(`/api/centro/empresas/${id}`, {
                method: "DELETE",
            });
            if (response.ok) {
                setEmpresas(empresas.filter((e) => e.id !== id));
            } else {
                setError("Error al eliminar empresa");
            }
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
                                    <h1>Empresas</h1>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="gestion">
                            {loading && (
                                <tr>
                                    <td colSpan="2">Cargando empresas...</td>
                                </tr>
                            )}
                            {error && (
                                <tr>
                                    <td colSpan="2" className="error">
                                        {error}
                                    </td>
                                </tr>
                            )}
                            {empresas.map((empresa) => (
                                <tr key={empresa.id}>
                                    <td>{empresa.nombre}</td>
                                    <td>{empresa.cif}</td>
                                    <td>{empresa.telefono}</td>
                                    <td>{empresa.localidad}</td>
                                    <td>{empresa.email}</td>
                                    <td>
                                        <button
                                            className="btn-elim"
                                            onClick={() =>
                                                handleEliminar(empresa.id)
                                            }
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

            <footer className="index-footer">
                <div className="footer-container">
                    <p>© 2026 Sara & Irati. Derechos reservados.</p>
                </div>
            </footer>
        </div>
    );
};

export default GestionEmpresas;
