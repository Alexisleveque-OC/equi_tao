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
use Faker\Generator;
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

		$planningCreatorOptions = [
			'planning_TRUE' => true,
			'planning_FALSE' => true,
			'planning_number_DAYS_strict' => 4,
			'other_planning' => 1,
			'default_number_of_days' => 6,
		];

		$lessonsOptions = [
			'lesson_min_nbr' => 1,
			'lesson_max_nbr' => 8,
			'lessons_duration' => '+1 hours',
			'pause_duration' => '+15 minutes',
			'day_begin_at_min' => 8,
			'day_begin_at_max' => 10,
		];

		$users = $this->createUsers($manager, 5);
		$categories = $this->createCategories($manager, 3);

		foreach ($categories as $category) {
			$articles = $this->createArticles($manager, $faker, $users[0], $category, 3);
		}

		foreach ($articles as $article) {
			$comments = $this->createComments($manager, $faker, $users, $article, 3, 10);
		}

		$plannings = $this->createPlannings($manager, $users[0], $planningCreatorOptions);

		foreach ($plannings as $planning) {
			$daysPlanning = $this->createDaysPlanning($manager, $planning);

			foreach ($daysPlanning as $dayPlanning) {
				$lessons = $this->createLessons($manager, $dayPlanning, $lessonsOptions);
			}
		}

		$manager->flush();
	}

	private function createUsers(ObjectManager $manager, int $nbrOfUsers = 5) {
		$image = $this->createUserImage($manager);

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

		for ($i = 1; $i <= $nbrOfUsers; $i++) {
			if ($i % 2 == 0) {
				$image = $this->createUserImage($manager);
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

		return $users;
	}

	private function createUserImage(ObjectManager $manager) {
		$image = new Image();
		$image->setAlt('Image d\'avatar inconnu')
			->setName('Avatar de inconnu')
			->setUrl('/Image/avatar_test.jpg');
		$manager->persist($image);
		return $image;
	}

	private function createArticleImage(ObjectManager $manager, Article $article, bool $secondImage = false) {
		$image = new Image();
		$image->setAlt('Image défaut article')
			->setName('Image défaut article')
			->setUrl('/Image/last.jpg')
			->setArticle($article);
		$manager->persist($image);
		if ($secondImage) {
			$image = new Image();
			$image->setAlt('Image 2 défaut article')
				->setName('Image 2  défaut article')
				->setUrl('/Image/MAJ.png')
				->setArticle($article);
			$manager->persist($image);
		}
	}

	private function createCategories(ObjectManager $manager, int $nbrOfCategories = 3) {
		$categories = [];
		for ($i = 0; $i < $nbrOfCategories; $i++) {

			$category = new ArticleCategory();
			$category->setName(sprintf("Categorie n°%d", $i))
				->setSlug($this->slugger->slug(sprintf("Categorie n°%d", $i)));

			$manager->persist($category);

			$categories[] = $category;
		}

		return $categories;
	}

	private function createArticles(ObjectManager   $manager,
									Generator       $faker,
									User            $admin,
									ArticleCategory $category,
									int             $nbrOfArticles = 3) {

		$articles = [];

		for ($j = 0; $j < $nbrOfArticles; $j++) {
			$article = new Article();
			$article->setTitle(sprintf("Titre de l'article %d", (($j + 1) + ($category->getId() * $nbrOfArticles))))
				->setContent($faker->text())
				->setUser($admin)
				->setCreationDate(new DateTime())
				->setCategory($category);

			$slug = $this->slugger->slug($article->getTitle());

			$article->setSlug($slug);

			if ($j === 0 || $j === 2) {
				$this->createArticleImage($manager, $article);
				if ($j === 0) {
					$this->createArticleImage($manager, $article, true);
				}
			}

			$manager->persist($article);

			$articles[] = $article;
		}
		return $articles;
	}

	private function createComments(ObjectManager $manager,
									Generator     $faker,
									array         $users,
									Article       $article,
									int           $min = 3,
									int           $max = 10) {

		$comments = [];

		for ($k = 0; $k < mt_rand($min, $max); $k++) {
			$comment = new Comment();
			$comment->setUser($users[mt_rand(0, count($users) - 1)])
				->setArticle($article)
				->setContent($faker->text())
				->setCreationDate(new DateTime())
				->setIsValidate(mt_rand(0, 1) === 1 ? true : false);

			$manager->persist($comment);
			$comments[] = $comment;
		}

		return $comments;
	}

	private function createPlannings(ObjectManager $manager, User $admin, array $planningCreatorOptions) {
		$plannings = [];

		if ($planningCreatorOptions['planning_TRUE']) {
			$planningTrue = new Planning();
			$planningTrue->setTitle('Planning de test TRUE')
				->setIsValid(true)
				->setNumberOfDay($planningCreatorOptions['default_number_of_days'])
				->setCreationDate(new DateTime())
				->setUser($admin);
			$manager->persist($planningTrue);
			$plannings[] = $planningTrue;
		}

		if ($planningCreatorOptions['planning_FALSE']) {
			$planningFalse = new Planning();
			$planningFalse->setTitle('Planning de test FALSE')
				->setIsValid(false)
				->setNumberOfDay($planningCreatorOptions['default_number_of_days'])
				->setCreationDate(new DateTime())
				->setUser($admin);
			$manager->persist($planningFalse);
			$plannings[] = $planningFalse;
		}

		if ($planningCreatorOptions['planning_number_DAYS_strict']) {
			$planningFourDays = new Planning();
			$planningFourDays->setTitle("Planning de test {$planningCreatorOptions['planning_number_DAYS_strict']} jours")
				->setIsValid(true)
				->setNumberOfDay($planningCreatorOptions['planning_number_DAYS_strict'])
				->setCreationDate(new DateTime())
				->setUser($admin);

			$manager->persist($planningFourDays);
			$plannings[] = $planningFourDays;
		}

		if ($planningCreatorOptions['other_planning']) {
			for ($i = 0; $i < $planningCreatorOptions['other_planning']; $i++) {
				$planning = new Planning();
				$planning->setTitle("Planning de test {$i}")
					->setIsValid(mt_rand(0, 1) === 1 ? true : false)
					->setNumberOfDay(mt_rand(1, 6))
					->setCreationDate(new DateTime())
					->setUser($admin);

				$manager->persist($planning);
				$plannings[] = $planning;
			}
		}

		return $plannings;
	}

	private function createDaysPlanning(ObjectManager $manager, Planning $planning) {

		$daysPlanning = [];
		for ($i = 0; $i < $planning->getNumberOfDay(); $i++) {
			$dayPlanning = new DayPlanning();
			$dayPlanning->setPlanning($planning)
				->setName(sprintf('Jour N°%d', $i + 1));
			$manager->persist($dayPlanning);
			$daysPlanning[] = $dayPlanning;
		}

		return $daysPlanning;
	}

	private function createLessons(ObjectManager $manager, DayPlanning $dayPlanning, array $lessonOptions) {

		if ($lessonOptions['lesson_max_nbr'] === 0){
			return [];
		}

		$lessons = [];
		for ($j = 0; $j < mt_rand($lessonOptions['lesson_min_nbr'], $lessonOptions['lesson_max_nbr']); $j++) {
			if ($j === 0) {
				$coherentTimeLesson = $this->getCoherentTimeLesson(null, $lessonOptions);
				$lesson = new Lesson();
				$lesson->setDay($dayPlanning)
					->setBegin($coherentTimeLesson['begin'])
					->setEnd($coherentTimeLesson['end'])
					->setTitle(sprintf('%s. Lesson N°%d', $dayPlanning->getName(), $j + 1));

			} else {
				$coherentTimeLesson = $this->getCoherentTimeLesson($lessons[$j - 1],$lessonOptions);
				$lesson = new Lesson();
				$lesson->setDay($dayPlanning)
					->setBegin($coherentTimeLesson['begin'])
					->setEnd($coherentTimeLesson['end'])
					->setTitle(sprintf('%s. Lesson N°%d', $dayPlanning->getName(), $j + 1));
			}

			$manager->persist($lesson);
			$lessons[] = $lesson;
		}

		return $lessons;
	}

	protected function getCoherentTimeLesson(Lesson $firstLesson = null, array $lessonOptions) {
		if (!$firstLesson) {
			$begin = new DateTime();
			$begin->setTime(
				mt_rand($lessonOptions['day_begin_at_min'], $lessonOptions['day_begin_at_max']),
				$this->getRandomQuarterHour());
			$end = clone $begin;
			$end->modify($lessonOptions['lessons_duration']);
			return ['begin' => $begin, 'end' => $end];
		}
		$begin = clone $firstLesson->getEnd();
		$begin->modify($lessonOptions['pause_duration']);
		$end = clone $begin;
		$end->modify($lessonOptions['lessons_duration']);
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
