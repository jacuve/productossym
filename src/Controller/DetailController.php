<?php
// src/Controller/DetailController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProductoRepository;
use Symfony\Component\Routing\Attribute\Route;

class DetailController extends AbstractController
{
    #[Route('/detalles/{id}', name: 'app_detail')]
    public function index(int $id, ProductoRepository $productoRepository): Response
    {
        $producto = $productoRepository->find($id);

        if (!$producto) {
            throw $this->createNotFoundException('Producto no encontrado');
        }

        return $this->render('detail.html.twig', [
            'producto' => $producto
        ]);
    }
}
