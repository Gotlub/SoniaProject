<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;


    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        
        $user = new User();
        $user->setUsername("test")
            ->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword(
            $user,
            'password'
        );
        
        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();
    }
}
