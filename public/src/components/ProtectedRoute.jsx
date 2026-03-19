import { Navigate } from 'react-router-dom';

const ProtectedRoute = ({ children, requiredRole }) => {
    const usuario = JSON.parse(localStorage.getItem('usuario'));

    if (!usuario || (requiredRole && usuario.rol !== requiredRole)) {
        return <Navigate to="/" replace />;
    }

    return children;
};

export default ProtectedRoute;
