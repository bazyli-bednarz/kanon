<?php
/**
 * Composer controller.
 */

namespace App\Controller;



use App\Entity\Composer;
use App\Form\Type\ComposerType;
use App\Service\ComposerServiceInterface;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ComposerController.
 *
 * @Route(
 *     "/composer"
 * )
 */
class ComposerController extends AbstractController
{
    private ComposerServiceInterface $composerService;
    private UserServiceInterface $userService;

    private TranslatorInterface $translator;

    /**
     * ComposerController constructor.
     */
    public function __construct(ComposerServiceInterface $composerService, UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->composerService = $composerService;
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
     *     name="composer_index"
     * )
     */
    public function index(Request $request): Response
    {
        $pagination = $this->composerService->getPaginatedList($request->query->getInt('page', 1),);

        return $this->render(
            'composer/index.html.twig',
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
     *     name="composer_create",
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
            return $this->redirectToRoute('composer_index');
        }
        $composer = new Composer();
        $composer->setAuthor($user);
        $composer->setEditedBy($user);
        $form = $this->createForm(ComposerType::class, $composer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->composerService->save($composer);
            $user = $this->getUser();
            $user->addExperience(20);
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('composer_show', ['slug' => $composer->getSlug()]);
        }

        return $this->render(
            'composer/create.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * Show action.
     *
     * @param Composer $composer Composer entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}",
     *     methods={"GET"},
     *     name="composer_show",
     * )
     */
    public function show(Composer $composer, Request $request): Response
    {
        $pagination = $this->composerService->getPaginatedListByComposer($request->query->getInt('page', 1), $composer);

        return $this->render(
            'composer/show.html.twig',
            [
                'pagination' => $pagination,
                'composer' => $composer
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param Composer $composer Composer entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/edit",
     *     methods={"GET", "PUT"},
     *     name="composer_edit",
     * )
     *
     * @IsGranted("EDIT", subject="composer")
     */
    public function edit(Request $request, Composer $composer): Response
    {
        $user = $this->getUser();
        if (!$user->isVerified()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.verify_email_first')
            );
            return $this->redirectToRoute('composer_index');
        }

        $form = $this->createForm(ComposerType::class, $composer, [
            'method' => 'PUT',
            'action' => $this->generateUrl('composer_edit', ['slug' => $composer->getSlug()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $composer->setEditedBy($user);
            $this->composerService->save($composer);
            $user->addExperience(5);
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('composer_show', ['slug' => $composer->getSlug()]);
        }

        return $this->render(
            'composer/edit.html.twig',
            [
                'form' => $form->createView(),
                'composer' => $composer,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Composer $composer Composer entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/delete",
     *     methods={"GET", "DELETE"},
     *     name="composer_delete",
     * )
     *
     * @IsGranted("DELETE", subject="composer")
     */
    public function delete(Request $request, Composer $composer): Response
    {
        $user = $this->getUser();
        if (!$user->isVerified()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.verify_email_first')
            );
            return $this->redirectToRoute('composer_index');
        }

        if (!$this->composerService->canBeDeleted($composer)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.composer_has_pieces')
            );
            return $this->redirectToRoute('composer_show', ['slug' => $composer->getSlug()]);
        }

        $form = $this->createForm(FormType::class, $composer, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('composer_delete', ['slug' => $composer->getSlug()]),
        ]);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->composerService->delete($composer);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('composer_index');
        }

        return $this->render(
            'composer/delete.html.twig',
            [
                'form' => $form->createView(),
                'composer' => $composer,
            ]
        );
    }
}
