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
    #[Route('/', name: 'app_categorie')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $categories = $em->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $categories,
        ]);
    }

    #[Route('/categorie/{id}', name: 'detail_categorie')]
    public function detail(Categorie $categorie = null, EntityManagerInterface $em, Request $request): Response
    {   
        if ($categorie == null) {
            return $this->redirectToRoute(route : 'app_categorie');
        }

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // le formulaire est bon
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute(route : 'app_categorie');
        }
    
        return $this->render('categorie/detail.html.twig', [
            'controller_name' => 'CategorieController',
            'categorie' => $categorie,
            'form' => $form->createView()
        ]);
    }

    #[Route("/create", name: 'create_categorie')]
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


    #[Route("/delete/{id}", name: "delete_categorie")]
    public function remove(Categorie $categorie = null, EntityManagerInterface $em, Request $request): Response
    {
        if ($categorie == null) {
            return $this->redirectToRoute('app_categorie');
        }

        $em->remove($categorie);
        $em->flush();

        return $this->redirectToRoute('app_categorie');
    }
}
