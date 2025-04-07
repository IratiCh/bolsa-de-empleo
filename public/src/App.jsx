import { BrowserRouter as Router, Routes, Route, BrowserRouter } from "react-router-dom";
import ProtectedRoute from "./components/ProtectedRoute.jsx";
import Index from "./components/index.jsx";
import Login from "./components/login.jsx";
import RegistroDemandante from "./components/registro_demandante.jsx";
import RegistroEmpresa from "./components/registro_empresa.jsx";
import DashboardCentro from "./components/centro/dashboard_centro.jsx";
import Informes from './components/centro/informes.jsx';
import GestionTitulos from "./components/centro/gestion_titulos.jsx";
import CrearTitulo from "./components/centro/crear_titulo.jsx";
import ModificarTitulo from "./components/centro/modificar_titulo.jsx";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Index />} />
        <Route path="/login" element={<Login />} />
        <Route path="/registro_demandante" element={<RegistroDemandante />} />
        <Route path="/registro_empresa" element={<RegistroEmpresa />} />
        <Route path="/centro/dashboard_centro" element={
          <ProtectedRoute requiredRole="centro">
            <DashboardCentro />
          </ProtectedRoute>
        } />
      
      <Route path="/centro/informes" element={
          <ProtectedRoute requiredRole="centro">
            <Informes />
          </ProtectedRoute>
        } />

        <Route path="/centro/gestion_titulos" element={
          <ProtectedRoute requiredRole="centro">
            <GestionTitulos />
          </ProtectedRoute>
        } />

        <Route path="/centro/crear_titulo" element={
          <ProtectedRoute requiredRole="centro">
            <CrearTitulo />
          </ProtectedRoute>
        } />
        
        <Route path="/centro/modificar_titulo/:id" element={
          <ProtectedRoute requiredRole="centro">
            <ModificarTitulo />
          </ProtectedRoute>
        } />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
