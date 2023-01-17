<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailService $mailService): Response{

        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(UserType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user->setPassword(
                $userPasswordHasher->hashPassword($user,$user->getPassword())
            );
            
            $entityManager->persist($user);
            $entityManager->flush();

            $mailService->sendMail("Vous êtes bien inscrit sur LocaJeu","Bienvenue sur le site, patati patata");

            $this->addFlash(
                'success',
                'Vous êtes bien inscrit sur LocaJeu !'
            );

            return $this->redirectToRoute('app_login');

        }

        return $this->renderForm('registration/index.html.twig', [
            'form' => $form
        ]);

    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
