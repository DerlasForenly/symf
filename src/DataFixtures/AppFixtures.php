<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(protected UserPasswordHasherInterface $passwordEncoder)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setNickname('admin');
        $user->setEmail('admin@admin.com');
        $user->setPassword($this->passwordEncoder->hashPassword($user, 'admin'));
        $manager->persist($user);

        $manager->flush();
    }
}
