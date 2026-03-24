import { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { api } from '../api/productos';

function FormularioProducto({ esEditar = false }) {
  const navigate = useNavigate();
  const { id } = useParams();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [producto, setProducto] = useState({
    nombre: '',
    descripcion: '',
    precio: '',
    stock: 0,
    stockMinimo: '',
    codigo: '',
    categoria: '',
    marca: '',
    peso: '',
    unidadMedida: '',
    cantidadMinima: '',
  });

  useEffect(() => {
    if (esEditar && id) {
      cargarProducto();
    }
  }, [esEditar, id]);

  const cargarProducto = async () => {
    try {
      setLoading(true);
      const data = await api.getProducto(id);
      setProducto({
        nombre: data.nombre || '',
        descripcion: data.descripcion || '',
        precio: data.precio || '',
        stock: data.stock || 0,
        stockMinimo: data.stockMinimo || '',
        codigo: data.codigo || '',
        categoria: data.categoria || '',
        marca: data.marca || '',
        peso: data.peso || '',
        unidadMedida: data.unidadMedida || '',
        cantidadMinima: data.cantidadMinima || '',
      });
    } catch (err) {
      setError('Error al cargar el producto');
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setProducto(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      const productoData = {
        ...producto,
        stock: parseInt(producto.stock) || 0,
        stockMinimo: producto.stockMinimo ? parseInt(producto.stockMinimo) : null,
        peso: producto.peso ? parseFloat(producto.peso) : null,
        cantidadMinima: producto.cantidadMinima ? parseInt(producto.cantidadMinima) : null,
      };

      if (esEditar) {
        await api.updateProducto(id, productoData);
      } else {
        await api.createProducto(productoData);
      }
      navigate('/productos');
    } catch (err) {
      setError('Error al guardar el producto');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  if (loading && esEditar && !producto.nombre) return <div className="p-4">Cargando...</div>;

  return (
    <div className="p-4 max-w-2xl mx-auto">
      <h1 className="text-2xl font-bold mb-4">
        {esEditar ? 'Editar Producto' : 'Nuevo Producto'}
      </h1>

      {error && (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label className="block mb-1">Nombre *</label>
          <input
            type="text"
            name="nombre"
            value={producto.nombre}
            onChange={handleChange}
            required
            className="w-full border p-2 rounded"
          />
        </div>

        <div>
          <label className="block mb-1">Descripción</label>
          <textarea
            name="descripcion"
            value={producto.descripcion}
            onChange={handleChange}
            rows="3"
            className="w-full border p-2 rounded"
          />
        </div>

        <div className="grid grid-cols-2 gap-4">
          <div>
            <label className="block mb-1">Precio *</label>
            <input
              type="number"
              name="precio"
              value={producto.precio}
              onChange={handleChange}
              required
              step="0.01"
              className="w-full border p-2 rounded"
            />
          </div>

          <div>
            <label className="block mb-1">Código</label>
            <input
              type="text"
              name="codigo"
              value={producto.codigo}
              onChange={handleChange}
              className="w-full border p-2 rounded"
            />
          </div>
        </div>

        <div className="grid grid-cols-3 gap-4">
          <div>
            <label className="block mb-1">Stock</label>
            <input
              type="number"
              name="stock"
              value={producto.stock}
              onChange={handleChange}
              min="0"
              className="w-full border p-2 rounded"
            />
          </div>

          <div>
            <label className="block mb-1">Stock Mínimo</label>
            <input
              type="number"
              name="stockMinimo"
              value={producto.stockMinimo}
              onChange={handleChange}
              min="0"
              className="w-full border p-2 rounded"
            />
          </div>

          <div>
            <label className="block mb-1">Cantidad Mín.</label>
            <input
              type="number"
              name="cantidadMinima"
              value={producto.cantidadMinima}
              onChange={handleChange}
              min="0"
              className="w-full border p-2 rounded"
            />
          </div>
        </div>

        <div className="grid grid-cols-2 gap-4">
          <div>
            <label className="block mb-1">Categoría</label>
            <input
              type="text"
              name="categoria"
              value={producto.categoria}
              onChange={handleChange}
              className="w-full border p-2 rounded"
            />
          </div>

          <div>
            <label className="block mb-1">Marca</label>
            <input
              type="text"
              name="marca"
              value={producto.marca}
              onChange={handleChange}
              className="w-full border p-2 rounded"
            />
          </div>
        </div>

        <div className="grid grid-cols-2 gap-4">
          <div>
            <label className="block mb-1">Peso</label>
            <input
              type="number"
              name="peso"
              value={producto.peso}
              onChange={handleChange}
              step="0.01"
              className="w-full border p-2 rounded"
            />
          </div>

          <div>
            <label className="block mb-1">Unidad de Medida</label>
            <input
              type="text"
              name="unidadMedida"
              value={producto.unidadMedida}
              onChange={handleChange}
              placeholder="kg, g, u."
              className="w-full border p-2 rounded"
            />
          </div>
        </div>

        <div className="flex gap-4 pt-4">
          <button
            type="submit"
            disabled={loading}
            className="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 disabled:opacity-50"
          >
            {loading ? 'Guardando...' : 'Guardar'}
          </button>
          <button
            type="button"
            onClick={() => navigate('/productos')}
            className="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400"
          >
            Cancelar
          </button>
        </div>
      </form>
    </div>
  );
}

export default FormularioProducto;
