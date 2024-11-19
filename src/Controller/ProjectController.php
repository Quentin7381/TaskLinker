<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProjectType;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends AbstractController
{
    
    #[Route('/projet', name: 'projet')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();
        return $this->render('project/list.html.twig', [
            'projects' => $projects,
            'title' => 'Liste des projets - TaskLinker',
        ]);
    }

    #[Route('/project/edit/{id}', name: 'project_edit', requirements: ['id' => '\d+']),]
    #[Route('/project/add', name: 'project_add')]
    public function form(Request $request, EntityManagerInterface $entityManager, ?Project $project = null): Response
    {
        $project = $project ?? new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('projet');
        }

        return $this->render('project/form.html.twig', [
            'form' => $form->createView(),
            'title' => $project->getName() ? $project->getName() : 'Ajouter un projet',
        ]);
    }

    #[Route('/project/delete/{id}', name: 'project_delete', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $entityManager, Project $project): Response
    {
        $entityManager->remove($project);
        $entityManager->flush();

        return $this->redirectToRoute('projet');
    }

    #[Route('/project/archive/{id}', name: 'project_archive', requirements: ['id' => '\d+'])]
    public function archive(EntityManagerInterface $entityManager, Project $project): Response
    {
        $project->setArchived(true);
        $entityManager->persist($project);
        $entityManager->flush();

        return $this->redirectToRoute('projet');
    }
}