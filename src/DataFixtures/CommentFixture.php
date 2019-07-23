<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixture extends Fixture implements DependentFixtureInterface
{
    private $faker;

    private $authors = [];

    private $applications = [];

    public function __construct()
    {

        $this->faker = Factory::create();

    }

    public function load(ObjectManager $manager)
    {
        $this->setAuthors();
        $this->setApplications();

        for($i = 0; $i < random_int(24, 109); $i++) {

            $comment = new Comment();
            $comment->setText($this->faker->realText(random_int(50, 255), 5));
            $comment->setAuthor($this->getRandomAuthor());
            $comment->setApplication($this->getRandomApplication());

            $manager->persist($comment);
        }

        $manager->flush();
    }

    private function getRandomAuthor(): User
    {

        return $this->getReference($this->faker->randomElement($this->authors));
    }

    private function getRandomApplication(): Application
    {
        return $this->getReference($this->faker->randomElement($this->applications));
    }

    private function setAuthors() {

        $references = $this->referenceRepository->getReferences();

        foreach ($references as $key => $reference) {

            if(strpos($key, 'manager_') === 0) {
                $this->authors[] = $key;
            }
        }
    }

    private function setApplications() {

        $references = $this->referenceRepository->getReferences();

        foreach ($references as $key => $reference) {

            if(strpos($key, 'application_') === 0) {
                $this->applications[] = $key;
            }
        }
    }

    public function getDependencies()
    {
        return [
            ApplicationFixture::class
        ];
    }
}
