<?php

namespace App\Controller;

use App\Entity\Round;
use App\Form\RoundType;
use App\Repository\RoundRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/round")
 */
class RoundController extends AbstractController
{
    /**
     * @Route("/", name="round_index", methods={"GET"})
     */
    public function index(RoundRepository $roundRepository): Response
    {
        return $this->render('round/index.html.twig', [
            'rounds' => $roundRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="round_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $round = new Round();
        $form = $this->createForm(RoundType::class, $round);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($round);
            $entityManager->flush();

            return $this->redirectToRoute('round_index');
        }

        return $this->render('round/new.html.twig', [
            'round' => $round,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="round_show", methods={"GET"})
     */
    public function show(Round $round): Response
    {
        return $this->render('round/show.html.twig', [
            'round' => $round,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="round_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Round $round): Response
    {
        $form = $this->createForm(RoundType::class, $round);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('round_index', [
                'id' => $round->getId(),
            ]);
        }

        return $this->render('round/edit.html.twig', [
            'round' => $round,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="round_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Round $round): Response
    {
        if ($this->isCsrfTokenValid('delete'.$round->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($round);
            $entityManager->flush();
        }

        return $this->redirectToRoute('round_index');
    }
}
