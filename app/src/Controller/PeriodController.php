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
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="period_create",
     * )
     *
     */
    public function create(Request $request): Response
    {
        $period = new Period();
        $form = $this->createForm(PeriodType::class, $period);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->periodService->save($period);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('period_index');
        }

        return $this->render(
            'period/create.html.twig',
            ['form' => $form->createView()]
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

    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param Period $period Period entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/edit",
     *     methods={"GET", "PUT"},
     *     name="period_edit",
     * )
     */
    public function edit(Request $request, Period $period): Response
    {
        $form = $this->createForm(PeriodType::class, $period, [
            'method' => 'PUT',
            'action' => $this->generateUrl('period_edit', ['slug' => $period->getSlug()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->periodService->save($period);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('period_index');
        }

        return $this->render(
            'period/edit.html.twig',
            [
                'form' => $form->createView(),
                'period' => $period,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Period $period Period entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/delete",
     *     methods={"GET", "DELETE"},
     *     name="period_delete",
     * )
     */
    public function delete(Request $request, Period $period): Response
    {
        if (!$this->periodService->canBeDeleted($period)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.period_has_pieces')
            );
            return $this->redirectToRoute('period_index');
        }

        $form = $this->createForm(FormType::class, $period, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('period_delete', ['slug' => $period->getSlug()]),
        ]);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->periodService->delete($period);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('period_index');
        }

        return $this->render(
            'period/delete.html.twig',
            [
                'form' => $form->createView(),
                'period' => $period,
            ]
        );
    }
}
