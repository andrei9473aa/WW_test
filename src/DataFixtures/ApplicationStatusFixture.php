<?php

namespace App\DataFixtures;

use App\Entity\ApplicationStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ApplicationStatusFixture extends Fixture
{
    private $statuses = [
        0 => 'new',
        1 => 'in progress',
        2 => 'closed'
    ];

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for($i = 0; $i <= 2; $i++) {

            $status = new ApplicationStatus();
            $status->setName($faker->randomElement($this->statuses));

            $manager->persist($status);

            $this->addReference(
                sprintf('status_%d', $i),
                $status
            );
        }

        $manager->flush();
    }
}
