import React, { useEffect, useState } from "react";
import { Link, useNavigate, useParams } from "react-router-dom";
import "../../../css/empresa/styles.css";
import "../../../css/styles.css";

function ModificarOferta() {
    const navigate = useNavigate();
    const { id } = useParams();

    const [formData, setFormData] = useState({
        nombre: "",
        breve_desc: "",
        desc: "",
        num_puesto: "",
        horario: "",
        obs: "",
        id_tipo_cont: "",
        titulos: [],
    });

    const [tiposContrato, setTiposContrato] = useState([]);
    const [titulos, setTitulos] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");
    const [success, setSuccess] = useState("");

    // Auth check
    useEffect(() => {
        const usuario = JSON.parse(localStorage.getItem("usuario"));
        if (!usuario || !usuario.id_emp) {
            navigate("/login", { replace: true });
        }
    }, [navigate]);

    // Cargar datos iniciales (tipos + titulos)
    useEffect(() => {
        const fetchData = async () => {
            try {
                setLoading(true);

                const tiposResponse = await fetch(
                    "/api/ofertas/tipos-contrato",
                );
                const tiposData = await tiposResponse.json();
                if (tiposData.success) setTiposContrato(tiposData.tipos);

                const titulosResponse = await fetch("/api/ofertas/titulos");
                const titulosData = await titulosResponse.json();
                if (titulosData.success) setTitulos(titulosData.titulos);
            } catch {
                setError("Error al cargar datos iniciales");
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    // Cargar oferta a modificar
    useEffect(() => {
        const fetchOferta = async () => {
            try {
                const response = await fetch(`/api/empresa/datos_oferta/${id}`);
                const data = await response.json();

                if (response.ok) {
                    setFormData({
                        nombre: data.oferta.nombre,
                        breve_desc: data.oferta.breve_desc,
                        desc: data.oferta.desc,
                        num_puesto: data.oferta.num_puesto,
                        horario: data.oferta.horario,
                        obs: data.oferta.obs,
                        id_tipo_cont: data.oferta.id_tipo_cont,
                        titulos: data.oferta.titulos?.map((t) => t.id) || [],
                    });
                } else {
                    setError("Error al cargar oferta");
                }
            } catch {
                setError("Error de conexión");
            }
        };

        fetchOferta();
    }, [id]);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData((prev) => ({
            ...prev,
            [name]: value,
        }));
    };

    const handleSelectTitulo = (e, index) => {
        const { value } = e.target;
        const newTitulos = [...formData.titulos];
        newTitulos[index] = value;

        setFormData((prev) => ({
            ...prev,
            titulos: newTitulos,
        }));
    };

    // ACTUALIZAR OFERTA
    const handleSubmit = async (e) => {
        e.preventDefault();
        setError("");
        setSuccess("");
        setLoading(true);

        try {
            const usuario = JSON.parse(localStorage.getItem("usuario"));
            if (!usuario || !usuario.id_emp) {
                navigate("/login", { replace: true });
                return;
            }

            const response = await fetch(
                `/api/empresa/modificar_oferta/${id}`,
                {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        ...formData,
                        titulos: formData.titulos.filter((t) => t),
                        id_emp: usuario.id_emp,
                    }),
                },
            );

            const data = await response.json();

            if (response.ok) {
                setSuccess("Oferta actualizada correctamente");
            } else {
                setError(data.error || "Error al actualizar oferta");
            }
        } catch (error) {
            setError("Error de conexión");
        } finally {
            setLoading(false);
        }
    };

    const handleLogout = () => {
        localStorage.removeItem("usuario");
        navigate("/", { replace: true });
        window.location.reload();
    };

    return (
        <div className="OFERTAS-DEL-CENTRO">
            <header className="HEADER">
                <div className="logo">
                    <Link to="/empresa/dashboard_empresa">
                        <img src="/img/logo-blue.png" />
                    </Link>
                </div>
                <div className="navegar">
                    <Link to="/empresa/perfil_empresa">Mi Perfil</Link>
                    <img src="/img/separador.png" alt="Separador" />
                    <button onClick={handleLogout}>Cerrar sesión</button>
                </div>
                <div className="user">
                    <img src="/img/user.png" />
                </div>
            </header>

            <div className="CONTENIDO">
                <div className="OFERTAS">
                    <h1>Modificar Oferta</h1>

                    {loading && <p>Cargando...</p>}

                    <form onSubmit={handleSubmit}>
                        <table className="table-ofert">
                            <tbody>
                                <tr>
                                    <td colSpan="2">
                                        <label>Nombre</label>
                                        <input
                                            type="text"
                                            id="nombre"
                                            name="nombre"
                                            value={formData.nombre}
                                            onChange={handleChange}
                                            required
                                        />
                                    </td>

                                    <td colSpan="2">
                                        <label>Tipo de Contrato</label>
                                        <select
                                            name="id_tipo_cont"
                                            value={formData.id_tipo_cont}
                                            onChange={handleChange}
                                            required
                                        >
                                            <option value="" disabled>
                                                Tipo de Contrato
                                            </option>
                                            {tiposContrato.map((tipo) => (
                                                <option
                                                    key={tipo.id}
                                                    value={tipo.id}
                                                >
                                                    {tipo.nombre}
                                                </option>
                                            ))}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colSpan="2">
                                        <label>Breve descripción</label>
                                        <input
                                            type="text"
                                            id="breve_desc"
                                            name="breve_desc"
                                            value={formData.breve_desc}
                                            onChange={handleChange}
                                            required
                                        />
                                    </td>

                                    <td>
                                        <label>Puestos</label>
                                        <input
                                            type="number"
                                            id="num_puesto"
                                            name="num_puesto"
                                            value={formData.num_puesto}
                                            onChange={handleChange}
                                            required
                                        />
                                    </td>

                                    <td>
                                        <label>Horario</label>
                                        <input
                                            type="text"
                                            id="horario"
                                            name="horario"
                                            value={formData.horario}
                                            onChange={handleChange}
                                            required
                                        />
                                    </td>
                                </tr>

                                <tr>
                                    <td colSpan="2">
                                        <label>Título principal</label>
                                        <select
                                            name="titulo_principal"
                                            required
                                            value={formData.titulos[0] || ""}
                                            onChange={(e) =>
                                                handleSelectTitulo(e, 0)
                                            }
                                        >
                                            <option value="" disabled>
                                                Título principal
                                            </option>
                                            {titulos.map((t) => (
                                                <option key={t.id} value={t.id}>
                                                    {t.nombre}
                                                </option>
                                            ))}
                                        </select>
                                    </td>

                                    <td colSpan="2">
                                        <label>Otro título</label>
                                        <select
                                            name="titulo_secundario"
                                            value={formData.titulos[1] || ""}
                                            onChange={(e) =>
                                                handleSelectTitulo(e, 1)
                                            }
                                        >
                                            <option value="" disabled>
                                                Otro Título
                                            </option>
                                            {titulos.map((t) => (
                                                <option key={t.id} value={t.id}>
                                                    {t.nombre}
                                                </option>
                                            ))}
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td colSpan="2">
                                        <label>Descripción</label>
                                        <textarea
                                            name="desc"
                                            rows="10"
                                            cols="50"
                                            value={formData.desc}
                                            onChange={handleChange}
                                            required
                                        ></textarea>
                                    </td>

                                    <td colSpan="2">
                                        <label>Observaciones</label>
                                        <textarea
                                            name="obs"
                                            required
                                            rows="10"
                                            cols="50"
                                            value={formData.obs}
                                            onChange={handleChange}
                                        ></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        {error && <div className="error">{error}</div>}
                        {success && <div className="success">{success}</div>}

                        <div className="btn-oferta">
                            <button type="submit" className="crear-oferta">
                                {loading ? "GUARDANDO..." : "ACTUALIZAR OFERTA"}
                            </button>
                        </div>
                    </form>
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

export default ModificarOferta;
