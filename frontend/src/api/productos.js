import axios from 'axios';

const API_URL = 'http://localhost:8000/api/productos';

export const api = {
  getProductos: async () => {
    const response = await axios.get(API_URL);
    return response.data.data;
  },

  getProducto: async (id) => {
    const response = await axios.get(`${API_URL}/${id}`);
    return response.data.data;
  },

  createProducto: async (producto) => {
    const response = await axios.post(API_URL, producto);
    return response.data;
  },

  updateProducto: async (id, producto) => {
    const response = await axios.patch(`${API_URL}/${id}`, producto);
    return response.data;
  },

  deleteProducto: async (id) => {
    const response = await axios.delete(`${API_URL}/${id}`);
    return response.data;
  },

  getStockBajo: async () => {
    const response = await axios.get(`${API_URL}/stock-bajo`);
    return response.data.data;
  },
};
