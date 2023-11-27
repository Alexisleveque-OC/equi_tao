<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UploadImageUserType;
use App\Service\Image\SaveImage;
use App\Service\Image\UploadImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    #[Route('/upload/image_user/{user}', name: 'upload_image_user')]
    public function uploadImageUser(Request $request, User $user,
                                    UploadImage $uploadImage,
                                    SaveImage $saveImageOnUser
    ): Response
    {
        /* TODO : make voter */
//        $imageDirectory = $this->getParameter('image_directory');
        if (!$user){
            $this->addFlash('info', "Vous n'avez pas séléctionner d'utilisateur ou celui-ci n'existe pas.");
            return $this->redirectToRoute('home');
        }

        if ($user !== $this->getUser()){
            $this->addFlash("danger", "Vous n'avez pas l'autorisation de changer l'image de profil d'un autre utilisateur.");
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(UploadImageUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $imageSaved = $uploadImage->saveImageOnServer($form->get('image')->getData());
            $saveImageOnUser->saveImageOnUser($user, $imageSaved);
            $this->addFlash('success', "Votre image a bien été modifié.");
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'formImage' => $form,
        ]);
    }
}
