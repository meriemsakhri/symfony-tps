<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Job;
use App\Entity\Image;
use App\Entity\Candidature;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\JobType;

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

    #[Route(path: "/Ajouter", name: "add_candidat")]
    public function ajouter_cand(Request $request, EntityManagerInterface $em): Response
    {
        $candidat = new Candidature();
        $fb = $this->createFormBuilder($candidat)
            ->add('candidat', TextType::class)
            ->add('contenu', TextType::class, options: array("label" => "Content"))
            ->add('date', DateType::class)
            ->add('job', EntityType::class, options: [
                'class' => Job::class,
                'choice_label' => 'type',
            ])
            ->add('Valider', SubmitType::class);
        $form = $fb->getForm();

        // Injection dans la base de données
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($candidat);
            $em->flush();
            
            // Add flash message
            $this->addFlash('notice', 'Candidat ajouté avec succès');
            
            return $this->redirectToRoute('home');
        }

        return $this->render('job/ajouter.html.twig', [
            'f' => $form->createView(),
            'is_editing' => false
        ]);
    }

    #[Route(path: "/add", name: "add_job")]
    public function ajouter2(Request $request, EntityManagerInterface $em): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('job/ajouter.html.twig', [
            'f' => $form->createView(),
            'is_editing' => false
        ]);
    }

    #[Route("/", name: "home")]
    public function home(EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(Candidature::class);
        $lesCandidats = $repo->findAll();
        
        return $this->render('job/home.html.twig',
            ['lesCandidats' => $lesCandidats]);
    }

    #[Route(path: "/supp/{id}", name: "cand_delete")]
    public function delete(Request $request, $id, EntityManagerInterface $entityManager): Response 
    {
        $c = $entityManager
            ->getRepository(Candidature::class)
            ->find($id);
            
        if (!$c) {
            throw $this->createNotFoundException(
                'No candidature found for id '.$id
            );
        }
        
        $entityManager->remove($c);
        $entityManager->flush();
        
        return $this->redirectToRoute('home');
    }

    #[Route('/edit/{id}', name: 'edit_user', methods: ['GET', 'POST'])]
    public function edit(Request $request, $id, EntityManagerInterface $em): Response
    {
        $candidat = $em->getRepository(Candidature::class)->find($id);
        
        if (!$candidat) {
            throw $this->createNotFoundException(
                'No candidat found for id '.$id
            );
        }
        
        $fb = $this->createFormBuilder($candidat)
            ->add('candidat', TextType::class)
            ->add('contenu', TextType::class, array("label" => "Content"))
            ->add('date', DateType::class)
            ->add('job', EntityType::class, [
                'class' => Job::class,
                'choice_label' => 'type',
            ])
            ->add('Valider', SubmitType::class);
            
        $form = $fb->getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $em->flush();
            return $this->redirectToRoute('home');
        }
        
        return $this->render('job/ajouter.html.twig', [
            'f' => $form->createView(),
            'is_editing' => true
        ]);
    }
}