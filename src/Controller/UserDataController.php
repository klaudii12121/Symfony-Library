<?php

/**
 * UserData Controller
 */
namespace App\Controller;

use App\Entity\UserData;
use App\Form\UserDataType;;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserDataService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class UserDataController
 * @Route("/data")
 */
class UserDataController extends AbstractController
{
    /**
     * UserData service
     *
     * @var UserDataService
     */
    private $userDataService;

    /**
     * UserDataController constructor.
     *
     * @param UserDataService $userDataService
     */
    public function __construct(UserDataService $userDataService)
    {
        $this->userDataService = $userDataService;
    }

    /**
     * Edit User data.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="userData_edit",
     * );
     *
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, UserData $userData): Response
    {
        $form = $this->createForm(UserDataType::class, $userData, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userDataService->save($userData);

            $this->addFlash('success', 'data updated successfully');

            return $this->redirectToRoute('main_index');
        }

        return $this->render(
            'userData/edit.html.twig',
            [
                'form' => $form->createView(),
                'userData' => $userData,
            ]
        );
    }
}
