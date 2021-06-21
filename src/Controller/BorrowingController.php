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
     * All borrowings.
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
     *     name="borrow_all",
     * )
     */
    public function allBorrowings(Request $request, BorrowingRepository $borrowingRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $borrowingRepository->queryForAdmin(),
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

        if ($form->isSubmitted() && $form->isValid() && $book->getAmount() != 0) {
            $borrowing->setUser($this->getUser());
            $borrowing->setBook($book);
            $bookAmount = $book->setAmount($book->getAmount()-1);

            $borrowingRepository->save($borrowing);
            $bookRepository->save($bookAmount);

            $this->addFlash('success', 'Wyraziłeś chęć wypożyczenia, musisz poczekać, aż Admin zaakceptuje twoje wypożyczenie, wtedy zobaczysz je na tej liście!');
            return $this->redirectToRoute('borrow_index');
        }elseif ($book->getAmount() == 0) {
            $this->addFlash('success', 'Tej ksiązki, na razie, nie mamy na stanie. Poczekaj aż ktoś inny zwróci swój egzemplarz.');
            return $this->redirectToRoute('borrow_index');
        }

        return $this->render(
            'borrowing/create.html.twig',
            ['form' => $form->createView(),
                'borrowing' => $borrowing,
                'book' => $book]
        );
    }

    /**
     * Confirm action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\BorrowingRepository        $borrowingRepository Borrowing repository
     * @param \App\Repository\BookRepository        $bookRepository Book repository
     * @param \App\Entity\Borrowing                      $borrowing           Borrowing entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/confirm",
     *     methods={"GET", "PUT"},
     *     name="borrow_confirm",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function confirm(Request $request, Borrowing $borrowing, BorrowingRepository $borrowingRepository, BookRepository $bookRepository): Response
    {

        $form = $this->createForm(BorrowingType::class, $borrowing, [ 'method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setBorrowDate(new \DateTime( 'NOW'));

            $borrowingRepository->save($borrowing);

            $this->addFlash('success', 'Potwierdziłeś wypożyczenie. Książka została dzisiaj wypożyczona.');
            return $this->redirectToRoute('borrow_all');
        }
        return $this->render(
            'borrowing/confirm.html.twig',
            ['form' => $form->createView(),
                'borrowing' => $borrowing,]
        );
    }

    /**
     * Discard action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\BorrowingRepository        $borrowingRepository Borrowing repository
     * @param \App\Repository\BookRepository        $bookRepository Book repository
     * @param \App\Entity\Borrowing                      $borrowing           Borrowing entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/discard",
     *     methods={"GET", "DELETE"},
     *     name="borrow_discard",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function discard(Request $request, Borrowing $borrowing, BorrowingRepository $borrowingRepository, BookRepository $bookRepository): Response
    {

        $form = $this->createForm(BorrowingType::class, $borrowing, [ 'method' => 'DELETE']);
        $form->handleRequest($request);
        $book = $bookRepository->find($borrowing->getBook());

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $borrowingRepository->delete($borrowing);
            $bookAmount = $book->setAmount($book->getAmount()+1);
            $bookRepository->save($bookAmount);

            $this->addFlash('success', 'Odrzuciłeś wypożyczenie.');
            return $this->redirectToRoute('borrow_all');
        }
        return $this->render(
            'borrowing/discard.html.twig',
            ['form' => $form->createView(),
                'borrowing' => $borrowing,
                'book' => $book,]
        );
    }
    /**
     * Return action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\BorrowingRepository        $borrowingRepository Borrowing repository
     * @param \App\Repository\BookRepository        $bookRepository Book repository
     * @param \App\Entity\Borrowing                      $borrowing           Borrowing entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/return",
     *     methods={"GET", "PUT"},
     *     name="borrow_return",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function return(Request $request, Borrowing $borrowing, BorrowingRepository $borrowingRepository, BookRepository $bookRepository): Response
    {

        $form = $this->createForm(BorrowingType::class, $borrowing, [ 'method' => 'PUT']);
        $form->handleRequest($request);
        $book = $bookRepository->find($borrowing->getBook());

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setReturnDate(new \DateTime( 'NOW'));
            $bookAmount = $book->setAmount($book->getAmount()+1);
            $bookRepository->save($bookAmount);
            $borrowingRepository->save($borrowing);

            $this->addFlash('success', 'Zwróciłeś książkę.');
            return $this->redirectToRoute('borrow_index');
        }
        return $this->render(
            'borrowing/return.html.twig',
            ['form' => $form->createView(),
                'borrowing' => $borrowing,]
        );
    }
}