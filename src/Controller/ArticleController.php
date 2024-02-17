<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {   
        $articles = $em->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }

    #[Route('/article/{id}', name: 'detail_article')]
    public function detail(Article $article = null, EntityManagerInterface $em, Request $request): Response
    {   
        if (!$authChecker->isGranted('ROLE_ADMIN')) { 
            return $this->redirectToRoute('app_article');
        }
        
        if ($article == null) {
            return $this->redirectToRoute(route : 'app_article');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // le formulaire est bon
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute(route : 'app_article');
        }
    
        return $this->render('article/detail.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    #[Route('/article-read/{titre}', name: 'read_article')]
    public function read(Article $article = null, EntityManagerInterface $em, Request $request): Response
    {   
        if (!$article) {
            return $this->redirectToRoute(route : 'app_article');
        }
    
        $auteur = $article->getAuteur();
    
        return $this->render('article/read.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article,
            'auteur' => $auteur
        ]);
    }
    
    

    #[Route("/create-article", name: 'create_article')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {   
        if (!$authChecker->isGranted('ROLE_ADMIN')) { 
            return $this->redirectToRoute('app_article');
        }

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // le formulaire est bon
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute(route : 'app_article');
        }

        return $this->render('article/create.html.twig', [
            'controller_name' => 'ArticleController',
            'form' => $form->createView(),
        ]);
    }

    #[Route("/delete/{id}", name: "delete_article")]
    public function remove(Article $article = null, EntityManagerInterface $em, Request $request): Response
    {   
        if (!$authChecker->isGranted('ROLE_ADMIN')) { 
            return $this->redirectToRoute('app_article');
        }

        if ($article == null) {
            return $this->redirectToRoute('app_article');
        }

        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('app_article');
    }
}
