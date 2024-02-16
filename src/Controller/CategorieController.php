<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $categories = $em->getRepository(Categorie::class)->findAll();
        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute(route : 'app_categorie');
        }

        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    #[Route('/categorie/{id}', name: 'detail_categorie')]
    public function detail($id, EntityManagerInterface $em): Response
    {
        $categorie = $em->getRepository(Categorie::class)->find($id);
    
        return $this->render('categorie/detail.html.twig', [
            'controller_name' => 'CategorieController',
            'categorie' => $categorie,
        ]);
    }

    #[Route("/createCategorie", name: 'create_categorie')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {   
        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // le formulaire est bon
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute(route : 'app_categorie');
        }

        return $this->render('categorie/create.html.twig', [
            'controller_name' => 'CategorieController',
            'form' => $form->createView(),
        ]);
    }
}
