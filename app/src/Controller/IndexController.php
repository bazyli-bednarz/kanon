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
 * @Route("/")
 *
 */
class IndexController extends AbstractController
{
    private PieceServiceInterface $pieceService;

    public function __construct(PieceServiceInterface $pieceService)
    {
        $this->pieceService = $pieceService;
    }

    /**
     * Index action.
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="index",
     * )
     *
     */
    public function index(): Response
    {
        return $this->render(
            'index.html.twig',
            ['piece' => $this->pieceService->getRandomPiece()]
        );
    }
}
