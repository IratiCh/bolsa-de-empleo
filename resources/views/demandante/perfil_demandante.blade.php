<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="{{ asset('css/demandante/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
  <div class="DIV-DEMANDANTE PERFIL">

    <header class="HEADER-PERFIL">
      <div class="logo">
        <a href="dashboard_demandante.blade.php">
          <img src="{{ asset('img/logo-purp.png') }}" />
        </a>
      </div>
      <div class="user">
        <a href="perfil_demandante.blade.php">
          <img src="{{ asset('img/user.png') }}" />
      </div>

    </header>

    <div class="DEMANDANTE">

      <div class="perfil-container">
        <div class="perfil-info">
          <img src="{{ asset('img/email.png') }}" />
          <div class="perfil-texto">
            <p>Nombre Apellidos</p>
            <p>Email@email.email</p>
          </div>
        </div>

        <div class="btn-perfil">
          <a href="ofertas_inscritas.blade.php">
            <button type="submit" class="act-ofertas-apun">OFERTAS APUNTADAS</button>
          </a>
          <a href="../index.blade.php">
            <button type="submit" class="btn-logout">CERRAR SESIÓN</button>
          </a>
        </div>
      </div>

      <form>
        <table class="table-perfil">
          <tbody>
            <h1>Datos Personales</h1>
            <tr>
              <td colspan="2">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Nombre">
              </td>
              <td colspan="2">
                <label for="text">Primer Apellido</label>
                <input type="text" id="apellido1" name="apellido1" required placeholder="Primer Apellido">
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <label for="apellido2">Segundo Apellido</label>
                <input type="text" id="apellido2" name="apellido2" required placeholder="Segundo Apellido">
              </td>
              <td>
                <label for="telefono">Teléfono</label>
                <input type="number" id="telefono" name="telefono" required placeholder="Teléfono">
              </td>
            </tr>
            <tr>
              <td>
                <label for="passw">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="Contraseña">
              </td>
            </tr>
          </tbody>
        </table>
        <div class="btn-datos">
          <button type="submit" class="act-datos">ACTUALIZAR DATOS</button>
        </div>
      </form>


      <form>
        <table class="table-titulos">
          <tbody>
            <h1>Mis Títulos</h1>
            <tr>
              <td colspan="2">
                <h2>Primer Título</h2>
              </td>
            </tr>
            <tr>
              <td>
                <label for="nombre">Nombre</label>
                <select name="select">
                  <option value="value1" selected disabled>Nombre</option>
                  <option value="value1">Value 1</option>
                  <option value="value2">Value 2</option>
                  <option value="value3">Value 3</option>
                </select>
              </td>
              <td>
                <label for="centro">Centro</label>
                <input type="text" id="centro" name="centro" required placeholder="Centro">
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label for="situacionCurso">Situación del Curso</label>
                <select name="select">
                  <option value="value1" selected disabled>Situación del Curso</option>
                  <option value="value1">Value 1</option>
                  <option value="value2">Value 2</option>
                  <option value="value3">Value 3</option>
                </select>
              </td>
              <td>
                <label for="año">Año de Adquisición</label>
                <input type="date" id="año" name="año" required placeholder="Año">
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <h2>Segundo Título</h2>
              </td>
            </tr>
            <tr>
              <td>
                <label for="nombre">Nombre</label>
                <select name="select">
                  <option value="value1" selected disabled>Nombre</option>
                  <option value="value1">Value 1</option>
                  <option value="value2">Value 2</option>
                  <option value="value3">Value 3</option>
                </select>
              </td>
              <td>
                <label for="centro">Centro</label>
                <input type="text" id="centro" name="centro" required placeholder="Centro">
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label for="situacionCurso">Situación del Curso</label>
                <select name="select">
                  <option value="value1" selected disabled>Situación del Curso</option>
                  <option value="value1">Value 1</option>
                  <option value="value2">Value 2</option>
                  <option value="value3">Value 3</option>
                </select>
              </td>
              <td>
                <label for="año">Año de Adquisición</label>
                <input type="date" id="año" name="año" required placeholder="Año">
              </td>
            </tr>
          </tbody>
        </table>
        <div class="btn-info">
          <button type="submit" class="act-info">GUARDAR INFORMACIÓN</button>
        </div>
      </form>
    </div>


    <footer class="global-footer footer-perfil">
      <div class="footer-container">
        <div class="footer-section global-section">
          <h4>Información General</h4>
          <ul>
            <li><a href="#">Quiénes somos</a></li>
            <li><a href="#">Nuestro propósito</a></li>
            <li><a href="#">Contacto</a></li>
          </ul>
        </div>
        <div class="footer-section global-section">
          <h4>Ayuda al Usuario</h4>
          <ul>
            <li><a href="#">Preguntas frecuentes</a></li>
            <li><a href="#">Plataforma</a></li>
            <li><a href="#">Soporte técnico</a></li>
          </ul>
        </div>
        <div class="footer-section global-section">
          <h4>Legal y Privacidad</h4>
          <ul>
            <li><a href="#">Términos y condiciones</a></li>
            <li><a href="#">Política de privacidad</a></li>
            <li><a href="#">Aviso legal</a></li>
          </ul>
        </div>
        <div class="footer-section global-section subscribe">
          <h4>Suscríbete</h4>
          <div>
            <input class="global-input-subscribe" type="email" placeholder="Email">
            <button>
              <img src="{{ asset('img/suscribe-purp.png') }}" />
            </button>
          </div>
          <p>Suscríbete para enterarte de las nuevas ofertas de empleo.</p>
        </div>
      </div>
      <div class="footer-copy">
        <p>© 2025 Sara & Irati. Derechos reservados.
          <a href="#">Política de Privacidad</a>
          <a href="#">Términos de Servicio</a>
        </p>
        <div class="social-icons">
          <img src="{{ asset('img/fc-white.png') }}" />
          <img src="{{ asset('img/in-white.png') }}" />
          <img src="{{ asset('img/tw-white.png') }}" />
          <img src="{{ asset('img/pint-white.png') }}" />
        </div>
      </div>
    </footer>
  </div>
