<?php
/**
 * User controller.
 */

namespace App\Controller;



use App\Entity\User;
use App\Form\Type\FriendsType;
use App\Form\Type\ProfileImageType;
use App\Form\Type\UserType;
use App\Security\LoginFormAuthenticator;
use App\Service\UserServiceInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
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
     *     methods={"GET", "PUT"},
     *     name="user_show",
     * )
     */
    public function show(User $user, Request $request): Response
    {
        $activeUser = $this->getUser();
        $form = null;
        if ($activeUser !== null && $activeUser->isVerified() && $activeUser !== $user) {
            $form = $this->createForm(FriendsType::class, $user, [
                'method' => 'PUT',
                'action' => $this->generateUrl('user_show', ['slug' => $user->getSlug()]),
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($activeUser->getFriends()->contains($user)) {
                    $user->removeFriendsWithMe($activeUser);
                    $activeUser->removeFriend($user);

                    $this->userService->save($user);
                    $this->userService->save($activeUser);

                    $this->addFlash(
                        'success',
                        $this->translator->trans('message.removed_from_friends')
                    );
                }
                else {
                    $user->addFriendsWithMe($activeUser);
                    $activeUser->addFriend($user);

                    $this->userService->save($user);
                    $this->userService->save($activeUser);

                    $this->addFlash(
                        'success',
                        $this->translator->trans('message.added_to_friends')
                    );
                }

                return $this->redirectToRoute('user_show', ['slug' => $user->getSlug()]);
            }

        }
        if ($form) {
            return $this->render(
                'user/show.html.twig',
                [
                    'user' => $user,
                    'form' => $form->createView()
                ]
            );
        }
        return $this->render(
            'user/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }


    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param User $user User entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/edit",
     *     methods={"GET", "PUT"},
     *     name="user_edit",
     * )
     *
     * @IsGranted("EDIT", subject="user")
     */
    public function edit(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator): Response
    {
        $activeUser = $this->getUser();
        if (!$activeUser->isVerified()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.verify_email_first')
            );
            return $this->redirectToRoute('user_index');
        }

        $form = $this->createForm(UserType::class, $user, [
            'method' => 'PUT',
            'action' => $this->generateUrl('user_edit', ['slug' => $user->getSlug()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $oldPassword = $form->get('oldPassword')->getData();

            if (!$userPasswordHasher->isPasswordValid($user, $oldPassword))
            {
                $this->addFlash(
                    'danger',
                    $this->translator->trans('message.edited_invalid_old_password')
                );
                return $this->render(
                    'user/edit.html.twig',
                    [
                        'form' => $form->createView(),
                        'user' => $user,
                    ]
                );
            }
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            $user->setPassword($encodedPassword);
            $this->userService->save($user);



            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );
            if ($activeUser === $user) {
                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            }

            return $this->redirectToRoute('user_show', ['slug' => $user->getSlug()]);
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Edit profile image action.
     *
     * @param Request  $request  HTTP request
     * @param User $user User entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{slug}/profile-image",
     *     methods={"GET", "PUT"},
     *     name="user_image",
     * )
     *
     * @IsGranted("EDIT", subject="user")
     */
    public function editImage(Request $request, User $user): Response
    {
        $activeUser = $this->getUser();
        if (!$activeUser->isVerified()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.verify_email_first')
            );
            return $this->redirectToRoute('user_index');
        }

        $form = $this->createForm(ProfileImageType::class, $user, [
            'method' => 'PUT',
            'action' => $this->generateUrl('user_image', ['slug' => $user->getSlug()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $chosenImage = $form->get('image')->getData();
            $user->setImage($chosenImage);

            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('user_show', ['slug' => $user->getSlug()]);
        }

        return $this->render(
            'user/profile_image.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
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
