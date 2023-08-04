<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /*
    * @var UserPasswordHasherInterface
    */
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $image = new Image();
        $image->setAlt('Image d\'avatar inconnu')
            ->setName('Avatar de inconnu')
            ->setUrl('/Image/avatar_test.png');
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
                    ->setUrl('/Image/avatar_test.png');
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


        $manager->flush();
    }
}
