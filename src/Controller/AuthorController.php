<?php
/**
 * Author controller.
 */
namespace App\Controller;

use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\AuthorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AuthorController.
 *
 * @Route("/author")
 */

class AuthorController extends AbstractController
{

    /**
     * Author service.
     *
     * @var \App\Service\AuthorService
     */
    private $authorService;

    /**
     * AuthorController constructor.
     *
     * @param \App\Service\AuthorService $authorService Author service
     */
    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request

     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *    "/",
     *    name="author_index",
     *    methods={"GET"}
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $this->authorService->createPaginatedList($page);

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
     *
     * @IsGranted("ROLE_USER")
     */
    public function show(Author $author): Response
    {
        return $this->render(
            'author/show.html.twig',
            ['author' => $author]
        );
    }
}

