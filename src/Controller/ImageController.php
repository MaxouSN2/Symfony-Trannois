<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    public function home(): Response
    {
        return $this->render('img/home.html.twig');
    }

    #[Route('/img/data/{imageName}', name: 'img_data')]
    public function affiche(string $imageName): Response
    {
        // Chemin vers le répertoire des images
        $imagePath = $this->getParameter('kernel.project_dir') . '/images/' . $imageName . '.jpg';

        // Vérifiez si le fichier existe
        if (!file_exists($imagePath)) {
            return new Response('Image inexistante, recommence ! Et ajoute image dans le dossier images !', Response::HTTP_NOT_FOUND);
        }

        // Lit les données binaires de l'image
        $imageData = file_get_contents($imagePath);

        // Crée une réponse avec les données de l'image et l'en-tête "Content-Type"
        $response = new Response($imageData);
        $response->headers->set('Content-Type', 'image/jpeg');

        return $response;
    }

    // Méthode pour générer le menu des images
    public function menu(): Response
    {
        // Chemin vers le répertoire des images
        $imageDir = $this->getParameter('kernel.project_dir') . '/images/';
        $images = [];

        // Vérifiez si le répertoire existe
        if (is_dir($imageDir)) {
            $files = scandir($imageDir);

            // Filtrez uniquement les fichiers images
            foreach ($files as $file) {
                if (!is_dir($imageDir . $file) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    $images[] = pathinfo($file, PATHINFO_FILENAME);
                }
            }
        }

        // Retourne le rendu du menu avec la liste des images
        return $this->render('img/menu.html.twig', [
            'images' => $images,
        ]);
    }
}