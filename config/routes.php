<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

$routes->add('client_prenom', new Route('/client/prenom/{prenom}', [
    '_controller' => 'App\Controller\ClientController::info',
]));

return $routes;
