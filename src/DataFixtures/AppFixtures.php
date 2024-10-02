<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\CategoryFactory;
use App\Factory\CommentFactory;
use App\Factory\MediaFactory;
use App\Factory\TrickFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function Zenstruck\Foundry\faker;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;

            $this->isTestEnvironment = false;

    }

    public function load(ObjectManager $manager): void
    {
        $users = UserFactory::createMany(15, function () {

            return [
                'password' => $this->isTestEnvironment ? 'password' : $this->passwordHasher->hashPassword(new User(), 'password')
            ];
        });
        $categories = CategoryFactory::createMany(5);
        $tricks = TrickFactory::createMany(40, function () use ($categories, $users) {
            return [

                'user' => faker()->randomElement($users),
                'category' => faker()->randomElement($categories),
            ];
        });
        CommentFactory::createMany(150, function () use ($tricks, $users) {
            return [
                'user' => faker()->randomElement($users),
                'trick' => faker()->randomElement($tricks),
            ];
        });
        MediaFactory::createMany(20, function () use ($tricks) {
            return [
                'trick' => faker()->randomElement($tricks),
            ];
        });

        $manager->flush();
    }
}
