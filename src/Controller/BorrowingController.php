<?php
/**
 * Borrowing controller.
 */

namespace App\Controller;

use App\Entity\Borrowing;
use App\Entity\Book;
use App\Entity\User;
use App\Form\BorrowingType;
use App\Repository\BookRepository;
use App\Repository\BorrowingRepository;
use App\Repository\UserRepository;
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
     * One user borrowings.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Repository\BorrowingRepository $borrowingRepository borrowing repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/all",
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
     * All borrowings.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Repository\BorrowingRepository $borrowingRepository borrowing repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="borrow_all",
     * )
     */
    public function allBorrowings(Request $request, BorrowingRepository $borrowingRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $borrowingRepository->queryAll(),
            $request->query->getInt('page', 1),
            BorrowingRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'borrowing/all.html.twig',
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
    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\BorrowingRepository        $borrowingRepository Borrowing repository
     * @param \App\Repository\BookRepository        $bookRepository Book repository
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="borrow_create",
     * )
     */
    public function create(Request $request, BorrowingRepository $borrowingRepository, BookRepository $bookRepository): Response
    {
        $borrowing = new Borrowing();
        $form = $this->createForm(BorrowingType::class, $borrowing);
        $form->handleRequest($request);
        $book = $bookRepository->find($request->query->getInt('id'));

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setUser($this->getUser());
            $borrowing->setBook($book);
            $borrowing->setBorrowDate(new \DateTime('1970-01-01'));
            $borrowing->setReturnDate(new \DateTime('1970-01-01'));
            $bookAmount = $book->setAmount($book->getAmount()-1);

            $borrowingRepository->save($borrowing);
            $bookRepository->save($bookAmount);

            $this->addFlash('success', 'Wyraziłeś chęć wypożyczenia, musisz poczekać, aż Admin zaakceptuje twoje wypożyczenie, wtedy zobaczysz je na tej liście!');
            return $this->redirectToRoute('borrow_index');
        }

        return $this->render(
            'borrowing/create.html.twig',
            ['form' => $form->createView(),
                'borrowing' => $borrowing,
                'book' => $book]
        );
    }

}