</body>

</html>

<!-- 

import React, { useEffect, useState } from "react";
import { Link, useNavigate } from 'react-router-dom';
import "../../../css/demandante/styles.css";
import "../../../css/styles.css";

function PerfilDemandante() {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        nombre: '',
        ape1: '',
        ape2: '',
        tel_movil: '',
        email: '',
        password: ''
    });
    const [misTitulos, setMisTitulos] = useState([{ 
        titulo_id: '', 
        centro: '', 
        año: '', 
        cursando: '' 
    }]);
    const [titulos, setTitulos] = useState([]);
    const [allTitulos, setAllTitulos] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');

    // Verificar autenticación
    useEffect(() => {
        if (!localStorage.getItem('usuario')) {
            navigate('/login', { replace: true });
        }
    }, [navigate]);

    // Cargar datos iniciales
    useEffect(() => {
        const fetchData = async () => {
            try {
                setLoading(true);
                const usuario = JSON.parse(localStorage.getItem('usuario'));
                
                // Datos del demandante
                const perfilResponse = await fetch(`/api/demandante/perfil/${usuario.id_dem}`);
                const perfilData = await perfilResponse.json();
                
                if (perfilData.demandante) {
                    setFormData({
                        nombre: perfilData.demandante.nombre || '',
                        ape1: perfilData.demandante.ape1 || '',
                        ape2: perfilData.demandante.ape2 || '',
                        tel_movil: perfilData.demandante.tel_movil || '',
                        email: perfilData.demandante.email || '',
                        password: ''
                    });
                    
                    if (perfilData.titulos) {
                        setTitulos(perfilData.titulos);
                    }
                }
                
                // Todos los títulos disponibles
                const titulosResponse = await fetch('/api/titulos');
                const titulosData = await titulosResponse.json();
                setAllTitulos(titulosData.titulos || []);
                
            } catch (error) {
                setError('Error al cargar datos');
            } finally {
                setLoading(false);
            }
        };
        
        fetchData();
    }, []);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleTituloChange = (index, field, value) => {
        const updated = [...misTitulos];
        updated[index] = { ...updated[index], [field]: value };
        setMisTitulos(updated);
    };

    const handleSubmitDatos = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError('');
        setSuccess('');
        
        try {
            const usuario = JSON.parse(localStorage.getItem('usuario'));
            const response = await fetch(`/api/demandante/actualizar/${usuario.id_dem}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (response.ok) {
                setSuccess('Datos actualizados correctamente');
                // Actualizar email en localStorage si cambió
                if (formData.email !== usuario.email) {
                    const updatedUser = {...usuario, email: formData.email};
                    localStorage.setItem('usuario', JSON.stringify(updatedUser));
                }
            } else {
                setError(data.error || 'Error al actualizar');
            }
        } catch (error) {
            setError('Error de conexión');
        } finally {
            setLoading(false);
        }
    };

    const handleSubmitTitulos = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError('');
        setSuccess('');
        
        try {
            const usuario = JSON.parse(localStorage.getItem('usuario'));
            const response = await fetch(`/api/demandante/titulos/${usuario.id_dem}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({ titulos })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                setSuccess('Títulos actualizados correctamente');
            } else {
                setError(data.error || 'Error al guardar títulos');
            }
        } catch (error) {
            setError('Error de conexión');
        } finally {
            setLoading(false);
        }
    };

    const handleLogout = () => {
        localStorage.removeItem('usuario');
        localStorage.removeItem('token');
        navigate('/');
    };

    const handleOfertasInscritas = () => {
        navigate('/demandante/ofertas_inscritas');
    };

    if (loading) return <div className="loading">Cargando...</div>;

    return (
        <div className="DIV-DEMANDANTE PERFIL">
            <header className="HEADER-PERFIL">
                <div className="logo">
                    <Link to="/demandante/dashboard_demandante">
                        <img src="/img/logo-purp.png" alt="Logo" />
                    </Link>
                </div>
                <div className="user">
                    <Link to="/demandante/perfil_demandante">
                        <img src="/img/user.png" alt="Usuario" />
                    </Link>
                </div>
            </header>
        
            <div className="DEMANDANTE">
                <div className="perfil-container">
                    <div className="perfil-info">
                        <img src="/img/email.png" alt="Email"/>
                        <div className="perfil-texto">
                            <p>{formData.nombre} {formData.ape1} {formData.ape2}</p>
                            <p>{formData.email}</p>
                        </div>
                    </div>
            
                    <div className="btn-perfil">
                        <button type="button" className="act-ofertas-apun" onClick={handleOfertasInscritas}>
                            OFERTAS APUNTADAS
                        </button>
                        <button type="button" className="btn-logout" onClick={handleLogout}>
                            CERRAR SESIÓN
                        </button>
                    </div>
                </div>
        
                <form onSubmit={handleSubmitDatos}>
                    <table className="table-perfil">
                        <tbody>
                            <tr><td colSpan="2"><h1>Datos Personales</h1></td></tr>
                            <tr>
                                <td>
                                    <label>Nombre</label>
                                    <input 
                                        type="text" 
                                        name="nombre" 
                                        required 
                                        value={formData.nombre}
                                        onChange={handleChange}
                                    />
                                </td>
                                <td>
                                    <label>Primer Apellido</label>
                                    <input 
                                        type="text" 
                                        name="ape1" 
                                        required 
                                        value={formData.ape1}
                                        onChange={handleChange}
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Segundo Apellido</label>
                                    <input 
                                        type="text" 
                                        name="ape2" 
                                        value={formData.ape2}
                                        onChange={handleChange}
                                    />
                                </td>
                                <td>
                                    <label>Teléfono</label>
                                    <input 
                                        type="number" 
                                        name="tel_movil" 
                                        required
                                        maxLength="9"
                                        value={formData.tel_movil}
                                        onChange={handleChange}
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td colSpan="2">
                                    <label>Contraseña</label>
                                    <input 
                                        type="password" 
                                        name="password"
                                        value={formData.password}
                                        onChange={handleChange}
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {error && <div className="error">{error}</div>}
                    {success && <div className="success">{success}</div>}
                    <div className="btn-datos">
                        <button type="submit" className="act-datos">ACTUALIZAR DATOS</button>
                    </div>
                </form>
              
                <form onSubmit={handleSubmitTitulos}>
                    <table className="table-titulos">
                        <tbody>
                            
                            <tr>
                                <td colspan="2">
                                    <h1>Mis Títulos</h1>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h2>Primer Título</h2>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="nombre">Nombre</label>
                                    <select
                                        value={titulos[0]?.titulo_id || ''}
                                        onChange={(e) => handleTituloChange(0, 'titulo_id', e.target.value)}
                                        required
                                    >
                                        <option value="" disabled>Seleccione un título</option>
                                        {titulos.map(titulo => (
                                            <option key={titulo.id} value={titulo.id}>
                                                {titulo.nombre}
                                            </option>
                                        ))}
                                    </select>
                                </td>
                                <td>
                                    <label>Centro</label>
                                    <input
                                        type="text"
                                        value={titulos.centro}
                                        onChange={(e) => handleTituloChange(index, 'centro', e.target.value)}
                                        required
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Situación del Curso</label>
                                    <select
                                        value={titulos.cursando}
                                        onChange={(e) => handleTituloChange(index, 'cursando', e.target.value)}
                                        required
                                    >
                                        <option value="" disabled>Seleccione</option>
                                        <option value="Cursando">Cursando</option>
                                        <option value="Finalizado">Finalizado</option>
                                        <option value="Abandonado">Abandonado</option>
                                    </select>
                                </td>
                                <td>
                                    <label>Año</label>
                                    <input
                                        type="date"
                                        value={titulos.año}
                                        onChange={(e) => handleTituloChange(index, 'año', e.target.value)}
                                        required
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h1>Mis Títulos</h1>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h2>Primer Título</h2>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="nombre">Nombre</label>
                                    <select
                                        value={titulos[0]?.titulo_id || ''}
                                        onChange={(e) => handleTituloChange(0, 'titulo_id', e.target.value)}
                                    >
                                        <option value="" disabled>Seleccione un título</option>
                                        {titulos.map(titulo => (
                                            <option key={titulo.id} value={titulo.id}>
                                                {titulo.nombre}
                                            </option>
                                        ))}
                                    </select>
                                </td>
                                <td>
                                    <label>Centro</label>
                                    <input
                                        type="text"
                                        value={titulos.centro}
                                        onChange={(e) => handleTituloChange(index, 'centro', e.target.value)}
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Situación del Curso</label>
                                    <select
                                        value={titulos.cursando}
                                        onChange={(e) => handleTituloChange(index, 'cursando', e.target.value)}
                                    >
                                        <option value="" disabled>Seleccione</option>
                                        <option value="Cursando">Cursando</option>
                                        <option value="Finalizado">Finalizado</option>
                                        <option value="Abandonado">Abandonado</option>
                                    </select>
                                </td>
                                <td>
                                    <label>Año</label>
                                    <input
                                        type="date"
                                        value={titulos.año}
                                        onChange={(e) => handleTituloChange(index, 'año', e.target.value)}
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div className="btn-info">
                        <button type="submit" className="act-info">GUARDAR TÍTULOS</button>
                    </div>
                </form>
            </div>
        
        
            <footer className="global-footer footer-perfil">
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
                    <img src="/img/fc-white.png" />
                    <img src="/img/in-white.png" />
                    <img src="/img/tw-white.png" />
                    <img src="/img/pint-white.png" />
                    </div>
                </div>
            </footer>
      </div>
    );
};

