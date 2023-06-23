<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use App\Form\UpdateRoleType;
use App\Form\UpdateUserType;
use App\Repository\UserRepository;
use App\Service\User\RegisterService;
use App\Service\User\UpdateRoleService;
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
    public function updateUser(Request         $request,
                               RegisterService $registerService)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(UpdateUserType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $registerService->register($form->getData());

            $this->addFlash('success', 'Le compte de ' . $form->getData()->getUserName() . " à bien été crée.");
            return $this->redirectToRoute('app_list_user');
        }


        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/modifier-role/{user_id}', name: "app_update_user_role")]
    public function updateRole(Request $request,
                               UpdateRoleService $updateRoleService,
                               UserRepository $userRepository,
                               int $user_id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $userRepository->findOneById($user_id);

        if (!$user){
            $this->addFlash('info', "Vous n'avez pas séléctionner d'utilisateur ou celui-ci n'existe pas.");
            return $this->redirectToRoute('app_list_user');
        }

        $form = $this->createForm(UpdateRoleType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $updateRoleService->updateRole($form->getData());
            $this->addFlash('success', 'Le compte de ' . $form->getData()->getUserName() . " à bien été modifié.");
            return $this->redirectToRoute('app_list_user');
        }

        return $this->render('user/update_role.html.twig', [
            'form' => $form,
            'user' => $user,
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
