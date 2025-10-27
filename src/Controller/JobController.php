<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Job;
use App\Entity\Image;

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

        $entityManager->persist($image);
        $entityManager->persist($job);
        $entityManager->flush();

        return $this->render('job/index.html.twig', [
            'id' => $job->getId(),
        ]);
    }

    #[Route('/job/{id}', name: 'job_show')]
    public function show(EntityManagerInterface $entityManager, $id)
    {
        $job = $entityManager->getRepository(Job::class)->find($id);
        return $this->render('job/show.html.twig', [
            'job' => $job
        ]);
    }
}