<?php

namespace App\Controller\Me;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserpageController extends AbstractController
{
    #[Route('/me', name: 'app_me')]
    public function index(): Response
    {
        return $this->render('me/userpage/index.html.twig', [
            'controller_name' => 'UserpageController',
        ]);
    }
}
