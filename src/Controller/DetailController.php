<?php
// src/Controller/DetailController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DetailController extends AbstractController
{
    // El parámetro {id} en la URL se mapea automáticamente al argumento $id
    #[Route('/detalles/{id}', name: 'app_detail')]
    public function index(int $id): Response
    {
        // Simulamos un objeto de base de datos
        $producto = [
            'id' => $id,
            'nombre' => 'Laptop Gaming',
            'precio' => 1200.50,
            'stock' => true
        ];

        return $this->render('detail.html.twig', [
            'producto' => $producto
        ]);
    }
}
