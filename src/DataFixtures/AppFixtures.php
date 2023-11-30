<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class AppFixtures extends Fixture
{
	/*
	* @var UserPasswordHasherInterface
	*/
	private $hasher;

	#[Required]
	public SluggerInterface $slugger;

	public function __construct(UserPasswordHasherInterface $hasher) {
		$this->hasher = $hasher;
	}

	public function load(ObjectManager $manager): void {
		$faker = Factory::create('fr_FR');

		$image = new Image();
		$image->setAlt('Image d\'avatar inconnu')
			->setName('Avatar de inconnu')
			->setUrl('/Image/avatar_test.jpg');
		$manager->persist($image);

		$users = [];

		$admin = new User();
		$hashedPassword = $this->hasher->hashPassword($admin, "admin");
		$admin->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
			->setPassword($hashedPassword)
			->setEmail('admin@mail.com')
			->setUsername('admin')
			->setCreationDate(new \DateTime())
			->setImage($image);
		$users[] = $admin;
		$manager->persist($admin);

		for ($i = 1; $i <= 5; $i++) {
			if ($i % 2 == 0) {

				$image = new Image();
				$image->setAlt('Image d\'avatar inconnu')
					->setName('Avatar de inconnu')
					->setUrl('/Image/avatar_test.jpg');
				$manager->persist($image);
			}

			$users = [];
			$user = new User();
			$hashedPassword = $this->hasher->hashPassword($user, sprintf("user%d", $i));
			$user->setRoles(['ROLE_USER'])
				->setPassword($hashedPassword)
				->setEmail(sprintf("user%d", $i) . "@mail.com")
				->setUsername(sprintf("user%d", $i))
				->setCreationDate(new \DateTime())
				->setImage($image);

			$manager->persist($user);

			$users[] = $user;
		}

		for ($i = 0; $i < 3; $i++) {

			$categories = [];
			$category = new ArticleCategory();
			$category->setName(sprintf("Categorie n°%d", $i))
				->setSlug($this->slugger->slug(sprintf("Categorie n°%d", $i)));

			$manager->persist($category);

			$categories[] = $category;

			for ($j = 0; $j < 3; $j++) {
				$articles = [];
				$article = new Article();
				$article->setTitle(sprintf("Titre de l'article %d", (($j + 1) + ($i * 5))))
					->setContent($faker->text())
					->setUser($users[0])
					->setCreationDate(new \DateTime())
					->setCategory($category);

				$slug = $this->slugger->slug($article->getTitle());

				$article->setSlug($slug);

				if ($j === 0 || $j === 2) {
					$image = new Image();
					$image->setAlt('Image défaut article')
						->setName('Image défaut article')
						->setUrl('/Image/last.jpg')
						->setArticle($article);
					$manager->persist($image);
					if ($j === 0){

						$image = new Image();
						$image->setAlt('Image 2 défaut article')
							->setName('Image 2  défaut article')
							->setUrl('/Image/MAJ.png')
							->setArticle($article);
						$manager->persist($image);
					}
				}

				$manager->persist($article);

				$articles[] = $article;
			}

		}


		$manager->flush();
	}
}
