<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookController.
 *
 * @Route("/book")
 */

class BookController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \App\Repository\BookRepository $bookRepository book repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *    "/",
     *    name="book_index",
     *    methods={"GET"}
     * )
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig',
            ['books' => $bookRepository->findAll()]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Book $book book entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="book_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Book $book): Response
    {
        return $this->render(
            'book/show.html.twig',
            ['book' => $book]
        );
    }
}

