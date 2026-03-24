import { BrowserRouter, Routes, Route, Link } from 'react-router-dom';
import ListaProductos from './components/ListaProductos';
import FormularioProducto from './components/FormularioProducto';
import './App.css';

function App() {
  return (
    <BrowserRouter>
      <nav className="bg-gray-800 text-white p-4">
        <div className="container mx-auto flex gap-4">
          <Link to="/productos" className="hover:text-gray-300">
            Productos
          </Link>
        </div>
      </nav>

      <Routes>
        <Route path="/productos" element={<ListaProductos />} />
        <Route path="/productos/nuevo" element={<FormularioProducto />} />
        <Route path="/productos/:id/editar" element={<FormularioProducto esEditar={true} />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
