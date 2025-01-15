<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\TemplateRenderer;
use App\Entity\Film;
use App\Repository\FilmRepository;
use App\Service\EntityMapper;

class FilmController
{
    private TemplateRenderer $renderer;
    private EntityMapper $entityMapper;

    public function __construct()
    {
        $this->renderer = new TemplateRenderer();
        $this->entityMapper = new EntityMapper();
        session_start();
    }

    public function list(array $queryParams)
    {
        $filmRepository = new FilmRepository();
        $films = $filmRepository->findAll();

        echo $this->renderer->render('film/list.html.twig', [
            'films' => $films,
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $film = $this->entityMapper->mapToEntity($_POST, Film::class);
            $film->setCreatedAt(new \DateTime());

            $filmRepository = new FilmRepository();
            $filmRepository->createFilm($film);

            $_SESSION['flash_message'] = 'Film créé avec succès.';
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

            $_SESSION['flash_message'] = 'Film modifié avec succès.';
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
                $_SESSION['flash_message'] = 'Film supprimé avec succès.';
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