<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateUserForAdminType;
use App\Form\DeleteConfirmationType;
use App\Form\DeleteConfType;
use App\Form\RegisterUserType;
use App\Form\UpdateRoleType;
use App\Form\UpdateUserForAdminType;
use App\Form\UpdateUserType;
use App\Form\UploadImageType;
use App\Service\User\deleteUser;
use App\Service\User\RegisterService;
use App\Service\User\UpdateRoleService;
use App\Service\User\UpdateService;
use App\Service\User\UserFinderService;
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
    #[Route('/modif-utlisateur/{user}', name: "app_update_user")]
    public function createUser(Request         $request,
                               RegisterService $registerService,
                               User            $user = null)
    {
        $isGranted = true;
        if ($user !== $this->getUser()) {
            if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $isGranted = false;
            }
        }
        if (!$isGranted) {
            throw $this->createAccessDeniedException();
        }

        $editMode = false;
        if ($user) {
            $editMode = true;
        }

        $form = $this->createForm(CreateUserForAdminType::class, $user, [
            'withRoleSelector' => $this->isGranted('ROLE_ADMIN')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registerService->register($form->getData());

            $this->addFlash('success', 'Le compte de ' . $form->getData()->getUserName() . " à bien été crée.");
            return $this->redirectToRoute('app_list_user');
        }


        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
            'editMode' => $editMode
        ]);
    }

    #[Route('/list-user', name: "app_list_user")]
    public function listUser(UserFinderService $userFinderService)
    {
        $this->denyAccessUnlessGranted(('ROLE_ADMIN'));
        $users = $userFinderService->listAll();

        $formDeleteUser = $this->createForm(DeleteConfType::class);

        return $this->render('user/list.html.twig', [
            'users' => $users,
            'formDeleteUser' => $formDeleteUser->createView()
        ]);

    }

    #[Route('/utilisateur/{user_id}', name: "app_show_user")]
    public function showUser(UserFinderService $userFinder, int $user_id)
    {
        /** @var User $user */
        $user = $userFinder->findOneUser($user_id);

        $formDeleteUser = $this->createForm(DeleteConfType::class);
        $formImage = $this->createForm(UploadImageType::class);


        return $this->render('user/show.html.twig', [
            'user' => $user,
            'formImage' => $formImage->createView(),
            'formDeleteUser' => $formDeleteUser->createView()
        ]);
    }

    #[Route('/utilisateur_delete/{id}', name: 'delete_user')]
    public function deleteUser(Request $request, User $user, deleteUser $deleteUser)
    {
        if ($this->getUser() !== $user) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $form = $this->createForm(DeleteConfType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deleteUser->deleteUser($user);

            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $this->addFlash("info", "Le compte de {$user->getUsername()} a bien été supprimé.");
                return $this->redirectToRoute("app_list_user");
            }

            $this->addFlash("info", "Votre compte a bien été supprimé.");
            return $this->redirectToRoute("home");
        }

        return $this->render('user/delete.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

}
