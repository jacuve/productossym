<?php

namespace App\Controller\Api;

use App\Entity\Producto;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/productos')]
class ProductoApiController extends AbstractController
{
    public function __construct(
        private ProductoRepository $productoRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer
    ) {}

    #[Route('', name: 'api_productos_list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $buscar = $request->query->get('buscar');
        
        if ($buscar) {
            $productos = $this->productoRepository->buscarPorNombre($buscar);
        } else {
            $productos = $this->productoRepository->findActivos();
        }

        return $this->json([
            'data' => $productos,
        ]);
    }

    #[Route('/stock-bajo', name: 'api_productos_stock_bajo', methods: ['GET'])]
    public function stockBajo(): JsonResponse
    {
        $productos = $this->productoRepository->findStockBajo();

        return $this->json([
            'data' => $productos,
        ]);
    }

    #[Route('/{id}', name: 'api_productos_show', methods: ['GET'])]
    public function show(Producto $producto): JsonResponse
    {
        return $this->json([
            'data' => $producto,
        ]);
    }

    #[Route('', name: 'api_productos_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $producto = new Producto();
        $producto->setNombre($data['nombre'] ?? null);
        $producto->setDescripcion($data['descripcion'] ?? null);
        $producto->setPrecio($data['precio'] ?? null);
        $producto->setStock((int) ($data['stock'] ?? 0));
        $producto->setStockMinimo(isset($data['stockMinimo']) ? (int) $data['stockMinimo'] : null);
        $producto->setCodigo($data['codigo'] ?? null);
        $producto->setCategoria($data['categoria'] ?? null);
        $producto->setMarca($data['marca'] ?? null);
        $producto->setPeso(isset($data['peso']) ? (float) $data['peso'] : null);
        $producto->setUnidadMedida($data['unidadMedida'] ?? null);
        $producto->setCantidadMinima(isset($data['cantidadMinima']) ? (int) $data['cantidadMinima'] : null);

        $this->entityManager->persist($producto);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Producto creado correctamente',
            'data' => $producto,
        ], 201);
    }

    #[Route('/{id}', name: 'api_productos_update', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, Producto $producto): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $producto->setNombre($data['nombre'] ?? $producto->getNombre());
        $producto->setDescripcion($data['descripcion'] ?? $producto->getDescripcion());
        $producto->setPrecio($data['precio'] ?? $producto->getPrecio());
        $producto->setStock(isset($data['stock']) ? (int) $data['stock'] : $producto->getStock());
        $producto->setStockMinimo(isset($data['stockMinimo']) ? (int) $data['stockMinimo'] : $producto->getStockMinimo());
        $producto->setCodigo($data['codigo'] ?? $producto->getCodigo());
        $producto->setCategoria($data['categoria'] ?? $producto->getCategoria());
        $producto->setMarca($data['marca'] ?? $producto->getMarca());
        $producto->setPeso(isset($data['peso']) ? (float) $data['peso'] : $producto->getPeso());
        $producto->setUnidadMedida($data['unidadMedida'] ?? $producto->getUnidadMedida());
        $producto->setCantidadMinima(isset($data['cantidadMinima']) ? (int) $data['cantidadMinima'] : $producto->getCantidadMinima());
        $producto->setActivo($data['activo'] ?? $producto->isActivo());

        $this->entityManager->flush();

        return $this->json([
            'message' => 'Producto actualizado correctamente',
            'data' => $producto,
        ]);
    }

    #[Route('/{id}', name: 'api_productos_delete', methods: ['DELETE'])]
    public function delete(Producto $producto): JsonResponse
    {
        $this->entityManager->remove($producto);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Producto eliminado correctamente',
        ]);
    }
}
