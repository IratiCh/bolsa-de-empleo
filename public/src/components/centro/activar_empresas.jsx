import React, { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import "../../../css/centro/styles.css";
import "../../../css/styles.css";

const ActivarEmpresas = () => {
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

    useEffect(() => {
        const fetchEmpresas = async () => {
            // Función para obtener la lista de empresas desde el servidor.
            try {
                // Solicita al backend las empresas pendientes de validación.
                const response = await fetch("/api/centro/empresas-pendientes");

                // Si la respuesta no es exitosa, actualiza el estado con un mensaje de error.
                if (!response.ok) {
                    setError("Error al cargar empresas");
                }

                // Convierte la respuesta en formato JSON y actualiza el estado de empresas.
                const data = await response.json();
                setEmpresas(data);
            } catch (err) {
                setError(err.message);
            } finally {
                // Independientemente del resultado, marca el estado de carga como falso.
                setLoading(false);
            }
        };

        // Ejecuta la función de obtención de datos cuando el componente se monta.
        fetchEmpresas();
    }, []);

    // Función para validar o rechazar una empresa, dependiendo de la acción solicitada.
    const handleValidacion = async (empresaId, accion) => {
        try {
            const response = await fetch(
                `/api/centro/validar-empresa/${empresaId}`,
                {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ accion }),
                },
            );

            const data = await response.json();

            // Si la respuesta fue exitosa, actualiza el estado de la empresa validada.
            if (response.ok && data.success) {
                setEmpresas(
                    empresas.filter((empresa) => empresa.id !== empresaId),
                );
            } else {
                // Muestra un mensaje de error si la validación no fue exitosa.
                setError(data.message || "Error al procesar la validación");
            }
        } catch (err) {
            setError("Error de conexión al validar empresa");
            console.error("Error:", err);
        }
    };

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
                                <th colSpan="4">
                                    <h1>Empresas por validar</h1>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {loading && (
                                <tr>
                                    <td colSpan="4">Cargando empresas...</td>
                                </tr>
                            )}
                            {error && (
                                <tr>
                                    <td colSpan="4" className="error">
                                        {error}
                                    </td>
                                </tr>
                            )}
                            {empresas.map((empresa) => (
                                <tr key={empresa.id}>
                                    <td>{empresa.nombre}</td>
                                    <td>{empresa.cif}</td>
                                    <td>{empresa.telefono}</td>
                                    <td>
                                        <button
                                            className="btn-aceptar"
                                            onClick={() =>
                                                handleValidacion(
                                                    empresa.id,
                                                    "aceptar",
                                                )
                                            }
                                        >
                                            ACEPTAR
                                        </button>
                                        <button
                                            className="btn-cancelar"
                                            onClick={() =>
                                                handleValidacion(
                                                    empresa.id,
                                                    "rechazar",
                                                )
                                            }
                                        >
                                            CANCELAR
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

export default ActivarEmpresas;
