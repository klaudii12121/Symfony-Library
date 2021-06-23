<?php
/**
 * Main controller.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class MainController.
 */
class MainController extends AbstractController
{
    /**
     * Index action.
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="main_index",
     * )
     *
     */
    public function index(): Response
    {
        return $this->render(
            'Main/main.html.twig',
        );
    }
}
