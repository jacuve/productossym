<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/productos')]
class ProductoController extends AbstractController
{
    public function __construct(
        private ProductoRepository $productoRepository,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {}

    #[Route('/', name: 'producto_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $buscar = $request->query->get('buscar');

        if ($buscar) {
            $paginator = $this->productoRepository->buscarPorNombre($buscar, $page);
        } else {
            $paginator = $this->productoRepository->findActivos($page);
        }

        $stockBajo = $this->productoRepository->findStockBajo();

        return $this->render('producto/index.html.twig', [
            'productos' => $paginator,
            'stock_bajo' => $stockBajo,
            'buscar' => $buscar,
            'currentPage' => $page,
            'totalPages' => ceil($paginator->count() / ProductoRepository::ITEMS_PER_PAGE),
        ]);
    }

    #[Route('/stock-bajo', name: 'producto_stock_bajo', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function stockBajo(): Response
    {
        $productos = $this->productoRepository->findStockBajo();

        return $this->render('producto/stock_bajo.html.twig', [
            'productos' => $productos,
        ]);
    }

    #[Route('/nuevo', name: 'producto_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        $producto = new Producto();
        $errors = [];

        if ($request->isMethod('POST')) {
            $producto->setNombre($request->request->get('nombre'));
            $producto->setDescripcion($request->request->get('descripcion'));
            $producto->setPrecio($request->request->get('precio'));
            $producto->setStock((int) $request->request->get('stock'));
            $producto->setStockMinimo($request->request->get('stock_minimo') ? (int) $request->request->get('stock_minimo') : null);
            $producto->setCodigo($request->request->get('codigo'));
            $producto->setCategoria($request->request->get('categoria'));
            $producto->setMarca($request->request->get('marca'));
            $producto->setPeso($request->request->get('peso') ? (float) $request->request->get('peso') : null);
            $producto->setUnidadMedida($request->request->get('unidad_medida'));
            $producto->setCantidadMinima($request->request->get('cantidad_minima') ? (int) $request->request->get('cantidad_minima') : null);

            $errors = $this->validator->validate($producto);

            if (count($errors) === 0) {
                $this->entityManager->persist($producto);
                $this->entityManager->flush();

                $this->addFlash('success', 'Producto creado correctamente');

                return $this->redirectToRoute('producto_index');
            }
        }

        return $this->render('producto/new.html.twig', [
            'producto' => $producto,
            'errors' => $errors,
        ]);
    }

    #[Route('/{id}', name: 'producto_show', methods: ['GET'])]
    public function show(Producto $producto): Response
    {
        return $this->render('producto/show.html.twig', [
            'producto' => $producto,
        ]);
    }

    #[Route('/{id}/editar', name: 'producto_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Producto $producto): Response
    {
        $errors = [];

        if ($request->isMethod('POST')) {
            $producto->setNombre($request->request->get('nombre'));
            $producto->setDescripcion($request->request->get('descripcion'));
            $producto->setPrecio($request->request->get('precio'));
            $producto->setStock((int) $request->request->get('stock'));
            $producto->setStockMinimo($request->request->get('stock_minimo') ? (int) $request->request->get('stock_minimo') : null);
            $producto->setCodigo($request->request->get('codigo'));
            $producto->setCategoria($request->request->get('categoria'));
            $producto->setMarca($request->request->get('marca'));
            $producto->setPeso($request->request->get('peso') ? (float) $request->request->get('peso') : null);
            $producto->setUnidadMedida($request->request->get('unidad_medida'));
            $producto->setCantidadMinima($request->request->get('cantidad_minima') ? (int) $request->request->get('cantidad_minima') : null);
            $producto->setActivo($request->request->has('activo'));

            $errors = $this->validator->validate($producto);

            if (count($errors) === 0) {
                $this->entityManager->flush();

                $this->addFlash('success', 'Producto actualizado correctamente');

                return $this->redirectToRoute('producto_index');
            }
        }

        return $this->render('producto/edit.html.twig', [
            'producto' => $producto,
            'errors' => $errors,
        ]);
    }

    #[Route('/{id}/eliminar', name: 'producto_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Producto $producto): Response
    {
        if ($this->isCsrfTokenValid('delete' . $producto->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($producto);
            $this->entityManager->flush();

            $this->addFlash('success', 'Producto eliminado correctamente');
        }

        return $this->redirectToRoute('producto_index');
    }
}
