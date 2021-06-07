<?php
/**
 * Borrowing controller.
 */

namespace App\Controller;

use App\Entity\Borrowing;
use App\Repository\BorrowingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BorrowingController.
 *
 * @Route("/borrowing")
 */
class BorrowingController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\BookRepository $bookRepository book repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="borrow_index",
     * )
     */
    public function index(Request $request, BorrowingRepository $borrowingRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $borrowingRepository->queryByUser($this->getUser()),
            $request->query->getInt('page', 1),
            BorrowingRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'borrowing/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Borrowing $borrowing borrowing entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="borrow_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Borrowing $borrowing): Response
    {
        if ($borrowing->getUser() !== $this->getUser()) {
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('borrow_index');
        }

        return $this->render(
            'borrowing/show.html.twig',
            ['borrowing' => $borrowing]
        );
    }
}