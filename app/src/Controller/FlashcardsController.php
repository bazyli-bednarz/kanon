<?php
/**
 * Canon controller.
 */

namespace App\Controller;



use App\Entity\Canon;

use App\Form\Type\CanonType;
use App\Form\Type\FlashcardsType;
use App\Form\Type\NextFlashcardType;
use App\Service\CanonServiceInterface;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CanonController.
 *
 * @Route(
 *     "/flashcards"
 * )
 */
class FlashcardsController extends AbstractController
{
    private CanonServiceInterface $canonService;
    private UserServiceInterface $userService;
    private Security $security;

    private TranslatorInterface $translator;

    /**
     * CanonController constructor.
     */
    public function __construct(CanonServiceInterface $canonService, UserServiceInterface $userService, TranslatorInterface $translator, Security $security)
    {
        $this->canonService = $canonService;
        $this->userService = $userService;
        $this->translator = $translator;
        $this->security = $security;
    }


    /**
     * Index action.
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET", "POST"},
     *     name="flashcards_index"
     * )
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(FlashcardsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $canon = $form->get('canons')->getData();
            if ($canon !== null) {
                return $this->redirectToRoute('flashcards_learn', ['slug' => $canon->getSlug()]);
            }

            $this->addFlash(
                'warning',
                $this->translator->trans('message.choose_canon')
            );
        }

        return $this->render(
            'flashcards/index.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * Learn canon action.
     *
     * @param Canon $canon Canon entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}",
     *     methods={"GET", "POST"},
     *     name="flashcards_learn",
     * )
     */
    public function learn(Canon $canon, Request $request): Response
    {
        $form = $this->createForm(NextFlashcardType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->security->getUser();
            if ($user) {
                if ($user->isVerified()) {
                    $user->addExperience(1);
                    $this->userService->save($user);
                }
            }
        }

        return $this->render(
            'flashcards/learn.html.twig',
            [
                'canon' => $canon,
                'piece' => $this->canonService->getRandomPieceByCanon($canon),
                'form' => $form->createView()
            ]
        );
    }
}
