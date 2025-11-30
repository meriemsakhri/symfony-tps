<?php

namespace App\DataFixtures;

use App\Entity\Candidature;
use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CandidatureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $candidatures = [
            [
                'candidat' => 'Karim Benali',
                'contenu' => 'Ingénieur cloud certifié Azure avec 6 ans d\'expérience.',
                'date' => '-7 days'
            ],
            [
                'candidat' => 'Léa Moreau',
                'contenu' => 'Spécialiste en cybersécurité, ancienne de l\'ANSSI.',
                'date' => '-2 days'
            ],
            [
                'candidat' => 'Samuel Costa',
                'contenu' => 'Développeur mobile avec 8 applications publiées sur les stores.',
                'date' => '-1 day'
            ]
        ];
        
        $jobs = $manager->getRepository(Job::class)->findAll();
        
        if (empty($jobs)) {
            throw new \Exception('No jobs found. Please load JobFixtures first.');
        }
        
        foreach ($candidatures as $index => $candData) {
            $candidature = new Candidature();
            $candidature->setCandidat($candData['candidat']);
            $candidature->setContenu($candData['contenu']);
            $candidature->setDate(new \DateTime($candData['date']));
            
            $jobIndex = $index % count($jobs);
            $candidature->setJob($jobs[$jobIndex]);
            
            $manager->persist($candidature);
        }
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [JobFixtures::class];
    }
}