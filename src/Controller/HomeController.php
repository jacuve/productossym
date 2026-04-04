<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProductoRepository;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductoRepository $productoRepository): Response
    {
        $productos = $productoRepository->findActivos();

        return $this->render('home.html.twig', [
            'productos' => $productos,
        ]);
    }
}
