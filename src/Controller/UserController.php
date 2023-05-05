<?php

namespace App\Controller;

use App\Form\RegisterUserType;
use App\Service\User\RegisterService;
use App\Service\User\UserListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/inscription', name: 'app_registration')]
    public function registration(Request $request,  RegisterService $registerService)
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

    #[Route('/list-user', name : "app_list_user")]
    public function listUser(UserListService $userListService)
    {
        $this->denyAccessUnlessGranted(('ROLE_ADMIN'));
        $users = $userListService->list();

        return $this->render('user/list.html.twig',[
            'users' => $users
        ]);
        
    }
}
