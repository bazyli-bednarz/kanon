<?php
/**
 * Piece controller.
 */

namespace App\Controller;



use App\Entity\Piece;
use App\Form\Type\PieceType;
use App\Service\PieceServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PieceController.
 *
 * @Route(
 *     "/piece"
 * )
 */
class PieceController extends AbstractController
{
    private PieceServiceInterface $pieceService;
    private TranslatorInterface $translator;

    public function __construct(PieceServiceInterface $pieceService, TranslatorInterface $translator)
    {
        $this->pieceService = $pieceService;
        $this->translator = $translator;
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
     *     name="piece_create",
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request): Response
    {
        $piece = new Piece();
        $user = $this->getUser();
        $piece->setAuthor($user);
        $piece->setEditedBy($user);
        $form = $this->createForm(PieceType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->pieceService->save($piece);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('piece_show', ['slug' => $piece->getSlug()]);
        }

        return $this->render(
            'piece/create.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * Index action.
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="piece_index"
     * )
     */
    public function index(Request $request): Response
    {
        $pagination = $this->pieceService->getPaginatedList(
            $request->query->getInt('page', 1),
        );

        return $this->render(
            'piece/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Piece $piece Piece entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}",
     *     methods={"GET"},
     *     name="piece_show",
     * )
     */
    public function show(Piece $piece): Response
    {
        return $this->render(
            'piece/show.html.twig',
            ['piece' => $piece]
        );
    }


    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Piece $piece
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/edit",
     *     methods={"GET", "PUT"},
     *     name="piece_edit",
     * )
     *
     * @IsGranted("EDIT", subject="piece")
     */
    public function edit(Request $request, Piece $piece): Response
    {
        $form = $this->createForm(PieceType::class, $piece, [
            'method' => 'PUT',
            'action' => $this->generateUrl('piece_edit', ['slug' => $piece->getSlug()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $piece->setEditedBy($user);
            $this->pieceService->save($piece);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('piece_show', ['slug' => $piece->getSlug()]);
        }

        return $this->render(
            'piece/edit.html.twig',
            [
                'form' => $form->createView(),
                'piece' => $piece,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Piece $piece
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/delete",
     *     methods={"GET", "DELETE"},
     *     name="piece_delete",
     * )
     *
     * @IsGranted("DELETE", subject="piece")
     */
    public function delete(Request $request, Piece $piece): Response
    {
        if (!$this->pieceService->canBeDeleted($piece)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.piece_in_canons')
            );
            return $this->redirectToRoute('piece_show', ['slug' => $piece->getSlug()]);
        }

        $form = $this->createForm(FormType::class, $piece, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('piece_delete', ['slug' => $piece->getSlug()]),
        ]);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->pieceService->delete($piece);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('piece_index');
        }

        return $this->render(
            'piece/delete.html.twig',
            [
                'form' => $form->createView(),
                'piece' => $piece,
            ]
        );
    }
}
