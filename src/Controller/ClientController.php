<?php

// src/Controller/ClientController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Vous n'avez plus besoin de cette ligne si vous utilisez des attributs
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    #[Route('/client/prenom/{prenom}', name: 'client_prenom', requirements: ['prenom' => '^[a-zA-Z]+(-[a-zA-Z]+)*$'])]
    public function info(string $prenom): Response
    {
        // Liste des clients pour l'exemple
        $clients = [
            'rene' => ['Rene Dupres', 'Rene Dupond'],
            'jacqueline' => ['Jacqueline Dubois'],
            'marie' => ['Marie Lefevre', 'Marie-Claude Lefevre'],
            'zouhir' => ['Zouhir KRIM'],
            'maxime' => ['LEBRUN Maxime'],
            'mathieu' => ['Mathieu BROGLY']
        ];

        // Vérifie si le prénom existe dans la liste des clients
        $result = $clients[strtolower($prenom)] ?? [];

        // Si aucun client trouvé, renvoyer un message alternatif
        if (empty($result)) {
            return new Response(
                'Aucune personne dans la base de donnée avec le prénom "' . htmlspecialchars($prenom) . '".'
            );
        }

        // Retourne la réponse avec les clients trouvés
        return new Response(
            'Clients avec le prénom "' . htmlspecialchars($prenom) . '" : ' . implode(', ', $result)
        );
    }
}
