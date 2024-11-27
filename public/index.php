<?php

declare(strict_types=1); // Activer le mode strict

require_once __DIR__ . '/../vendor/autoload.php'; // Charger les dépendances via Composer

use App\Core\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../templates'); // Répertoire des templates Twig
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/../cache', 
    'debug' => true,                
]);

// Ajouter une extension pour déboguer avec Twig (optionnelle, utile pour dump())
$twig->addExtension(new \Twig\Extension\DebugExtension());

// INITIALISATION DU ROUTEUR
$router = new Router($twig); // Injectez l'instance Twig dans le routeur
$router->route(); // Exécute le routage des URL
