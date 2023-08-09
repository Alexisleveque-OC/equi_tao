<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UploadImageType;
use App\Service\Image\UploadImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    #[Route('/upload/image_user/{user}', name: 'upload_image_user')]
    public function uploadImageUser(Request $request, User $user, UploadImage $uploadImage): Response
    {
        /* TODO : make voter */
//        $imageDirectory = $this->getParameter('image_directory');
        if (!$user){
            $this->addFlash('info', "Vous n'avez pas séléctionner d'utilisateur ou celui-ci n'existe pas.");
            return $this->redirectToRoute('home');
        }
        $form = $this->createForm(UploadImageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $uploadImage->saveImageOnUser($user, $form->get('image')->getData());
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'formImage' => $form,
        ]);
    }
}
