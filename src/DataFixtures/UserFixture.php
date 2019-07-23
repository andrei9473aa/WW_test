<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;

        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {

        $moderator = new User();
        $moderator->setRoles(['ROLE_MODERATOR']);
        $moderator->setEmail($this->faker->email);
        $moderator->setPassword($this->passwordEncoder->encodePassword(
            $moderator,
            'engage'
        ));

        $manager->persist($moderator);


        for($i = 0; $i < 10; $i++) {

            $applicationManager = new User();
            $applicationManager->setRoles(['ROLE_MANAGER']);
            $applicationManager->setEmail($this->faker->email);
            $applicationManager->setPassword($this->passwordEncoder->encodePassword(
                $applicationManager,
                'engage'
            ));

            $manager->persist($applicationManager);
            $this->addReference(
                sprintf('manager_%d', $i),
                $applicationManager
            );
        }


        for($i = 0; $i < 15; $i++) {

            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'engage'
            ));

            $manager->persist($user);
            $this->addReference(
                sprintf('author_%d', $i),
                $user
            );
        }


        $manager->flush();
    }
}