export default PerfilDemandante;

-->


<!-- 


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demandante;
use App\Models\User;
use App\Models\TituloDemandante;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DemandanteController extends Controller
{
    public function getPerfil($id)
    {
        try {
            $demandante = Demandante::with(['user', 'titulos'])->findOrFail($id);
            
            $titulos = $demandante->titulos->map(function($titulo) {
                return [
                    'id' => $titulo->pivot->id, // ID del registro en titulos_demandante
                    'titulo_id' => $titulo->id,
                    'nombre' => $titulo->nombre,
                    'centro' => $titulo->pivot->centro,
                    'año' => $titulo->pivot->año,
                    'cursando' => $titulo->pivot->cursando
                ];
            });

            return response()->json([
                'demandante' => $demandante,
                'titulos' => $titulos
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error al obtener perfil demandante", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error al cargar perfil'], 500);
        }
    }

    public function actualizarPerfil(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $demandante = Demandante::findOrFail($id);
            
            $usuario = DB::table('usuarios')
            ->where('id_rol', $id)
            ->where('rol', 'demandante')
            ->first();

            if (!$usuario) {
                throw new \Exception('Usuario no encontrado');
            }

            // Validación de datos
            $request->validate([
                'nombre' => 'required|string|max:45',
                'ape1' => 'required|string|max:45',
                'ape2' => 'required|string|max:45',
                'tel_movil' => 'required|digits:9',
                'email' => [
                    'required',
                    'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/',
                    'max:45',
                    'unique:demandante,email',
                    'unique:usuarios,email'
                ],
                'contrasena_hash' => 'required|string|min:6'
            ], [
                // Mensajes personalizados
                'nombre.max' => 'El nombre es demasiado largo',
                'ape1.max' => 'El primer apellido es demasiado largo',
                'ape2.max' => 'El segundo apellido es demasiado largo',
                'email.regex' => 'El email debe tener un formato válido.',
                'email.unique' => 'Este email ya está registrado.',
                'tel_movil.digits' => 'El número de teléfono debe tener exactamente 9 dígitos.',
                'contrasena_hash.min' => 'La contraseña debe tener una longitud mínima de 6.'
            ]);
            
            // Actualizar demandante
            $demandante->update([
                'nombre' => $request->nombre,
                'ape1' => $request->ape1,
                'ape2' => $request->ape2,
                'tel_movil' => $request->tel_movil,
                'email' => $request->email
            ]);
                        
            if (!empty($request->password)) {
                $updateData['contrasena_hash'] = Hash::make($request->password);
            }
            
            DB::table('usuarios')
                ->where('id', $usuario->id)
                ->update($updateData);
            
            DB::commit();
            
            return response()->json([
                'success' => 'Perfil actualizado correctamente',
                'demandante' => $demandante
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar perfil", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Error al actualizar perfil',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function guardarTitulo(Request $request, $idDemandante)
    {
        try {
            $request->validate([
                'titulos' => 'required|array|min:1',
                'titulos.*.id' => 'nullable|exists:titulos_demandante,id', // Para actualizar
                'titulos.*.titulo_id' => 'required|exists:titulos,id',
                'titulos.*.centro' => 'required|string|max:45',
                'titulos.*.año' => 'required|string|max:45',
                'titulos.*.cursando' => 'required|string|max:45'
            ],[
                // Mensajes personalizados
                'titulos.*.centro.max' => 'El centro es demasiado largo',
                'titulos.*.cursando.max' => 'La situación del curso es demasiado larga.'
            ]);
            
            foreach ($request->titulos as $tituloData) {
                if (isset($tituloData['id'])) {
                    // Actualizar título existente
                    TituloDemandante::where('id', $tituloData['id'])
                        ->update([
                            'centro' => $tituloData['centro'],
                            'año' => $tituloData['año'],
                            'cursando' => $tituloData['cursando']
                        ]);
                } else {
                    TituloDemandante::create([
                        'id_dem' => $request->id_dem,
                        'id_titulo' => $request->id_titulo,
                        'centro' => $request->centro,
                        'año' => $request->año,
                        'cursando' => $request->cursando
                    ]);
                }
            }
            
            return response()->json(['success' => 'Título agregado correctamente']);
            
        } catch (\Exception $e) {
            Log::error("Error al agregar título", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error al agregar título'], 500);
        }
    }
}

-->