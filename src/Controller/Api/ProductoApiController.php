<?php

namespace App\Controller\Api;

use App\DTO\ProductoDTO;
use App\Entity\Producto;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/productos')]
class ProductoApiController extends AbstractController
{
    public function __construct(
        private ProductoRepository $productoRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    #[Route('', name: 'api_productos_list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $buscar = $request->query->get('buscar');
        
        if ($buscar) {
            $paginator = $this->productoRepository->buscarPorNombre($buscar, $page);
        } else {
            $paginator = $this->productoRepository->findActivos($page);
        }

        return $this->json([
            'data' => iterator_to_array($paginator),
            'pagination' => [
                'currentPage' => $page,
                'totalItems' => $paginator->count(),
                'itemsPerPage' => \App\Repository\ProductoRepository::ITEMS_PER_PAGE,
            ],
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
        $dto = ProductoDTO::fromArray($data);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json([
                'errors' => $errorMessages,
            ], 400);
        }

        $producto = new Producto();
        $producto->setNombre($dto->nombre);
        $producto->setDescripcion($dto->descripcion);
        $producto->setPrecio((string) $dto->precio);
        $producto->setStock($dto->stock);
        $producto->setStockMinimo($dto->stockMinimo);
        $producto->setCodigo($dto->codigo);
        $producto->setCategoria($dto->categoria);
        $producto->setMarca($dto->marca);
        $producto->setPeso($dto->peso);
        $producto->setUnidadMedida($dto->unidadMedida);
        $producto->setCantidadMinima($dto->cantidadMinima);

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

        $errors = [];

        if (isset($data['nombre'])) {
            if (empty(trim($data['nombre']))) {
                $errors['nombre'] = 'El nombre no puede estar vacío';
            } elseif (strlen($data['nombre']) > 255) {
                $errors['nombre'] = 'El nombre no puede superar los 255 caracteres';
            } else {
                $producto->setNombre($data['nombre']);
            }
        }

        if (isset($data['precio'])) {
            if (!is_numeric($data['precio']) || (float) $data['precio'] <= 0) {
                $errors['precio'] = 'El precio debe ser un número positivo';
            } else {
                $producto->setPrecio((string) $data['precio']);
            }
        }

        if (isset($data['stock'])) {
            if (!is_numeric($data['stock']) || (int) $data['stock'] < 0) {
                $errors['stock'] = 'El stock debe ser cero o positivo';
            } else {
                $producto->setStock((int) $data['stock']);
            }
        }

        if (isset($data['stockMinimo']) && $data['stockMinimo'] !== null) {
            if (!is_numeric($data['stockMinimo']) || (int) $data['stockMinimo'] < 0) {
                $errors['stockMinimo'] = 'El stock mínimo debe ser cero o positivo';
            } else {
                $producto->setStockMinimo((int) $data['stockMinimo']);
            }
        }

        if (isset($data['codigo']) && strlen($data['codigo']) > 100) {
            $errors['codigo'] = 'El código no puede superar los 100 caracteres';
        } elseif (isset($data['codigo'])) {
            $producto->setCodigo($data['codigo']);
        }

        if (isset($data['categoria'])) {
            $producto->setCategoria($data['categoria']);
        }

        if (isset($data['marca'])) {
            $producto->setMarca($data['marca']);
        }

        if (isset($data['peso']) && $data['peso'] !== null) {
            if (!is_numeric($data['peso']) || (float) $data['peso'] <= 0) {
                $errors['peso'] = 'El peso debe ser un número positivo';
            } else {
                $producto->setPeso((float) $data['peso']);
            }
        }

        if (isset($data['unidadMedida'])) {
            $producto->setUnidadMedida($data['unidadMedida']);
        }

        if (isset($data['cantidadMinima']) && $data['cantidadMinima'] !== null) {
            if (!is_numeric($data['cantidadMinima']) || (int) $data['cantidadMinima'] < 0) {
                $errors['cantidadMinima'] = 'La cantidad mínima debe ser cero o positiva';
            } else {
                $producto->setCantidadMinima((int) $data['cantidadMinima']);
            }
        }

        if (isset($data['descripcion'])) {
            $producto->setDescripcion($data['descripcion']);
        }

        if (isset($data['activo'])) {
            $producto->setActivo((bool) $data['activo']);
        }

        if (!empty($errors)) {
            return $this->json([
                'errors' => $errors,
            ], 400);
        }

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
