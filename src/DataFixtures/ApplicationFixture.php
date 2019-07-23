<?php

namespace App\DataFixtures;

use App\Entity\Application;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ApplicationFixture extends Fixture implements DependentFixtureInterface
{
    private $faker;

    private $authors = [];

    private $managers = [];

    private $statuses = [];

    public function __construct()
    {

        $this->faker = Factory::create();

    }

    public function load(ObjectManager $manager)
    {
        $this->getAuthors();
        $this->getManagers();
        $this->getStatuses();

        for($i = 0; $i < 20; $i++) {
            $application = new Application();
            $application->setName($this->faker->name);
            $application->setAddress($this->faker->address);
            $application->setCreatedAt($this->faker->dateTimeBetween('-5 years', '-1 years'));
            $application->setUpdatedAt($this->faker->dateTimeBetween('-1 years', 'now'));
            $application->setAuthor($this->getRandomAuthor());
            $application->setStatus($this->getRandomStatus());
            if($this->faker->boolean(50)) {
                $application->setManager($this->getRandomManager());
            }

            $manager->persist($application);

            $this->addReference(
                sprintf(
                    'application_%d',
                    $i
                ),
                $application
            );
        }

        $manager->flush();
    }

    private function getRandomAuthor() {

        return $this->getReference($this->faker->randomElement($this->authors));
    }

    private function getRandomManager() {

        return $this->getReference($this->faker->randomElement($this->managers));
    }

    private function getRandomStatus() {

        return $this->getReference($this->faker->randomElement($this->statuses));
    }

    private function getAuthors() {

        $this->setSpecificReferences('author_', $this->authors);
    }

    private function getManagers() {
        $this->setSpecificReferences('manager_', $this->managers);
    }

    private function getStatuses() {
        $this->setSpecificReferences('status_', $this->statuses);
    }

    private function setSpecificReferences(string $prefix, &$link) {

        $references = $this->referenceRepository->getReferences();

        foreach ($references as $key => $reference) {

            if(strpos($key, $prefix) === 0) {
                $link[] = $key;
            }
        }

    }

    public function getDependencies()
    {
        return [
            UserFixture::class
        ];
    }
}
