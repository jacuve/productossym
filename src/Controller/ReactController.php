<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReactController extends AbstractController
{
    #[Route('/react/{path}', name: 'react_app', requirements: ['path' => '.*'])]
    public function index(): Response
    {
        $indexFile = $this->getParameter('kernel.project_dir') . '/frontend/dist/index.html';

        if (!file_exists($indexFile)) {
            return new Response('React app not built. Run: cd frontend && npm run build', 404);
        }

        return new BinaryFileResponse($indexFile);
    }
}
