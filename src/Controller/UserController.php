<?php

namespace App\Controller;

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

    #[Route('/creer-utlisateur', name: "app_create_user")]
    #[Route('/modifier-utlisateur/{user_id}', name: "app_update_user")]
    public function updateUser(Request $request,
                               RegisterService $registerService,
                               #[MapEntity(id: "user_id")] $id = null)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(UpdateUserType::class);
//        dd($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $registerService->register($form->getData());

            $this->addFlash('success', 'Le compte de '. $form->getData()->getUserName()." à bien été crée.");
            return $this->redirectToRoute('home');
        }


        return $this->render('user/update.html.twig', [
            'form' => $form->createView()
        ]);


//        $this->addFlash('danger', "Vous n'êtes pas autorisé à voir cette page");
//        return $this->redirectToRoute('home');
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
