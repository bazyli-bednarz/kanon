<?php
/**
 * kanon-historii-muzyki
 *
 * (c) Bazyli Bednarz, 2022
 */

namespace App\Controller;

use App\Service\PieceServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController.
 *
 * @Route("/about")
 *
 */
class AboutController extends AbstractController
{

    /**
     * Index action.
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="about_index",
     * )
     *
     */
    public function index(): Response
    {
        return $this->render(
            'about/index.html.twig',
        );
    }
}
