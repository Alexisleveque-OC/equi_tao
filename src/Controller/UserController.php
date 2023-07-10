<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use App\Form\UpdateRoleType;
use App\Form\UpdateUserForAdminType;
use App\Form\UpdateUserType;
use App\Repository\UserRepository;
use App\Service\User\RegisterService;
use App\Service\User\UpdateRoleService;
use App\Service\User\UpdateService;
use App\Service\User\UserFinderService;
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
    public function createUser(Request         $request,
                               RegisterService $registerService)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(UpdateUserForAdminType::class);

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

    #[Route('/modifier-utilisateur/{user_id}', name: "app_update_user")]
    public function updateUser(Request         $request,
                               UpdateService $updateService,
                               UserFinderService $userFinder,
                               int $user_id)
    {

        $user = $userFinder->findOneUser($user_id);

        if (!$user){
            $this->addFlash('info', "Vous n'avez pas séléctionner d'utilisateur ou celui-ci n'existe pas.");
            return $this->redirectToRoute('home');
        }

        if ($user !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(UpdateUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updateService->update($form->getData(), $user);

            $this->addFlash('success', 'Votre compte à bien été modifié.');
            return $this->redirectToRoute('home');
        }


        return $this->render('user/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/modifier-role/{user_id}', name: "app_update_user_role")]
    public function updateRole(Request $request,
                               UpdateRoleService $updateRoleService,
                               UserFinderService $userFinder,
                               int $user_id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $userFinder->findOneUser($user_id);

        if (!$user){
            $this->addFlash('info', "Vous n'avez pas séléctionner d'utilisateur ou celui-ci n'existe pas.");
            return $this->redirectToRoute('home');
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
    public function listUser(UserFinderService $userFinderService)
    {
        $this->denyAccessUnlessGranted(('ROLE_ADMIN'));
        $users = $userFinderService->listAll();

        return $this->render('user/list.html.twig', [
            'users' => $users
        ]);

    }

    #[Route('/utilisateur/{user_id}', name: "app_show_user")]
    public function showUser(UserFinderService $userFinder, int $user_id)
    {
//        $this->denyAccessUnlessGranted(('ROLE_ADMIN'));

        /** @var User $user */
        $user = $userFinder->findOneUser($user_id);
//dd($this->getUser()->getRoles());
        if ($user->getId() != $this->getUser()->getUserIdentifier() || $this->getUser()->getRoles() != 'ROLE_ADMIN'){

        }

        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);

    }
}
