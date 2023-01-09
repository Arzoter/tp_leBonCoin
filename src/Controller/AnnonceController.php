<?php

namespace App\Controller;

use DateTime;
use App\Entity\Annonce;
use App\Form\AnnonceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
    #[Route('/annonce/{id}', name: 'annonce_single')]
    public function annonce_single(ManagerRegistry $doctrine, int $id): Response
    {
        $repository = $doctrine->getRepository(Annonce::class);
        $annonce = $repository->find($id);

        return $this->render('annonce.html.twig', [
            'annonce' => $annonce
        ]);
    }



    #[Route('/add', name: 'add')]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $annonce = new Annonce();
        $entityManager = $doctrine->getManager();

        $form = $this->createForm(AnnonceType::class, $annonce);

        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $data = $annonce->setCreatedAt(new DateTime());
            $data = $annonce->setUpdatedAt(new DateTime());
            $data = $annonce->setUser($this->getUser());
            $entityManager->persist($data);
            $entityManager->flush();

            $this->addFlash(
                'message',
                'Votre annonce a bien été publiée.'
            );

            return $this->redirectToRoute('home_page');
        }

        return $this->render('add.html.twig', [
            'formAnnonce' => $form
        ]);
    }


    #[Route('/edit/{id}', name: 'edit_annonce')]
    public function edit(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $annonce = $entityManager->getRepository(Annonce::class)->find($id);

        $form = $this->createForm(AnnonceType::class, $annonce);

        $form->handleRequest($request);

        if($annonce->getUser()->getId() != $this->getUser()->getId()){
            throw new Exception('Vous n`avez pas accès a cette annonce');
        }

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            //$data = $annonce->setUpdatedAt(new DateTime());
            $entityManager->persist($data);
            $entityManager->flush();
            
            return $this->redirectToRoute('annonce_single',[
                "id" => $id
        ]);
        }

        return $this->render('add.html.twig', [
            'formAnnonce' => $form,
            //'createdAt' => $annonce->getCreatedAt()
        ]);
    }


    #[Route('/delete/{id}', name: 'delete_annonce')]
    public function delete(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $annonce = $entityManager->getRepository(Annonce::class)->find($id);

        
        if($annonce->getUser()->getId() != $this->getUser()->getId()){
            throw new Exception('Vous n`avez pas accès a cette annonce');
        }

        
        $entityManager->remove($annonce);
        $entityManager->flush();
            
        return $this->redirectToRoute('home_page');
    }


    #[Route('/annonce_new', name: 'create_annonce')]
    public function createAnnonce(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        
        $annonce = new Annonce();
        $annonce->updatedTimestamps();
        $annonce->setTitle('annonce-test:title');
        $annonce->setDescription('annonce-test:desc');
        $annonce->setPrice('annonce-test:price');

        $entityManager->persist($annonce);
        $entityManager->flush();

        return new Response('created the new cat.');
    }



    #[Route('/annonce_delete/{id}', name: 'delete_annonce')]
    public function deleteAnnonce(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $annonce = $entityManager->getRepository(Annonce::class)->find($id);
        
        if(!$annonce) {
            throw $this->createNotFoundException("Pas d'annonce avec l'id n°" . $id . "...");
        }

        $entityManager->remove($annonce);
        $entityManager->flush();

        $this->addFlash(
            'message',
            'Votre annonce a bien été supprimée.'
        );

        return $this->redirectToRoute('home_page');
    }
}
