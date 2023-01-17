<?php

namespace App\Controller\Admin;

use App\Entity\Jeu;
use App\Form\JeuType;
use App\Repository\JeuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin/jeu', name: 'app_admin_jeu')]
class JeuController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(JeuRepository $jeuRepository): Response
    {

        $jeux = $jeuRepository->findBy([],['nom' => 'asc']);

        return $this->render('admin/jeu/index.html.twig', [
            'jeux' => $jeux,
        ]);
    }

    #[Route('/ajouter', name: '_ajouter')]
    #[Route('/modifer/{id}', name: '_modifier')]
    public function editer(Request $request, JeuRepository $jeuRepository, EntityManagerInterface $entityManager, int $id = null): Response
    {

        if($request->attributes->get('_route') == 'app_admin_jeu_ajouter'){
            $jeu = new Jeu();
        }else{
            $jeu = $jeuRepository->find($id);
        }

        $form = $this->createForm(JeuType::class,$jeu);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($jeu);
            $entityManager->flush();

            // Gestion du message de confirmation
            if($request->attributes->get('_route') == 'app_admin_jeu_ajouter') {

                $this->addFlash(
                    'success',
                    "Le nouveau jeu a bien été ajouté !"
                );

            }else{

                $this->addFlash(
                    'success',
                    "Le jeu a bien été modifié !"
                );
            }

            return $this->redirectToRoute('app_admin_jeu');

        }

        return $this->renderForm('admin/jeu/editer.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function supprimer(JeuRepository $jeuRepository, EntityManagerInterface $entityManager, int $id): Response
    {

        $jeu = $jeuRepository->find($id);
        $entityManager->remove($jeu);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Le jeu a bien été supprimé !"
        );

        return $this->redirectToRoute('app_admin_jeu');

    }
}
