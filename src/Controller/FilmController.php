<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\TemplateRenderer;
use App\Entity\Film;
use App\Repository\FilmRepository;

class FilmController
{
    private TemplateRenderer $renderer;

    public function __construct()
    {
        $this->renderer = new TemplateRenderer();
    }

    public function list(array $queryParams)
    {
        $filmRepository = new FilmRepository();
        $films = $filmRepository->findAll();

        /* $filmEntities = [];
        foreach ($films as $film) {
            $filmEntity = new Film();
            $filmEntity->setId($film['id']);
            $filmEntity->setTitle($film['title']);
            $filmEntity->setYear($film['year']);
            $filmEntity->setType($film['type']);
            $filmEntity->setSynopsis($film['synopsis']);
            $filmEntity->setDirector($film['director']);
            $filmEntity->setCreatedAt(new \DateTime($film['created_at']));
            $filmEntity->setUpdatedAt(new \DateTime($film['updated_at']));

            $filmEntities[] = $filmEntity;
        } */

        //dd($films);

        echo $this->renderer->render('film/list.html.twig', [
            'films' => $films,
        ]);

        // header('Content-Type: application/json');
        // echo json_encode($films);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $film = $this->entityMapper->mapToEntity($_POST, Film::class);
            $film->setCreatedAt(new \DateTime());

            $filmRepository = new FilmRepository();
            $filmRepository->createFilm($film);

            header('Location: /film/list');
            exit();
        }

        echo $this->renderer->render('film/create.html.twig');
    }

    
    

    public function read(array $queryParams)
    {
        $filmRepository = new FilmRepository();
        $film = $filmRepository->find((int) $queryParams['id']);

        if ($film) {
            echo $this->renderer->render('film/read.html.twig', [
                'film' => $film,
            ]);
        } else {
            echo "Film not found";
        }
    }

    public function update(array $queryParams)
    {
        $filmRepository = new FilmRepository();
        $film = $filmRepository->find((int) $queryParams['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updatedFilm = $this->entityMapper->mapToEntity($_POST, Film::class);
            $updatedFilm->setId($film->getId());
            $updatedFilm->setUpdatedAt(new \DateTime());

            $filmRepository->updateFilm($updatedFilm);

            header('Location: /film/list');
            exit();
        }

        echo $this->renderer->render('film/update.html.twig', [
            'film' => $film,
        ]);
    }

    public function delete(array $queryParams)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filmRepository = new FilmRepository();
            $film = $filmRepository->find((int) $queryParams['id']);

            if ($film) {
                $filmRepository->deleteFilm($film);
                header('Location: /film/list');
                exit();
            } else {
                echo "Film not found";
            }
        } else {
            header('Location: /film/list');
            exit();
        }
    }
}
