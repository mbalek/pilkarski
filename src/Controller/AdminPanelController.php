<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_panel")
     */
    public function index(TaskRepository $taskRepository)
    {
        return $this->render('admin_panel/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/dictionary" , name="admin_dictionary")
     */
    public function dictionaryIndex()
    {
        return $this->render('admin_panel/dictionary.html.twig' , [
            'controller_name' => 'AdminPanelController',
        ]);
    }
}
