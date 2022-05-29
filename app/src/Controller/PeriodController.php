<?php
/**
 * Period controller.
 */

namespace App\Controller;



use App\Entity\Period;

use App\Service\PeriodServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PeriodController.
 *
 * @Route(
 *     "/period"
 * )
 */
class PeriodController extends AbstractController
{
    private PeriodServiceInterface $periodService;

    private TranslatorInterface $translator;

    /**
     * PeriodController constructor.
     */
    public function __construct(PeriodServiceInterface $periodService, TranslatorInterface $translator)
    {
        $this->periodService = $periodService;
        $this->translator = $translator;
    }


    /**
     * Index action.
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="period_index"
     * )
     */
    public function index(Request $request): Response
    {
        $pagination = $this->periodService->getPaginatedList($request->query->getInt('page', 1),);

        return $this->render(
            'period/index.html.twig',
            ['pagination' => $pagination]
        );
    }



    /**
     * Show action.
     *
     * @param Period $period Period entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}",
     *     methods={"GET"},
     *     name="period_show",
     * )
     */
    public function show(Period $period, Request $request): Response
    {
        $pagination = $this->periodService->getPaginatedListByPeriod($request->query->getInt('page', 1), $period);

        return $this->render(
            'period/show.html.twig',
            [
                'pagination' => $pagination,
                'period' => $period
            ]
        );
    }
}
