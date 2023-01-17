<?php

namespace App\Controller;

use App\Repository\JeuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/jeu', name: 'app_jeu')]
class JeuController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(JeuRepository $jeuRepository): Response
    {
        $jeux = $jeuRepository->findJeuxWithLimitedNumber(3);

        return $this->render('jeu/index.html.twig', [
            'jeux' => $jeux
        ]);
    }

    #[Route('/liste', name: '_liste')]
    public function liste(JeuRepository $jeuRepository): Response
    {
        $jeux = $jeuRepository->findBy([],['nom' => 'ASC']);

        return $this->render('jeu/liste.html.twig', [
            'jeux' => $jeux
        ]);
    }

    #[Route('/voir/{id}', name: '_voir')]
    public function voir(JeuRepository $jeuRepository, int $id): Response
    {
        $jeu = $jeuRepository->find($id);

        return $this->render('jeu/voir.html.twig', [
            'jeu' => $jeu
        ]);
    }
}
