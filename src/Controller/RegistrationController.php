<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function registration(
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $user->getPlainPassword();
            if ($plainPassword) {
                $hashedPassword = $hasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('show', ['id' => $user->getId()]);
        }

        return $this->render('registration/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/show/{id}", name: "show")]
    public function show(User $user): Response
    {
        return $this->render('registration/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        
        if (!$lastUsername) {
            $lastUsername = '';
        }

        return $this->render('registration/login.html.twig', [
            'controller_name' => 'LoginController',
            'email' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route("/logout", name: "app_logout")]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_login');
    }
}