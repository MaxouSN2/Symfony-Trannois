<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;  

class ImageController extends AbstractController
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

   
    #[Route('/img/data/{imageName}', name: 'img_data')]
    public function affiche(string $imageName): Response
    {
        
        $imagePath = $this->getParameter('kernel.project_dir') . '/images/' . $imageName . '.jpg';

        
        if (!file_exists($imagePath)) {
            return new Response('Image inexistante, recommence ! Et ajoute image dans le dossier images !', Response::HTTP_NOT_FOUND);
        }

       
        $imageData = file_get_contents($imagePath);

        
        $response = new Response($imageData);
        $response->headers->set('Content-Type', 'image/jpeg');

        return $response;
    }

    
    public function menu(): Response
    {
        
        $imageDir = $this->getParameter('kernel.project_dir') . '/images/';
        $images = [];

      
        if (is_dir($imageDir)) {
            $files = scandir($imageDir);

      
            foreach ($files as $file) {
                if (!is_dir($imageDir . $file) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    $images[] = pathinfo($file, PATHINFO_FILENAME);
                }
            }
        }

       
        return $this->render('img/menu.html.twig', [
            'images' => $images,
        ]);
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
