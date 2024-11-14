<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Employee;

class EmployeController extends AbstractController
{
    #[Route('/equipe', name: 'equipe')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Get all employees with manager
        $team = $entityManager->getRepository(Employee::class)->findAll();

        return $this->render('employe/equipe.html.twig', [
            'controller_name' => 'EmployeController',
            'title' => 'Liste des employés - TaskLinker',
            'team' => $team,
        ]);
    }
}
