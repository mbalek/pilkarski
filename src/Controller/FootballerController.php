<?php

namespace App\Controller;

use App\Entity\Footballer;
use App\Form\FootballerType;
use App\Repository\FootballerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/footballer")
 */
class FootballerController extends AbstractController
{
    /**
     * @Route("/", name="footballer_index", methods={"GET"})
     */
    public function index(FootballerRepository $footballerRepository): Response
    {
        return $this->render('footballer/index.html.twig', [
            'footballers' => $footballerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="footballer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $footballer = new Footballer();
        $form = $this->createForm(FootballerType::class, $footballer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($footballer);
            $entityManager->flush();

            return $this->redirectToRoute('footballer_index');
        }

        return $this->render('footballer/new.html.twig', [
            'footballer' => $footballer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="footballer_show", methods={"GET"})
     */
    public function show(Footballer $footballer): Response
    {
        return $this->render('footballer/show.html.twig', [
            'footballer' => $footballer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="footballer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Footballer $footballer): Response
    {
        $form = $this->createForm(FootballerType::class, $footballer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('footballer_index', [
                'id' => $footballer->getId(),
            ]);
        }

        return $this->render('footballer/edit.html.twig', [
            'footballer' => $footballer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="footballer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Footballer $footballer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$footballer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($footballer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('footballer_index');
    }
}
