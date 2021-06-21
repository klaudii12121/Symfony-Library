<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\UpgradePassType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\UserService;

/**
 * Class UserController.
 *
 * @Route("/user")
 */

class UserController extends AbstractController
{
    /**
     * User service.
     *
     * @var \App\Service\UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param \App\Service\UserService $userService User service
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Index action.
     *
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *    "/",
     *    name="user_index",
     *    methods={"GET"}
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $this->bookService->createPaginatedList($page);

        return $this->render(
            'user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\User $user User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="user_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(User $user): Response
    {
        return $this->render(
            'user/show.html.twig',
            ['user' => $user]
        );
    }

    /**
     * Upgrade password.
     *
     * @param \App\Entity\User $user User entity
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}/password",
     *     methods={"GET","PUT"},
     *     name="pass_edit",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function upgradePass(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UpgradePassType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $this->userService->save($user);
                $this->addFlash('success', 'password changed successfully');

                return $this->redirectToRoute('app_logout');
            }
        return $this->render(
            'user/upgrade.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }



}