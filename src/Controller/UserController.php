<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordType;
use App\Form\RegisterUserType;
use App\Form\UpdateUserType;
use App\Service\User\RegisterService;
use App\Service\User\UserListService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/inscription', name: 'app_registration')]
    public function registration(Request $request, RegisterService $registerService)
    {

        $form = $this->createForm(RegisterUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $registerService->register($form->getData());

            $this->addFlash('success', 'Votre compte a été correctement créer, vous pouvez à présent vous connecter !');
            return $this->redirectToRoute('home');
        }

        return $this->render('security/registration.html.twig', [
            'formUser' => $form->createView()
        ]);
    }


    #[Route('/creer-utilisateur', name: "app_create_user")]
    public function create_user(Request $request,
                                RegisterService $register)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $formUser = $this->createForm(UpdateUserType::class);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {

            $register->register($formUser->getData());

            $this->addFlash('success', 'Le compte de '. $formUser->getData()->getUserName()." a bien été crée.");
            return $this->redirectToRoute('home');
        }

        return $this->render('user/update.html.twig', [
            'formUser' => $formUser->createView(),
        ]);

    }

    #[Route('/modifier-utilisateur/{user_id}', name: "app_update_user")]
    public function updateUser(Request $request,
                               RegisterService $registerService,
                               #[MapEntity(id: "user_id")]User $user = null)
    {
        if (!$user){
            $user = new User();
        }
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $formUser = $this->createForm(UpdateUserType::class,$user);

        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {

            $registerService->register($formUser->getData());

            $this->addFlash('success', 'Le compte de '. $formUser->getData()->getUserName()." a été modifié avec succès.");
            return $this->redirectToRoute('home');
        }

        return $this->render('user/update.html.twig', [
            'formUser' => $formUser->createView(),
            'user' => $user
        ]);

    }

    #[Route('/list-user', name: "app_list_user")]
    public function listUser(UserListService $userListService)
    {
        $this->denyAccessUnlessGranted(('ROLE_ADMIN'));
        $users = $userListService->list();

        return $this->render('user/list.html.twig', [
            'users' => $users
        ]);

    }
}
