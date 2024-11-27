<?php

declare(strict_types=1);

namespace App\Core;

use Twig\Environment;

class Router
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig; // Injection de Twig pour le rendu des vues
    }

    public function route(): void
    {
        // Récupère l'URI demandée (sans domaine ni paramètres de requête)
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // Découpe l'URI en segments
        $parts = explode('/', $uri); // Exemple : ['films', 'create']
        $route = $parts[0] ?? null;  // Exemple : 'films'
        $action = $parts[1] ?? null; // Exemple : 'create'

        // Routes disponibles et leurs contrôleurs
        $routes = [
            'films' => 'FilmController',
            'contact' => 'ContactController',
        ];

        if (array_key_exists($route, $routes)) {
            // Crée dynamiquement le nom du contrôleur
            $controllerName = 'App\\Controller\\' . $routes[$route];

            if (!class_exists($controllerName)) {
                $this->render404("Controller '$controllerName' not found");
                return;
            }

            $controller = new $controllerName($this->twig); // Injecte Twig dans le contrôleur

            // Vérifie si la méthode existe dans le contrôleur
            if (method_exists($controller, $action)) {
                $queryParams = $_GET; // Récupère les paramètres de la requête
                $controller->$action($queryParams); // Appelle la méthode du contrôleur
            } else {
                $this->render404("Action '$action' not found in $controllerName");
            }
        } else {
            // Page non trouvée
            $this->render404("Route '$route' not found");
        }
    }

    private function render404(string $message): void
    {
        // Affiche une page 404 avec Twig
        echo $this->twig->render('404.html.twig', [
            'message' => $message,
            'uri' => $_SERVER['REQUEST_URI'],
        ]);
    }
}
