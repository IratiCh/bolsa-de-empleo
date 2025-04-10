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
import DashboardEmpresa from "./components/empresa/dashboard_empresa.jsx";
import CrearOferta from "./components/empresa/crear_oferta.jsx";
import AsignarOferta from "./components/empresa/asignar_oferta.jsx";
import DashboardDemandante from "./components/demandante/dashboard_demandante.jsx";
import PerfilDemandante from "./components/demandante/perfil_demandante.jsx";
import DetalleOferta from './components/demandante/detalle_oferta.jsx';
import OfertasInscritas from "./components/demandante/ofertas_inscritas.jsx";


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

        <Route path="/empresa/dashboard_empresa" element={
          <ProtectedRoute requiredRole="empresa">
            <DashboardEmpresa />
          </ProtectedRoute>
        } />

        <Route path="/empresa/crear_oferta" element={
          <ProtectedRoute requiredRole="empresa">
            <CrearOferta />
          </ProtectedRoute>
        } />

        <Route path="/empresa/asignar_oferta/:id" element={
          <ProtectedRoute requiredRole="empresa">
            <AsignarOferta />
          </ProtectedRoute>
        } />

        <Route path="/demandante/dashboard_demandante" element={
          <ProtectedRoute requiredRole="demandante">
            <DashboardDemandante />
          </ProtectedRoute>
        } />

        <Route path="/demandante/perfil_demandante" element={
          <ProtectedRoute requiredRole="demandante">
            <PerfilDemandante />
          </ProtectedRoute>
        } />

        
        <Route path="/demandante/oferta/:id" element={
          <ProtectedRoute requiredRole="demandante">
            <DetalleOferta />
          </ProtectedRoute>
        } />

          <Route path="/demandante/ofertas_inscritas" element={
            <ProtectedRoute requiredRole="demandante">
              <OfertasInscritas />
            </ProtectedRoute>
        } />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
