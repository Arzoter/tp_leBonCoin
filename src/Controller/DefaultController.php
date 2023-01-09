<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Categorie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home_page')]
    public function home(ManagerRegistry $doctrine): Response
    {
        $annonces = $doctrine->getRepository(Annonce::class)->findAll();

        return $this->render('home.html.twig', [
            'annoncesAll' => $annonces
        ]);
    }


    #[Route('/cat_new', name: 'create_cat')]
    public function createCat(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        
        $cat = new Categorie();
        $cat->setTitle('cat-test:title2');

        $entityManager->persist($cat);
        $entityManager->flush();

        return new Response('created the new cat.');
    }
}
