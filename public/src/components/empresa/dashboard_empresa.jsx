import React, { useEffect, useState } from "react";
import { Link, useNavigate, useParams } from "react-router-dom";
import "../../../css/empresa/styles.css";
import "../../../css/styles.css";

function DashboardEmpresa() {
    const navigate = useNavigate();
    // Estado local que almacena la lista de ofertas laborales.
    const [ofertas, setOfertas] = useState([]);
    const [historico, setHistorico] = useState([]);
    // Estado local para indicar si los datos aún se están cargando.
    const [loading, setLoading] = useState(true);
    const [loadingHistorico, setLoadingHistorico] = useState(true);
    // Estado local para manejar mensajes de error.
    const [error, setError] = useState("");
    const [errorHistorico, setErrorHistorico] = useState("");

    // Motivo cerrar oferta
    const [modalCerrar, setModalCerrar] = useState(false);
    const [motivoCierre, setMotivoCierre] = useState("");
    const [ofertaACerrar, setOfertaACerrar] = useState(null);

    // Comprueba si el usuario está autenticado. Si no, lo redirige al inicio de sesión.
    if (!localStorage.getItem("usuario")) {
        // Si el usuario no pertenece al rol de empresa, lo redirige al inicio de sesión.
        navigate("/login", { replace: true });
    } else {
        const usuario = JSON.parse(localStorage.getItem("usuario"));
        if (!usuario.id_emp) {
            navigate("/login", { replace: true });
        }
    }

    // Función para cargar las ofertas abiertas desde el backend.
    useEffect(() => {
        const fetchOfertas = async () => {
            try {
                // Comprueba la existencia de un usuario autenticado y que pertenece a una empresa.
                const usuario = JSON.parse(localStorage.getItem("usuario"));
                if (!usuario || !usuario.id_emp) {
                    navigate("/login", { replace: true });
                    return;
                }

                // Solicita las ofertas laborales abiertas del backend para la empresa específica.
                const response = await fetch(
                    `/api/ofertas/abiertas?id_emp=${usuario.id_emp}`,
                );

                // Si la solicitud falla, establece un mensaje de error.
                if (!response.ok) {
                    setError("Error al cargar las ofertas");
                }

                const data = await response.json();
                // Actualiza el estado con la lista de ofertas obtenida.
                setOfertas(data);
            } catch (error) {
                // Captura errores de conexión o problemas en la solicitud al backend.
                setError("Error al cargar ofertas:", error);
            } finally {
                setLoading(false);
            }
        };
        // Llama a la función para cargar las ofertas cuando el componente se monta.
        fetchOfertas();
    }, []);

    useEffect(() => {
        const fetchHistorico = async () => {
            try {
                const usuario = JSON.parse(localStorage.getItem("usuario"));
                if (!usuario || !usuario.id_emp) {
                    navigate("/login", { replace: true });
                    return;
                }

                const response = await fetch(
                    `/api/empresa/historico-ofertas?id_emp=${usuario.id_emp}`,
                );
                const data = await response.json();

                if (!response.ok) {
                    setErrorHistorico(
                        data.error || "Error al cargar histórico",
                    );
                    return;
                }

                setHistorico(data.ofertas || []);
            } catch (error) {
                setErrorHistorico("Error al cargar histórico");
            } finally {
                setLoadingHistorico(false);
            }
        };

        fetchHistorico();
    }, []);

    // Abrir modal de cierre
    const handleCerrarOferta = (oferta) => {
        setOfertaACerrar(oferta);
        setModalCerrar(true);
    };

    // Confirmar cierre
    const confirmarCerrarOferta = async () => {
        if (!ofertaACerrar) return;

        try {
            const response = await fetch(
                `/api/ofertas/${ofertaACerrar.id}/cerrar`,
                {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ motivo: motivoCierre }),
                },
            );

            if (!response.ok) throw new Error("Error al cerrar la oferta");

            const data = await response.json();

            // Quitar de abiertas
            setOfertas((prev) => prev.filter((o) => o.id !== ofertaACerrar.id));

            // Añadir al histórico con lo que devuelve la API
            setHistorico((prev) => [data.oferta, ...prev]);

            // Reset modal
            setModalCerrar(false);
            setMotivoCierre("");
            setOfertaACerrar(null);
        } catch (err) {
            alert("Error al cerrar la oferta");
        }
    };

    // Redirige al usuario al formulario para crear una nueva oferta laboral.
    const handleCrearOferta = () => {
        navigate("/empresa/crear_oferta");
    };

    // Redirige al usuario al formulario para asignar la oferta a un candidato.
    const handleAsignarOferta = (id) => {
        navigate(`/empresa/asignar_oferta/${id}`);
    };

    const handleModificarOferta = (id) => {
        navigate(`/empresa/modificar_oferta/${id}`);
    };

    const handleLogout = () => {
        // Limpiar el almacenamiento local
        localStorage.removeItem("usuario");
        // Redirigir al inicio y evita volver atrás
        navigate("/", { replace: true });
        // Forzar recarga si es necesario
        window.location.reload();
    };

    const formatDate = (dateString) => {
        if (!dateString) return "No disponible";
        try {
            return new Date(dateString).toLocaleDateString("es-ES");
        } catch {
            return dateString;
        }
    };

    const formatCandidato = (oferta) => {
        if (!oferta?.candidato_nombre) return "Sin candidato";
        if (oferta.candidato_tipo === "externo") {
            return `${oferta.candidato_nombre} (Externo)`;
        }
        return oferta.candidato_nombre;
    };

    return (
        <div className="OFERTAS-DEL-CENTRO">
            <header className="HEADER">
                <div className="logo">
                    <img src="/img/logo-blue.png" alt="Logo" />
                </div>
                <div className="navegar">
                    <Link to="/empresa/perfil_empresa">Mi Perfil</Link>
                    <img src="/img/separador.png" alt="Separador" />
                    <button onClick={handleLogout}>Cerrar sesión</button>
                </div>
                <div className="user">
                    <img src="/img/user.png" alt="User" />
                </div>
            </header>

            <div className="CONTENIDO">
                <div className="OFERTAS">
                    <table className="table-ofert">
                        <thead>
                            <tr>
                                <th colSpan="2">
                                    <h1>Mis Ofertas Abiertas</h1>
                                </th>
                                <th>
                                    <button
                                        type="submit"
                                        className="BOTON-CREAR"
                                        onClick={() => handleCrearOferta()}
                                    >
                                        CREAR OFERTA
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {loading && (
                                <tr>
                                    <td colSpan="3">Cargando ofertas...</td>
                                </tr>
                            )}
                            {error && (
                                <tr>
                                    <td colSpan="3" className="error">
                                        {error}
                                    </td>
                                </tr>
                            )}
                            {ofertas.map((oferta) => (
                                <tr key={oferta.id}>
                                    <td>{oferta.nombre}</td>
                                    <td>{oferta.breve_desc}</td>
                                    <td>
                                        <button
                                            className="btn-asignarOfer"
                                            onClick={() =>
                                                handleAsignarOferta(oferta.id)
                                            }
                                        >
                                            ASIGNAR
                                        </button>
                                        <button
                                            className="btn-modificar"
                                            onClick={() =>
                                                handleModificarOferta(oferta.id)
                                            }
                                        >
                                            MODIFICAR
                                        </button>
                                        <button
                                            className="btn-cerrar"
                                            onClick={() =>
                                                handleCerrarOferta(oferta)
                                            }
                                        >
                                            CERRAR
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            {ofertas.length === 0 && !loading && (
                                <tr>
                                    <td
                                        colSpan="3"
                                        style={{ textAlign: "center" }}
                                    >
                                        No hay ofertas abiertas
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                    {modalCerrar && (
                        <div className="motivoCierre">
                            <div>
                                <h2>Motivo de cierre</h2>
                            </div>
                            <div className="motivoCierreContenido">
                                <textarea
                                    value={motivoCierre}
                                    onChange={(e) =>
                                        setMotivoCierre(e.target.value)
                                    }
                                    placeholder="Escribe el motivo de cierre..."
                                />
                            </div>
                            <div className="motivoCierreContenido">
                                <button
                                    className="btn-cancCierre"
                                    onClick={() => setModalCerrar(false)}
                                >
                                    Cancelar
                                </button>
                                <button
                                    className="btn-confirCierre"
                                    onClick={confirmarCerrarOferta}
                                >
                                    Confirmar
                                </button>
                            </div>
                        </div>
                    )}
                    <table className="table-ofert">
                        <thead>
                            <tr>
                                <th colSpan="4">
                                    <h1>Histórico de Ofertas</h1>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {loadingHistorico && (
                                <tr>
                                    <td colSpan="4">Cargando histórico...</td>
                                </tr>
                            )}
                            {errorHistorico && (
                                <tr>
                                    <td colSpan="4" className="error">
                                        {errorHistorico}
                                    </td>
                                </tr>
                            )}
                            {historico.map((oferta) => (
                                <tr key={`hist-${oferta.id}`}>
                                    <td>{oferta.nombre}</td>
                                    <td>{formatDate(oferta.fecha_cierre)}</td>
                                    <td>{formatCandidato(oferta)}</td>
                                    <td>{oferta.motivo_cierre || "-"}</td>
                                    <td>
                                        <button
                                            className="btn-modificar"
                                            onClick={() =>
                                                handleAsignarOferta(oferta.id)
                                            }
                                        >
                                            SOLICITUDES
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            {historico.length === 0 && !loadingHistorico && (
                                <tr>
                                    <td
                                        colSpan="4"
                                        style={{ textAlign: "center" }}
                                    >
                                        No hay histórico
                                    </td>
                                </tr>
                            )}
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
}

export default DashboardEmpresa;
