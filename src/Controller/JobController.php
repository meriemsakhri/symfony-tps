<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Job;
use App\Entity\Image;
use App\Entity\Candidature;

class JobController extends AbstractController
{
    #[Route(path: '/job', name: 'app_job')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $job = new Job();
        $job->setType('Développeur');
        $job->setCompany('SOTORIPOP');
        $job->setDescription('LARAVEL');
        $job->setExpiresAt(new \DateTimeImmutable());
        $job->setEmail('haykel@gmail.com');
        $image = new Image();
        $image->setUrl('https://cdn.pixabay.com/photo/2015/10/30/10/03/gold-1013618_960_720.jpg');
        $image->setAlt("Job Image");

        $job->setImage($image);

        // === PERSIST JOB AND IMAGE FIRST === //
        $entityManager->persist($job);
        $entityManager->persist($image);

        // Creation des candidats
        $candidature1 = new Candidature();
        $candidature2 = new Candidature();

        // Remplir candidat 1
        $candidature1->setCandidat("Rhaiem");
        $candidature1->setContenu("Formation J2EE");
        $candidature1->setDate(new \DateTime());

        // Remplir candidat 2  
        $candidature2->setCandidat("Salima");
        $candidature2->setContenu("Formation Symfony");
        $candidature2->setDate(new \DateTime());

        // Affecter un Job Aux candidats
        $candidature1->setJob($job);
        $candidature2->setJob($job);

        $entityManager->persist($candidature1);
        $entityManager->persist($candidature2);

        $entityManager->flush();

        return $this->render('job/index.html.twig', [
            'id' => $job->getId(),
        ]);
    }

    #[Route('/job/{id}', name: 'job_show')]
    public function show(EntityManagerInterface $entityManager, $id)
    {
        $job = $entityManager->getRepository(Job::class)->find($id);
        
        // Consulter les candidats - FIXED: use 'Job' not 'job'
        $listCandidatures = $entityManager->getRepository(Candidature::class)
            ->findBy(['Job' => $job]);

        if (!$job) {
            throw $this->createNotFoundException(
                'No job found for id '.$id
            );
        }
        
        return $this->render('job/show.html.twig', [
            'job' => $job,
            'listCandidatures' => $listCandidatures,
        ]);
    }
}