<?php
/**
 * Scale controller.
 */

namespace App\Controller;



use App\Entity\Scale;

use App\Service\ScaleServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ScaleController.
 *
 * @Route(
 *     "/scale"
 * )
 */
class ScaleController extends AbstractController
{
    private ScaleServiceInterface $scaleService;

    /**
     * ScaleController constructor.
     */
    public function __construct(ScaleServiceInterface $scaleService)
    {
        $this->scaleService = $scaleService;
    }


    /**
     * Index action.
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="scale_index"
     * )
     */
    public function index(Request $request): Response
    {
        $pagination = $this->scaleService->getPaginatedList($request->query->getInt('page', 1),);

        return $this->render(
            'scale/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Scale $scale Scale entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}",
     *     methods={"GET"},
     *     name="scale_show",
     * )
     */
    public function show(Scale $scale, Request $request): Response
    {
        $pagination = $this->scaleService->getPaginatedListByScale($request->query->getInt('page', 1), $scale);

        return $this->render(
            'scale/show.html.twig',
            [
                'pagination' => $pagination,
                'scale' => $scale
            ]
        );
    }
}
