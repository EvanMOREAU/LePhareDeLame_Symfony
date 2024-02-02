<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Utilisateur Dev
        $superAdmin = new User();
        $superAdmin->setEmail('evan.moreau@etik.com');
        $plaintextPassword = 'admin';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $superAdmin,
            $plaintextPassword
        );
        $superAdmin->setPassword($hashedPassword);
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN']);

        // Vous pouvez définir d'autres champs ici, par exemple :
        $superAdmin->setPhoneNumber("0638344893");
        $superAdmin->setFirstName("Evan");
        $superAdmin->setLastName("Moreau");

        $manager->persist($superAdmin);

        for ($i = 1; $i <= 15; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@example.com");
            $plaintextPassword = 'admin';
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);

            $user->setPhoneNumber("0000000000");
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
