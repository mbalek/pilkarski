<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminPanelController extends AbstractController
{
    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/admin", name="admin_panel")
     */
    public function index(TaskRepository $taskRepository)
    {
        return $this->render('admin_panel/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN" , message="Error 404, no permissions")
     * @Route("/admin/dictionary" , name="admin_dictionary")
     */
    public function dictionaryIndex()
    {
        return $this->render('admin_panel/dictionary.html.twig' , [
            'controller_name' => 'AdminPanelController',
        ]);
    }
}
