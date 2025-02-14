<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;  

class StockageController extends AbstractController
{
    private LoggerInterface $logger;

   
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    
    public function home(): Response
    {
        return $this->render('img/home.html.twig');
    }


    #[Route('/img/download/{imageName}', name: 'img_download')]
    public function download(string $imageName): Response
    {
       
        if (pathinfo($imageName, PATHINFO_EXTENSION) !== 'jpg') {
            $imageName .= '.jpg'; // Ajoutez l'extension .jpg si elle n'est pas présente
        }

        
        $imagePath = $this->getParameter('kernel.project_dir') . '/images/' . $imageName;

        // Log du chemin de l'image pour débogage
        $this->logger->info("Image path: " . $imagePath);  // Utilisation du logger injecté

        // Vérifiez si le fichier existe
        if (!file_exists($imagePath)) {
            // Affiche le message d'erreur si l'image n'existe pas
            return new Response('Image inexistante, recommence ! Et ajoute image dans le dossier images !', Response::HTTP_NOT_FOUND);
        }

        // Déterminer le type MIME en fonction de l'extension
        $mimeType = 'image/jpeg'; // Par défaut
        if (pathinfo($imageName, PATHINFO_EXTENSION) === 'png') {
            $mimeType = 'image/png';
        } elseif (pathinfo($imageName, PATHINFO_EXTENSION) === 'gif') {
            $mimeType = 'image/gif';
        }

        // Crée une réponse pour télécharger le fichier
        $response = $this->file($imagePath);

        // Forcer le téléchargement avec un en-tête de type Content-Disposition pour un fichier en pièce jointe
        $response->headers->set('Content-Disposition', ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        $response->headers->set('Content-Type', $mimeType);  // Forcer le type MIME

        return $response;
    }
}
