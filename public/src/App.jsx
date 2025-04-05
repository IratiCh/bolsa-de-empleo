import { BrowserRouter as Router, Routes, Route, BrowserRouter } from "react-router-dom";
import Index from "./components/index.jsx";
import Login from "./components/login.jsx";
/*import RegistroDemandante from "./components/registro_demandante.jsx";
import RegistroEmpresa from "./components/registro_empresa.jsx";
import AsignarOferta from "./components/empresa/asignar_oferta.jsx";*/

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Index />} />
        <Route path="/login" element={<Login />} />
      </Routes>
    </BrowserRouter>
  );
}

/*

        <Route path="/registro_demandante" element={<RegistroDemandante />} />
        <Route path="/registro_empresa" element={<RegistroEmpresa />} />
        <Route path="/empresa/asignar_oferta" element={<AsignarOferta />} />
*/

export default App;
