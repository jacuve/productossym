<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $servicios = ['Diseño', 'Programación', 'Marketing', 'SEO'];

        return $this->render('home.html.twig', [
            'titulo' => 'Nuestros Servicios',
            'items' => $servicios,
        ]);
    }
}
