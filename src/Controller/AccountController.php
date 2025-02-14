<?php

namespace App\Controller;

use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    #[Route('/account', name: 'app_account')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Calculer l'espace de stockage
        $usedSpace = $this->storageService->calculateUsedSpace($user);
        $remainingSpace = $this->storageService->calculateRemainingSpace($user);

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'usedSpace' => $usedSpace,
            'remainingSpace' => $remainingSpace,
        ]);
    }
}
