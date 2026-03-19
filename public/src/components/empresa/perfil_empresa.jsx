import React, { useEffect, useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import "../../../css/empresa/styles.css";
import "../../../css/styles.css";

const PerfilEmpresa = () => {
    const navigate = useNavigate();
    const [empresaOriginal, setEmpresaOriginal] = useState({
        nombre: "",
        email: "",
        telefono: "",
        cif: "",
        localidad: "",
    });

    // Estado del formulario
    const [empresaEdicion, setEmpresaEdicion] = useState({
        nombre: "",
        contrasena_hash: "",
        telefono: "",
        localidad: "",
    });

    const [success, setSuccess] = useState("");
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);
    const [fieldErrors, setFieldErrors] = useState({
        nombre: "",
        cif: "",
        email: "",
        telefono: "",
        contrasena_hash: "",
        localidad: "",
    });

    // Mostrar u ocultar constraseña en formulario
    const [showPassword, setShowPassword] = useState(false);
    const togglePasswordVisibility = () => {
        setShowPassword(!showPassword);
    };

    // Verificar autenticación
    useEffect(() => {
        const usuario = JSON.parse(localStorage.getItem("usuario"));
        if (!usuario) {
            navigate("/login");
            return;
        }

        const cargarPerfil = async () => {
            try {
                setLoading(true);
                const response = await fetch(
                    `/api/empresa/perfil/${usuario.id_emp}`,
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem("token")}`,
                        },
                    },
                );

                const data = await response.json();

                if (response.ok) {
                    // Datos originales (para mostrar en el header)
                    setEmpresaOriginal({
                        nombre: data.empresa.nombre,
                        cif: data.empresa.cif,
                        telefono: data.empresa.telefono,
                        email: data.empresa.email,
                        localidad: data.empresa.localidad,
                    });

                    // Datos para edición (formulario)
                    setEmpresaEdicion({
                        nombre: data.empresa.nombre,
                        cif: data.empresa.cif,
                        telefono: data.empresa.telefono,
                        email: data.empresa.email,
                        localidad: data.empresa.localidad,
                        contrasena_hash: "",
                    });

                    setShowPassword(false);
                } else {
                    setError(data.error || "Error al cargar perfil");
                }
            } catch (err) {
                setError("Error de conexión");
            } finally {
                setLoading(false);
            }
        };

        cargarPerfil();
    }, [navigate]);

    const handleDatosChange = (e) => {
        const { name, value } = e.target;
        setEmpresaEdicion((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmitDatos = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError("");
        setSuccess("");
        setFieldErrors({
            nombre: "",
            contrasena_hash: "",
            telefono: "",
            localidad: "",
        });

        try {
            const usuario = JSON.parse(localStorage.getItem("usuario"));

            const datosParaEnviar = {
                nombre: empresaEdicion.nombre,
                localidad: empresaEdicion.localidad,
                telefono: empresaEdicion.telefono,
                contrasena_hash: empresaEdicion.contrasena_hash,
            };

            const response = await fetch(
                `/api/empresa/actualizar_datos/${usuario.id_emp}`,
                {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${localStorage.getItem("token")}`,
                    },
                    body: JSON.stringify(datosParaEnviar),
                },
            );

            const data = await response.json();

            if (response.ok) {
                setSuccess("Datos actualizados correctamente");

                setEmpresaOriginal({
                    nombre: empresaEdicion.nombre,
                    email: empresaEdicion.email,
                    localidad: empresaEdicion.localidad,
                    cif: empresaEdicion.cif,
                    telefono: empresaEdicion.telefono,
                });
            } else {
                setError(
                    data.error || data.message || "Error al actualizar datos",
                );
            }
        } catch (error) {
            setError("Error de conexión");
        } finally {
            setLoading(false);
        }
    };

    if (loading) return <div className="loading">Cargando...</div>;

    const handleLogout = () => {
        localStorage.removeItem("usuario");
        localStorage.removeItem("usuario_id");
        navigate("/", { replace: true });
        window.location.reload();
    };

    return (
        <div className="OFERTAS-DEL-CENTRO">
            <header className="HEADER">
                <div className="logo">
                    <Link to="/empresa/dashboard_empresa">
                        <img src="/img/logo-blue.png" alt="Logo" />
                    </Link>
                </div>
                <div className="navegar">
                    <Link to="/empresa/perfil_empresa">Mi Perfil</Link>
                    <img src="/img/separador.png" alt="Separador" />
                    <button onClick={handleLogout}>Cerrar sesión</button>
                </div>
                <div className="user">
                    <img src="/img/user.png" alt="Usuario" />
                </div>
            </header>

            <div className="CONTENIDO">
                <div className="OFERTAS">
                    <div className="perfil-info">
                        <img src="/img/email.png" alt="Email" />
                        <div className="perfil-texto">
                            <p>
                                {empresaOriginal.nombre} | CIF:{" "}
                                {empresaOriginal.cif}
                            </p>
                            <p>{empresaOriginal.email}</p>
                        </div>
                    </div>
                    <form onSubmit={handleSubmitDatos}>
                        <table className="table-perfil">
                            <tbody>
                                <tr>
                                    <td colSpan="2">
                                        <h1>Datos Personales</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Nombre</label>
                                        <input
                                            type="text"
                                            name="nombre"
                                            required
                                            value={empresaEdicion.nombre}
                                            onChange={handleDatosChange}
                                        />
                                    </td>
                                    <td className="password-cell-empresa">
                                        <label>Contraseña</label>
                                        <input
                                            type={
                                                showPassword
                                                    ? "text"
                                                    : "password"
                                            }
                                            name="contrasena_hash"
                                            required
                                            value={
                                                empresaEdicion.contrasena_hash
                                            }
                                            onChange={handleDatosChange}
                                        />
                                        <button
                                            type="button"
                                            className="toggle-password-empresa"
                                            onClick={togglePasswordVisibility}
                                            aria-label={
                                                showPassword
                                                    ? "Ocultar contraseña"
                                                    : "Mostrar contraseña"
                                            }
                                        >
                                            <img
                                                src={
                                                    showPassword
                                                        ? "/img/ocultar_contrasena.png"
                                                        : "/img/mostrar_contrasena.png"
                                                }
                                                alt={
                                                    showPassword
                                                        ? "Ocultar contraseña"
                                                        : "Mostrar contraseña"
                                                }
                                            />
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Teléfono</label>
                                        <input
                                            type="text"
                                            name="telefono"
                                            required
                                            value={empresaEdicion.telefono}
                                            onChange={handleDatosChange}
                                        />
                                    </td>
                                    <td>
                                        <label>Localidad</label>
                                        <input
                                            type="text"
                                            name="localidad"
                                            required
                                            value={empresaEdicion.localidad}
                                            onChange={handleDatosChange}
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        {error && <div className="error">{error}</div>}
                        {success && <div className="success">{success}</div>}

                        <div className="btn-datos">
                            <button type="submit" className="act-datos">
                                {loading ? "PROCESANDO..." : "ACTUALIZAR DATOS"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <footer className="global-footer">
                <div className="footer-container">
                    <p>© 2025 Sara & Irati. Derechos reservados.</p>
                </div>
            </footer>
        </div>
    );
};

export default PerfilEmpresa;
