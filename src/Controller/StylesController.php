<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StylesController extends AbstractController
{
    #[Route('/styles', name: 'app_styles')]
    public function index(): Response
    {
        return $this->render('styles/index.html.twig', [
            'controller_name' => 'StylesController',
        ]);
    }
}
