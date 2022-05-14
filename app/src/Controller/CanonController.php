<?php
/**
 * Canon controller.
 */

namespace App\Controller;



use App\Entity\Canon;

use App\Form\Type\CanonType;
use App\Service\CanonServiceInterface;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CanonController.
 *
 * @Route(
 *     "/canon"
 * )
 */
class CanonController extends AbstractController
{
    private CanonServiceInterface $canonService;
    private UserServiceInterface $userService;

    private TranslatorInterface $translator;

    /**
     * CanonController constructor.
     */
    public function __construct(CanonServiceInterface $canonService, UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->canonService = $canonService;
        $this->userService = $userService;
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
     *     name="canon_index"
     * )
     */
    public function index(Request $request): Response
    {
        $pagination = $this->canonService->getPaginatedList($request->query->getInt('page', 1),);

        return $this->render(
            'canon/index.html.twig',
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
     *     name="canon_create",
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user->isVerified()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.verify_email_first')
            );
            return $this->redirectToRoute('canon_index');
        }

        $canon = new Canon();
        $canon->setAuthor($user);
        $form = $this->createForm(CanonType::class, $canon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->canonService->save($canon);
            $user = $this->getUser();
            $user->addExperience(5);
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('canon_show', ['slug' => $canon->getSlug()]);
        }

        return $this->render(
            'canon/create.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * Show action.
     *
     * @param Canon $canon Canon entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}",
     *     methods={"GET"},
     *     name="canon_show",
     * )
     */
    public function show(Canon $canon, Request $request): Response
    {
        $pagination = $this->canonService->getPaginatedListByCanon($request->query->getInt('page', 1), $canon);

        return $this->render(
            'canon/show.html.twig',
            [
                'pagination' => $pagination,
                'canon' => $canon
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param Canon $canon Canon entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/edit",
     *     methods={"GET", "PUT"},
     *     name="canon_edit",
     * )
     *
     * @IsGranted("EDIT", subject="canon")
     */
    public function edit(Request $request, Canon $canon): Response
    {
        $user = $this->getUser();
        if (!$user->isVerified()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.verify_email_first')
            );
            return $this->redirectToRoute('canon_index');
        }
        $form = $this->createForm(CanonType::class, $canon, [
            'method' => 'PUT',
            'action' => $this->generateUrl('canon_edit', ['slug' => $canon->getSlug()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->canonService->save($canon);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('canon_show', ['slug' => $canon->getSlug()]);
        }

        return $this->render(
            'canon/edit.html.twig',
            [
                'form' => $form->createView(),
                'canon' => $canon,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Canon $canon Canon entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/delete",
     *     methods={"GET", "DELETE"},
     *     name="canon_delete",
     * )
     *
     * @IsGranted("DELETE", subject="canon")
     */
    public function delete(Request $request, Canon $canon): Response
    {
        $user = $this->getUser();
        if (!$user->isVerified()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.verify_email_first')
            );
            return $this->redirectToRoute('canon_index');
        }
        if (!$this->canonService->canBeDeleted($canon)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.canon_has_pieces')
            );
            return $this->redirectToRoute('canon_index');
        }

        $form = $this->createForm(FormType::class, $canon, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('canon_delete', ['slug' => $canon->getSlug()]),
        ]);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->canonService->delete($canon);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('canon_index');
        }

        return $this->render(
            'canon/delete.html.twig',
            [
                'form' => $form->createView(),
                'canon' => $canon,
            ]
        );
    }
}
