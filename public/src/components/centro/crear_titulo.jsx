import React, { useEffect, useState } from "react"; // useEffect y useState para manejar efectos secundarios y estados locales.
import { Link, useNavigate } from "react-router-dom";
// Estilos CSS específicos para la página.
import "../../../css/centro/styles.css";
import "../../../css/styles.css";

const CrearTitulo = () => {
    const navigate = useNavigate();
    // Estado local para almacenar el nombre del título que se desea crear.
    const [nombre, setNombre] = useState("");
    const [error, setError] = useState("");
    const [success, setSuccess] = useState("");

    // Comprobación inicial para verificar si el usuario está autenticado.
    useEffect(() => {
        if (!localStorage.getItem("usuario")) {
            // Si no hay un usuario en el almacenamiento local, redirige al inicio de sesión.
            navigate("/login", { replace: true });
        }
    }, [navigate]);

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            // Envía una solicitud POST al backend para crear un nuevo título.
            const response = await fetch("/api/centro/titulos", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ nombre }),
            });

            // Convierte la respuesta del servidor en formato JSON.
            const data = await response.json();

            // Si la solicitud fue exitosa y el backend confirma el éxito:
            if (response.ok && data.success) {
                setSuccess("Título creado correctamente");
                setError("");
                // Limpia los estados después de crear el título.
                setNombre("");
            } else {
                // Si hubo un error en la solicitud, muestra un mensaje de error
                setError(data.message || "Error al crear título");
                setSuccess("");
            }
        } catch (err) {
            // Captura errores durante la comunicación con el servidor.
            setError("Error de conexión");
            setSuccess("");
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
                    <h1>Crear Nuevo Título</h1>
                    <form onSubmit={handleSubmit}>
                        <label htmlFor="text">Nombre</label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            required
                            placeholder="Nombre"
                            onChange={(e) => setNombre(e.target.value)}
                        />
                        {error && <div className="error">{error}</div>}
                        {success && <div className="success">{success}</div>}
                        <div className="btn-titulo">
                            <button type="submit" className="crear-titulo">
                                CREAR TÍTULO
                            </button>
                        </div>
                    </form>
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

export default CrearTitulo;
