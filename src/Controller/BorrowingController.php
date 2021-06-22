<?php
/**
 * Borrowing controller.
 */

namespace App\Controller;

use App\Entity\Borrowing;
use App\Form\BorrowingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\BorrowingService;
use App\Service\BookService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * Class BorrowingController.
 *
 * @Route("/borrowing")
 */
class BorrowingController extends AbstractController
{
    /**
     * @var BorrowingService
     */
    private $borrowingService;

    /**
     * @var BookService
     */
    private $bookService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * BorrowingController constructor.
     *
     * @param BorrowingService $borrowingService
     * @param BookService     $bookService
     * @param UserService $userService
     */
    public function __construct(BorrowingService $borrowingService, BookService $bookService, UserService $userService)
    {
        $this->borrowingService = $borrowingService;
        $this->bookService = $bookService;
        $this->userService = $userService;
    }
    /**
     * One user borrowings.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="borrow_index",
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $page = $request->query->getInt('page', '1');
        $pagination = $this->borrowingService->borrowByUser($page,$user);

        return $this->render(
            'borrowing/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * All borrowings.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/all",
     *     methods={"GET"},
     *     name="borrow_all",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function allBorrowings(Request $request): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $this->borrowingService->borrowForAdmin($page);

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
     *
     * @IsGranted("ROLE_USER")
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
     *
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request): Response
    {
        $borrowing = new Borrowing();
        $form = $this->createForm(BorrowingType::class, $borrowing);
        $form->handleRequest($request);
        $book = $this->bookService->findByID($request->query->getInt('id'));

        if ($form->isSubmitted() && $form->isValid() && $book->getAmount() != 0) {
            $borrowing->setUser($this->getUser());
            $borrowing->setBook($book);
            $bookAmount = $book->setAmount($book->getAmount()-1);

            $this->borrowingService->save($borrowing);
            $this->bookService->save($bookAmount);

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
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function confirm(Request $request, Borrowing $borrowing): Response
    {

        $form = $this->createForm(BorrowingType::class, $borrowing, [ 'method' => 'PUT']);
        $form->handleRequest($request);
        $book = $borrowing->getBook();
        $user = $borrowing->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setBorrowDate(new \DateTime( 'NOW'));
            $this->borrowingService->save($borrowing);

            $this->addFlash('success', 'Potwierdziłeś wypożyczenie. Książka została dzisiaj wypożyczona.');
            return $this->redirectToRoute('borrow_all');
        }
        return $this->render(
            'borrowing/confirm.html.twig',
            ['form' => $form->createView(),
                'borrowing' => $borrowing,
                'book' => $book,
                'user' => $user]
        );
    }

    /**
     * Discard action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
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
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function discard(Request $request, Borrowing $borrowing): Response
    {

        $form = $this->createForm(BorrowingType::class, $borrowing, [ 'method' => 'DELETE']);
        $form->handleRequest($request);
        $book = $this->bookService->findByObject($borrowing->getBook());
        $user = $borrowing->getUser();

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->borrowingService->delete($borrowing);
            $bookAmount = $book->setAmount($book->getAmount()+1);
            $this->bookService->save($bookAmount);

            $this->addFlash('success', 'Odrzuciłeś wypożyczenie.');
            return $this->redirectToRoute('borrow_all');
        }
        return $this->render(
            'borrowing/discard.html.twig',
            ['form' => $form->createView(),
                'borrowing' => $borrowing,
                'book' => $book,
                'user' => $user,]
        );
    }
    /**
     * Return action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
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
     *
     * @IsGranted("ROLE_USER")
     */
    public function return(Request $request, Borrowing $borrowing): Response
    {

        $form = $this->createForm(BorrowingType::class, $borrowing, [ 'method' => 'PUT']);
        $form->handleRequest($request);
        $book = $this->bookService->findByObject($borrowing->getBook());

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setReturnDate(new \DateTime( 'NOW'));
            $bookAmount = $book->setAmount($book->getAmount()+1);
            $this->bookService->save($bookAmount);
            $this->borrowingService->save($borrowing);

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