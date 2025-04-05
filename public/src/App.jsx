import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Index from "./components/index.jsx";
import Login from "./components/login.jsx";

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Index />} />
        <Route path="/login" element={<Login />} />
      </Routes>
    </Router>
  );
}

export default App;
