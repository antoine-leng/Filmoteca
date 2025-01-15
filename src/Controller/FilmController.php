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
    private FilmRepository $filmRepository;

    public function __construct()
    {
        $this->renderer = new TemplateRenderer();
        $this->entityMapper = new EntityMapper();
        $this->filmRepository = new FilmRepository();
        session_start();
    }

    public function list(array $queryParams): void
    {
        $films = $this->filmRepository->findAll();

        echo $this->renderer->render('film/list.html.twig', [
            'films' => $films,
            'session' => $_SESSION,
        ]);
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $film = $this->entityMapper->mapToEntity($_POST, Film::class);
            $film->setCreatedAt(new \DateTime());

            $this->filmRepository->createFilm($film);

            header('Location: /film/list');
            exit();
        }

        echo $this->renderer->render('film/create.html.twig', [
            'session' => $_SESSION,
        ]);
    }

    public function read(array $queryParams): void
    {
        $film = $this->filmRepository->find((int) $queryParams['id']);

        if ($film) {
            echo $this->renderer->render('film/read.html.twig', [
                'film' => $film,
            ]);
        } else {
            echo "Film not found";
        }
    }

    public function update(array $queryParams): void
    {
        $film = $this->filmRepository->find((int) $queryParams['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updatedFilm = $this->entityMapper->mapToEntity($_POST, Film::class);
            $updatedFilm->setId($film->getId());
            $updatedFilm->setUpdatedAt(new \DateTime());

            $this->filmRepository->updateFilm($updatedFilm);

            header('Location: /film/list');
            exit();
        }

        echo $this->renderer->render('film/update.html.twig', [
            'film' => $film,
            'session' => $_SESSION,
        ]);
    }

    public function delete(array $queryParams): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $film = $this->filmRepository->find((int) $queryParams['id']);

            if ($film) {
                $this->filmRepository->deleteFilm($film);
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