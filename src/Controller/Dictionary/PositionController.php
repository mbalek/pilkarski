<?php

namespace App\Controller\Dictionary;

use App\Entity\Dictionary\Position;
use App\Form\Dictionary\PositionType;
use App\Repository\Dictionary\PositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dictionary/position")
 */
class PositionController extends AbstractController
{
    /**
     * @Route("/", name="dictionary_position_index", methods={"GET"})
     */
    public function index(PositionRepository $positionRepository): Response
    {
        return $this->render('dictionary/position/index.html.twig', [
            'positions' => $positionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="dictionary_position_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $position = new Position();
        $form = $this->createForm(PositionType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($position);
            $entityManager->flush();

            return $this->redirectToRoute('dictionary_position_index');
        }

        return $this->render('dictionary/position/new.html.twig', [
            'position' => $position,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dictionary_position_show", methods={"GET"})
     */
    public function show(Position $position): Response
    {
        return $this->render('dictionary/position/show.html.twig', [
            'position' => $position,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dictionary_position_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Position $position): Response
    {
        $form = $this->createForm(PositionType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dictionary_position_index', [
                'id' => $position->getId(),
            ]);
        }

        return $this->render('dictionary/position/edit.html.twig', [
            'position' => $position,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dictionary_position_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Position $position): Response
    {
        if ($this->isCsrfTokenValid('delete'.$position->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($position);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dictionary_position_index');
    }
}
