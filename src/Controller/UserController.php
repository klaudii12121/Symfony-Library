<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UpgradePassType;
use App\Service\UserService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * User service.
     */
    private UserService $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService User service
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *    "/",
     *    name="user_index",
     *    methods={"GET"}
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $this->userService->createPaginatedList($page);

        return $this->render(
            'user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param User $user User entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="user_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted("ROLE_USER")
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
     * @param Request                      $request         HTTP request
     * @param User                         $user            User entity
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/password",
     *     methods={"GET","PUT"},
     *     name="pass_edit",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function upgradePass(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if (($this->getUser()->getId() === $user->getId()) || $this->isGranted('ROLE_ADMIN')) {
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
                $this->addFlash('success', 'password_changed_successfully');

                if (!$this->isGranted('ROLE_ADMIN')) {
                    return $this->redirectToRoute('app_logout');
                }
            }

            return $this->render(
                'user/upgrade.html.twig',
                [
                    'form' => $form->createView(),
                    'user' => $user,
                ]
            );
        }
        $this->addFlash('success', 'password_changed_successfully');
        return $this->redirectToRoute('main_index', );
    }
}
