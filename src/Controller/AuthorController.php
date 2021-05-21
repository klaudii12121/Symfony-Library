<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorController.
 *
 * @Route("/author")
 */

class AuthorController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \App\Repository\AuthorRepository $authorRepository Author repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *    "/",
     *    name="author_index",
     *    methods={"GET"}
     * )
     */
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('author/index.html.twig',
            ['authors' => $authorRepository->findAll()]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Author $author author entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="author_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Author $author): Response
    {
        return $this->render(
            'author/show.html.twig',
            ['author' => $author]
        );
    }
}

