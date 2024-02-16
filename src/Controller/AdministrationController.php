<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdministrationController extends AbstractController
{
    #[Route('/administration', name: 'app_administration')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {   
        $users = $em->getRepository(User::class)->findAll();
        $categories = $em->getRepository(Categorie::class)->findAll();

        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
            'users' => $users,
            'categories' => $categories,
        ]);
    }
}
