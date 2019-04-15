<?php

namespace App\Controller;

use App\Entity\League;
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
     * @Route("/league/{id}", name="round_index", methods={"GET"})
     */
    public function index(RoundRepository $roundRepository, Request $request): Response
    {
        return $this->render('round/index.html.twig', [
            'rounds' => $roundRepository->findBy(array('league' => $request->get('league'))),
            'league' => $request->get('league'),
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
        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository(League::class)->findOneBy(array('id'=>$request->get('league')));

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $round->setLeague($league);
            $entityManager->persist($round);
            $entityManager->flush();

            return $this->redirectToRoute('league_show',array('id'=>$league->getId()));
        }

        return $this->render('round/new.html.twig', [
            'round' => $round,
            'league' => $league,
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
        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository(League::class)->findOneBy(array('id'=>$request->get('league')));

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('league_show' ,array('id'=>$league->getId()));
        }

        return $this->render('round/edit.html.twig', [
            'round' => $round,
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="round_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Round $round): Response
    {
        $league = $request->get('league');
        if ($this->isCsrfTokenValid('delete'.$round->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($round);
            $entityManager->flush();
        }

        return $this->redirectToRoute('league_show',array('id'=>$league));
    }
}
