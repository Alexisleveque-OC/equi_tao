<?php

namespace App\Controller;

use App\Form\RegisterUserType;
use App\Service\User\RegisterService;
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

            $this->addFlash('success', 'Vous avez été enregistré, vérifiez vos email pour valider votre compte !');
            return $this->redirectToRoute('home');
        }

        return $this->render('security/registration.html.twig', [
            'formUser' => $form->createView()
        ]);
    }
}
