<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Entity\Comment;
use App\Entity\DayPlanning;
use App\Entity\Image;
use App\Entity\Lesson;
use App\Entity\Planning;
use App\Entity\User;
use DateTime;
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
			->setCreationDate(new DateTime())
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

			$user = new User();
			$hashedPassword = $this->hasher->hashPassword($user, sprintf("user%d", $i));
			$user->setRoles(['ROLE_USER'])
				->setPassword($hashedPassword)
				->setEmail(sprintf("user%d", $i) . "@mail.com")
				->setUsername(sprintf("user%d", $i))
				->setCreationDate(new DateTime())
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
					->setCreationDate(new DateTime())
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
					if ($j === 0) {

						$image = new Image();
						$image->setAlt('Image 2 défaut article')
							->setName('Image 2  défaut article')
							->setUrl('/Image/MAJ.png')
							->setArticle($article);
						$manager->persist($image);
					}
				}

				for ($k = 0; $k < mt_rand(3, 10); $k++) {
					$comment = new Comment();
					$comment->setUser($users[mt_rand(0, count($users) - 1)])
						->setArticle($article)
						->setContent($faker->text())
						->setCreationDate(new DateTime())
						->setIsValidate(mt_rand(0, 1) === 1 ? true : false);
					$manager->persist($comment);
				}

				$manager->persist($article);

				$articles[] = $article;
			}

		}
		$plannings = [];

		$planningTrue = new Planning();
		$planningTrue->setTitle('Planning de test TRUE')
			->setIsValid(true)
			->setNumberOfDay(6)
			->setCreationDate(new DateTime())
			->setUser($users[0]);
		$manager->persist($planningTrue);
		$plannings[] = $planningTrue;

		$planningFalse = new Planning();
		$planningFalse->setTitle('Planning de test FALSE')
			->setIsValid(false)
			->setNumberOfDay(6)
			->setCreationDate(new DateTime())
			->setUser($users[0]);
		$manager->persist($planningFalse);
		$plannings[] = $planningFalse;

		$planningFourDays = new Planning();
		$planningFourDays->setTitle('Planning de test 4 jours')
			->setIsValid(true)
			->setNumberOfDay(4)
			->setCreationDate(new DateTime())
			->setUser($users[0]);
		$manager->persist($planningFourDays);

		$plannings[] = $planningFourDays;

		foreach ($plannings as $planning) {
			$numberOfDay = $planning->getNumberOfDay();
			for ($i = 0; $i < $numberOfDay; $i++) {
				$lessons = [];
				$dayPlanning = new DayPlanning();
				$dayPlanning->setPlanning($planning)
					->setName(sprintf('jour N°%d', $i + 1));

				for ($j = 0; $j < mt_rand(1, 8); $j++) {
					if ($j === 0) {
						$coherentTimeLesson = $this->getCoherentTimeLesson(null,true);
						$lesson = new Lesson();
						$lesson->setDay($dayPlanning)
							->setBegin($coherentTimeLesson['begin'])
							->setEnd($coherentTimeLesson['end'])
							->setTitle(sprintf('Jour :%d. Lesson N°%d', $i+1 ,$j + 1));

					} else {
						$coherentTimeLesson = $this->getCoherentTimeLesson($lessons[$j - 1]);
						$lesson = new Lesson();
						$lesson->setDay($dayPlanning)
							->setBegin($coherentTimeLesson['begin'])
							->setEnd($coherentTimeLesson['end'])
							->setTitle(sprintf('Jour :%d. Lesson N°%d', $i+1 ,$j + 1));
					}


					$manager->persist($lesson);
					$lessons[] = $lesson;
				}
				$manager->persist($dayPlanning);
			}
		}

		$manager->flush();
	}

	protected function getCoherentTimeLesson(Lesson $firstLesson = null, $first = false) {
		if ($first) {
			$begin = new DateTime();
			$begin->setTime(mt_rand(8, 10), $this->getRandomQuarterHour());
			$end = clone $begin;
			$end->modify('+1 hour');
			return ['begin' => $begin, 'end' => $end];
		}
		$begin = clone $firstLesson->getBegin();
		$begin->modify('+1 hour +15 minutes');
		$end = clone $begin;
		$end->modify('+1 hour');
		return ['begin' => $begin, 'end' => $end];
	}

	protected function getRandomQuarterHour() {
		$quarter = mt_rand(0, 3);
		switch ($quarter) {
			case 1:
				return 15;
			case 2:
				return 30;
			case 3:
				return 45;
			default:
				return 0;
		}
	}
}
