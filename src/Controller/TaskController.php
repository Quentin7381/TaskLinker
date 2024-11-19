<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\TaskType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Project;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'task_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $tasks = $entityManager->getRepository(Task::class)->findAll();
        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
            'title' => 'Liste des tâches - TaskLinker',
        ]);
    }

    #[Route('/task/edit/{id}', name: 'task_edit', requirements: ['id' => '\d+'])]
    #[Route('/task/add/{projectId}', name: 'task_add')]
    public function form(Request $request, EntityManagerInterface $entityManager, ?Task $task = null): Response
    {
        $task = $task ?? new Task();
        $project = $task->getProject() ?? $entityManager->getRepository(Project::class)->find($request->get('projectId'));
        if($project) {
            $task->setProject($project);
        }

        $form = $this->createForm(TaskType::class, $task, ['project' => $task->getProject()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            // Redirect to the project page
            return $this->redirectToRoute('project_page', ['id' => $task->getProject()->getId()]);
        }

        return $this->render('task/form.html.twig', [
            'form' => $form->createView(),
            'title' => $task->getName() ? $task->getName() : 'Ajouter une tâche',
        ]);
    }

    #[Route('/task/delete/{id}', name: 'task_delete', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $entityManager, Task $task): Response
    {
        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('project_page', ['id' => $task->getProject()->getId()]);
    }

    #[Route('/task/{id}', name: 'task_page', requirements: ['id' => '\d+'])]
    public function page(Task $task): Response
    {
        return $this->render('task/page.html.twig', [
            'task' => $task,
            'title' => $task->getName(),
        ]);
    }
}
