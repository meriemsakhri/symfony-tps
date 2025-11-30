<?php

namespace App\DataFixtures;

use App\Entity\Job;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class JobFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $jobs = [
            [
                'type' => 'Ingénieur Cloud',
                'company' => 'Azure Solutions',
                'description' => 'Expert en infrastructure cloud Microsoft Azure.',
                'expires' => '+20 days',
                'email' => 'carriere@azuresolutions.com',
                'image_ref' => 'image_cloud'  
            ],
            [
                'type' => 'Cyber Security',
                'company' => 'SecureNet',
                'description' => 'Analyste en sécurité informatique. ',
                'expires' => '+35 days',
                'email' => 'recrutement@securenet.fr',
                'image_ref' => 'image_security'  
            ],
            [
                'type' => 'Mobile Developer',
                'company' => 'AppCraft',
                'description' => 'Développement d applications iOS et Android avec React Native.',
                'expires' => '+28 days',
                'email' => 'jobs@appcraft.io',
                'image_ref' => 'image_mobile'  
            ]
        ];

        foreach ($jobs as $index => $jobData) {
            $job = new Job();
            $job->setType($jobData['type']);
            $job->setCompany($jobData['company']);
            $job->setDescription($jobData['description']);
            $job->setExpiresAt(new \DateTimeImmutable($jobData['expires']));
            $job->setEmail($jobData['email']);
            $job->setImage($this->getReference($jobData['image_ref'], Image::class));
            $manager->persist($job);

            $this->addReference('job_' . $index, $job);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ImageFixtures::class,
        ];
    }
}