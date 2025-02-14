<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function redirectToStockage(): RedirectResponse
    {
        return $this->redirectToRoute('route_image');  // 'route_image' est le nom de la route pour /stockage
    }
}
