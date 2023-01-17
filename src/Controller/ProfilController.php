<?php

namespace App\Controller;

use App\Form\UserPasswordType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/profil', name: 'app_profil')]
class ProfilController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    #[Route('/changepassword', name: '_changepassword')]
    public function changePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailService $mailService): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(UserPasswordType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // Si le mot de passe actuel n'est pas bon
            if(!$userPasswordHasher->isPasswordValid($user,$form->get('current_password')->getData())){

                $this->addFlash(
                    'danger',
                    "Votre mot de passe actuel n'est pas bon !"
                );

                return $this->redirectToRoute('app_profil_changepassword');

            }

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $mailService->sendMail("Votre mot de passe a bien été modifié","Vous venez de modifier votre mdp sur LocaJeu, si ce n'est pas vous ...");

            $this->addFlash(
                'success',
                'Votre mot de passe a bien été modifié'
            );

            return $this->redirectToRoute('app_profil');

        }

        return $this->renderForm('profil/change_password.html.twig', [
            'form' => $form
        ]);
    }
}
