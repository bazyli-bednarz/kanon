<?php
/**
 * User controller.
 */

namespace App\Controller;



use App\Entity\User;
use App\Form\Type\UserType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 *
 * @Route(
 *     "/user"
 * )
 */
class UserController extends AbstractController
{
    private UserServiceInterface $userService;

    private TranslatorInterface $translator;

    /**
     * UserController constructor.
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator)
    {
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
     *     name="user_index"
     * )
     */
    public function index(Request $request): Response
    {
        $pagination = $this->userService->getPaginatedList($request->query->getInt('page', 1),);

        return $this->render(
            'user/index.html.twig',
            ['pagination' => $pagination]
        );
    }


    /**
     * Show action.
     *
     * @param User $user User entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}",
     *     methods={"GET"},
     *     name="user_show",
     * )
     */
    public function show(User $user, Request $request): Response
    {
        return $this->render(
            'user/show.html.twig',
            [
                'user' => $user
            ]
        );
    }

    /**
     * Show user pieces action.
     *
     * @param User $user User entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/pieces",
     *     methods={"GET"},
     *     name="user_pieces",
     * )
     */
    public function showPieces(User $user, Request $request): Response
    {
        $pagination = $this->userService->getPaginatedListByUserPieces($request->query->getInt('page', 1), $user);

        return $this->render(
            'user/show_pieces.html.twig',
            [
                'pagination' => $pagination,
                'user' => $user
            ]
        );
    }

    /**
     * Show user canons action.
     *
     * @param User $user User entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/canons",
     *     methods={"GET"},
     *     name="user_canons",
     * )
     */
    public function showCanons(User $user, Request $request): Response
    {
        $pagination = $this->userService->getPaginatedListByUserCanons($request->query->getInt('page', 1), $user);

        return $this->render(
            'user/show_canons.html.twig',
            [
                'pagination' => $pagination,
                'user' => $user
            ]
        );
    }

    /**
     * Show user composers action.
     *
     * @param User $user User entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/composers",
     *     methods={"GET"},
     *     name="user_composers",
     * )
     */
    public function showComposers(User $user, Request $request): Response
    {
        $pagination = $this->userService->getPaginatedListByUserComposers($request->query->getInt('page', 1), $user);

        return $this->render(
            'user/show_composers.html.twig',
            [
                'pagination' => $pagination,
                'user' => $user
            ]
        );
    }

}
