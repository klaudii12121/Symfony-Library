<?php
/**
 * Author controller.
 */
namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

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
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\AuthorRepository $authorRepository Author repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator

     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *    "/",
     *    name="author_index",
     *    methods={"GET"}
     * )
     */
    public function index(Request $request, AuthorRepository $authorRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $authorRepository->queryAll(),
            $request->query->getInt('page', 1),
            AuthorRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'author/index.html.twig',
            ['pagination' => $pagination]
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

