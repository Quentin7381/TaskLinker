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
    #[Route('/equipe', name: 'team')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Get all employees with manager
        $team = $entityManager->getRepository(Employee::class)->findAll();

        return $this->render('employee/team.html.twig', [
            'controller_name' => 'EmployeController',
            'title' => 'Liste des employés - TaskLinker',
            'team' => $team,
        ]);
    }

    #[Route('/employe/edit/{id}', name: 'employee_edit', requirements: ['id' => '\d+']),]
    #[Route('/employe/add', name: 'employee_add')]
    public function form(Request $request, EntityManagerInterface $entityManager, ?Employee $employee = null): Response
    {
        $employee = $employee ?? new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employee);
            $entityManager->flush();
    
            return $this->redirectToRoute('team');
        }
    
        return $this->render('employee/form.html.twig', [
            'form' => $form->createView(),
            'title' => $employee->getFirstName() ? $employee->getFirstName() . ' ' . $employee->getLastName() : 'Ajouter un employé',
        ]);
    }

    #[Route('/employee/delete/{id}', name: 'employee_delete', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $entityManager, Employee $employee): Response
    {
        $entityManager->remove($employee);
        $entityManager->flush();

        return $this->redirectToRoute('team');
    }
}
