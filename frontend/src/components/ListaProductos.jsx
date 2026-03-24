import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { api } from '../api/productos';

function ListaProductos() {
  const [productos, setProductos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [buscar, setBuscar] = useState('');

  useEffect(() => {
    cargarProductos();
  }, []);

  const cargarProductos = async () => {
    try {
      setLoading(true);
      const data = await api.getProductos();
      setProductos(data);
      setError(null);
    } catch (err) {
      setError('Error al cargar productos');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const eliminarProducto = async (id) => {
    if (window.confirm('¿Estás seguro de eliminar este producto?')) {
      try {
        await api.deleteProducto(id);
        cargarProductos();
      } catch (err) {
        alert('Error al eliminar producto');
      }
    }
  };

  const productosFiltrados = productos.filter(p =>
    p.nombre?.toLowerCase().includes(buscar.toLowerCase()) ||
    p.codigo?.toLowerCase().includes(buscar.toLowerCase())
  );

  if (loading) return <div className="p-4">Cargando...</div>;
  if (error) return <div className="p-4 text-red-500">{error}</div>;

  return (
    <div className="p-4">
      <div className="flex justify-between items-center mb-4">
        <h1 className="text-2xl font-bold">Productos</h1>
        <Link
          to="/productos/nuevo"
          className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
          Nuevo Producto
        </Link>
      </div>

      <div className="mb-4">
        <input
          type="text"
          placeholder="Buscar productos..."
          value={buscar}
          onChange={(e) => setBuscar(e.target.value)}
          className="border p-2 rounded w-full max-w-md"
        />
      </div>

      <table className="min-w-full border-collapse border">
        <thead>
          <tr className="bg-gray-100">
            <th className="border p-2 text-left">ID</th>
            <th className="border p-2 text-left">Nombre</th>
            <th className="border p-2 text-left">Código</th>
            <th className="border p-2 text-right">Precio</th>
            <th className="border p-2 text-right">Stock</th>
            <th className="border p-2 text-right">Stock Mín.</th>
            <th className="border p-2 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          {productosFiltrados.length === 0 ? (
            <tr>
              <td colSpan="7" className="border p-4 text-center">
                No hay productos
              </td>
            </tr>
          ) : (
            productosFiltrados.map((producto) => (
              <tr key={producto.id} className="hover:bg-gray-50">
                <td className="border p-2">{producto.id}</td>
                <td className="border p-2">{producto.nombre}</td>
                <td className="border p-2">{producto.codigo || '-'}</td>
                <td className="border p-2 text-right">${producto.precio}</td>
                <td className="border p-2 text-right">
                  <span className={producto.stockMinimo && producto.stock <= producto.stockMinimo ? 'text-red-500 font-bold' : ''}>
                    {producto.stock}
                  </span>
                </td>
                <td className="border p-2 text-right">{producto.stockMinimo || '-'}</td>
                <td className="border p-2 text-center">
                  <Link
                    to={`/productos/${producto.id}/editar`}
                    className="text-blue-500 hover:underline mr-2"
                  >
                    Editar
                  </Link>
                  <button
                    onClick={() => eliminarProducto(producto.id)}
                    className="text-red-500 hover:underline"
                  >
                    Eliminar
                  </button>
                </td>
              </tr>
            ))
          )}
        </tbody>
      </table>
    </div>
  );
}

export default ListaProductos;
