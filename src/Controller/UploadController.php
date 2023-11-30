<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Form\UploadImageType;
use App\Form\UploadImageUserType;
use App\Service\Image\SaveImage;
use App\Service\Image\UploadImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
	/**
	 * @throws \Exception
	 */
	#[Route('/upload/image_user/{user}', name: 'upload_image_user')]
    public function uploadImageUser(Request $request, User $user,
                                    UploadImage $uploadImage,
                                    SaveImage $saveImageOnUser
    ): Response
    {
        /* TODO : make voter */
        if (!$user){
            $this->addFlash('info', "Vous n'avez pas sélectionner d'utilisateur ou celui-ci n'existe pas.");
            return $this->redirectToRoute('home');
        }

        if ($user !== $this->getUser()){
            $this->addFlash("danger", "Vous n'avez pas l'autorisation de changer l'image de profil d'un autre utilisateur.");
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(UploadImageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $imageSaved = $uploadImage->saveImageOnServer($form->getData());
            $saveImageOnUser->saveImageOnUser($user, $imageSaved);
            $this->addFlash('success', "Votre image a bien été modifié.");
        }

        return $this->redirectToRoute('app_show_user', [
            'user_id' => $user->getId()
        ]);
    }
}
