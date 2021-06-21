<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Service\UserDataService;
use App\Service\UserService;


class RegistrationController extends AbstractController
{
    /**
     * User service
     *
     * @var UserService
     */
    private $userService;

    /**
     * UserData service
     *
     * @var UserDataService
     */
    private $userDataService;

    /**
     * RegistrationController constructor.
     *
     * @param UserService     $userService
     * @param UserDataService $userDataService
     */
    public function __construct(UserService $userService, UserDataService $userDataService)
    {
        $this->userService = $userService;
        $this->userDataService = $userDataService;
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     *
     * @Route(
     *     "/register",
     *     methods={"GET", "POST"},
     *     name="app_register",
     * )
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $userData = new UserData();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );
            $user->setRoles([User::ROLE_USER]);
            $user->setUserData($userData);
            $userData->setNick('noname');
            $this->userService->save($user);
            $this->userDataService->save($userData);

            $this->addFlash('success', 'Hello new user! You are noname now. Go to your profile and fill in the details about yourself!');

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'user' => $user,
        ]);
    }
}
