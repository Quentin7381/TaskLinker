<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Employee;
use App\Form\EmployeeType;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/employe/edit/{id}', name: 'employe_edit', requirements: ['id' => '\d+']),]
    #[Route('/employe/add', name: 'employe_add')]
    public function form(Request $request, EntityManagerInterface $entityManager, ?Employee $employee = null): Response
    {
        $employee = $employee ?? new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employee);
            $entityManager->flush();
    
            return $this->redirectToRoute('equipe');
        }
    
        return $this->render('employe/form.html.twig', [
            'form' => $form->createView(),
            'title' => $employee->getFirstName() ? $employee->getFirstName() . ' ' . $employee->getLastName() : 'Ajouter un employé',
        ]);
    }

    #[Route('/employe/delete/{id}', name: 'employe_delete', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $entityManager, Employee $employee): Response
    {
        $entityManager->remove($employee);
        $entityManager->flush();

        return $this->redirectToRoute('equipe');
    }
}
