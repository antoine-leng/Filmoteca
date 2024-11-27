<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FilmRepository;
use Twig\Environment;

class FilmController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig; // Injection de Twig pour afficher des templates
    }

    public function list(array $queryParams): void
    {
        $filmRepository = new FilmRepository();
        $films = $filmRepository->findAll();

        // Utilisation de Twig pour afficher la liste des films
        echo $this->twig->render('films.html.twig', [
            'films' => $films,
        ]);
    }

    public function create(): void
    {
        // Affiche un formulaire pour créer un film
        echo $this->twig->render('film_create.html.twig');
    }

    public function read(array $queryParams): void
    {
        if (!isset($queryParams['id'])) {
            echo $this->twig->render('404.html.twig', [
                'message' => 'Film ID is missing.',
            ]);
            return;
        }

        $filmRepository = new FilmRepository();
        $film = $filmRepository->find((int) $queryParams['id']);

        if (!$film) {
            echo $this->twig->render('404.html.twig', [
                'message' => 'Film not found.',
            ]);
            return;
        }

        // Utilisation de Twig pour afficher les détails d’un film
        echo $this->twig->render('film_details.html.twig', [
            'film' => $film,
        ]);
    }

    public function update(): void
    {
        // Affiche un formulaire de mise à jour
        echo $this->twig->render('film_update.html.twig');
    }

    public function delete(): void
    {
        // Affiche une page ou confirmation pour supprimer un film
        echo $this->twig->render('film_delete.html.twig');
    }
}
