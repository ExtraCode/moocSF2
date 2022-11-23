<?php

namespace App\DataFixtures;

use App\Entity\Jeu;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create();
        $genres = ["stratégie", "familiale", "ambiance", "coopératif"];

        for($i = 0; $i<10; $i++){

            $jeu = new Jeu();
            $jeu->setNom($faker->streetName);
            $jeu->setDateSortie($faker->dateTime);
            $jeu->setGenre($genres[rand(0,3)]);
            $jeu->setDescription($faker->text(50));
            $manager->persist($jeu);
        }

        $user = new User();
        $user->setEmail("user@gmail.com");
        $user->setNom($faker->name);
        $user->setPrenom($faker->firstname);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "123"));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $manager->flush();
    }
}
