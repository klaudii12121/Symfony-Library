<?php
/**
 * Borrowing controller.
 */

namespace App\Controller;

use App\Entity\Borrowing;
use App\Form\BorrowingType;
use App\Service\BookService;
use App\Service\BorrowingService;
use App\Service\UserService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BorrowingController.
 *
 * @Route("/borrowing")
 */
class BorrowingController extends AbstractController
{
    private BorrowingService $borrowingService;

    private BookService $bookService;

    private UserService $userService;

    /**
     * BorrowingController constructor.
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
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
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
        $pagination = $this->borrowingService->borrowByUser($page, $user);

        return $this->render(
            'borrowing/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * All borrowings.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
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
     * @param Borrowing $borrowing borrowing entity
     *
     * @return Response HTTP response
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
            $this->addFlash('warning', 'message.access_denied');

            return $this->redirectToRoute('borrow_index');
        }

        return $this->render(
            'borrowing/index.html.twig',
            ['borrowing' => $borrowing]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
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

        if (0 != $book->getAmount()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $borrowing->setUser($this->getUser());
                $borrowing->setBook($book);
                $bookAmount = $book->setAmount($book->getAmount() - 1);

                $this->borrowingService->save($borrowing);
                $this->bookService->save($bookAmount);

                $this->addFlash('success', 'willingness_to_borrow');

                return $this->redirectToRoute('borrow_index');
            }
        } else {
            $this->addFlash('success', 'no_book');

            return $this->redirectToRoute('borrow_index');
        }

        return $this->render(
            'borrowing/create.html.twig',
            ['form' => $form->createView(),
                'borrowing' => $borrowing,
                'book' => $book, ]
        );
    }

    /**
     * Confirm action.
     *
     * @param Request   $request   HTTP request
     * @param Borrowing $borrowing Borrowing entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
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
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'PUT']);
        $form->handleRequest($request);
        $book = $borrowing->getBook();
        $user = $borrowing->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setBorrowDate(new \DateTime('NOW'));
            $this->borrowingService->save($borrowing);

            $this->addFlash('success', 'message.confirm_borrow');

            return $this->redirectToRoute('borrow_all');
        }

        return $this->render(
            'borrowing/confirm.html.twig',
            ['form' => $form->createView(),
                'borrowing' => $borrowing,
                'book' => $book,
                'user' => $user, ]
        );
    }

    /**
     * Discard action.
     *
     * @param Request   $request   HTTP request
     * @param Borrowing $borrowing Borrowing entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
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
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'DELETE']);
        $form->handleRequest($request);
        $book = $this->bookService->findByObject($borrowing->getBook());
        $user = $borrowing->getUser();

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->borrowingService->delete($borrowing);
            $bookAmount = $book->setAmount($book->getAmount() + 1);
            $this->bookService->save($bookAmount);

            $this->addFlash('success', 'message.discard_borrow');

            return $this->redirectToRoute('borrow_all');
        }

        return $this->render(
            'borrowing/discard.html.twig',
            ['form' => $form->createView(),
                'borrowing' => $borrowing,
                'book' => $book,
                'user' => $user, ]
        );
    }

    /**
     * Return action.
     *
     * @param Request   $request   HTTP request
     * @param Borrowing $borrowing Borrowing entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
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
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'PUT']);
        $form->handleRequest($request);
        $book = $this->bookService->findByObject($borrowing->getBook());

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setReturnDate(new \DateTime('NOW'));
            $bookAmount = $book->setAmount($book->getAmount() + 1);
            $this->bookService->save($bookAmount);
            $this->borrowingService->save($borrowing);

            $this->addFlash('success', 'message.return_borrow');

            return $this->redirectToRoute('borrow_index');
        }

        return $this->render(
            'borrowing/return.html.twig',
            ['form' => $form->createView(),
                'borrowing' => $borrowing,
                'book' => $book, ]
        );
    }
}
