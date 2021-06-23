<?php
/**
 * UserData Controller.
 */

namespace App\Controller;

use App\Entity\UserData;
use App\Form\UserDataType;
use App\Service\UserDataService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserDataController.
 *
 * @Route("/data")
 */
class UserDataController extends AbstractController
{
    /**
     * UserData service.
     */
    private UserDataService $userDataService;

    /**
     * UserDataController constructor.
     *
     * @param UserDataService $userDataService
     *
     */
    public function __construct(UserDataService $userDataService)
    {
        $this->userDataService = $userDataService;
    }

    /**
     * Edit User data.
     *
     * @param Request $request HTTP request
     * @param UserData $userData
     *
     * @throws ORMException
     * @throws OptimisticLockException
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

            $this->addFlash('success', 'message_updated_successfully');

            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('user_index');
            } else {
                return $this->redirectToRoute('main_index', );
            }
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
