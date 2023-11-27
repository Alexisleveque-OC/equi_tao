<?php

namespace App\Service\Image;

use App\Entity\Image;
use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadImage
{
	private string $imageDirectory;
	private SluggerInterface $slugger;

	public function __construct(SluggerInterface $slugger, string $imageDirectory) {
		$this->imageDirectory = $imageDirectory;
		$this->slugger = $slugger;
	}

	/**
	 * @throws Exception
	 */
	public function saveImageOnServer(Image $image) {

		if ($image->getFile() instanceof UploadedFile) {
			/** @var File $file */
			$file = $image->getFile();
			$originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
			$safeFilename = $this->slugger->slug($originalFileName);
			$newFileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

			try {
				$file = $file->move($this->imageDirectory, $newFileName);
			} catch (FileException $e) {
				throw new Exception("Le fichier n'a pas pus être enregistré. Liste d'erreur : $e");
			}
			$image->setName($newFileName);
		}
		return $image;

	}

}