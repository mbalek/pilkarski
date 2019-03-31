<?php

namespace App\Controller;

use App\Entity\GameTeamSquad;
use App\Form\GameTeamSquadType;
use App\Repository\GameTeamSquadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gameteamsquad")
 */
class GameTeamSquadController extends AbstractController
{
    /**
     * @Route("/", name="game_team_squad_index", methods={"GET"})
     */
    public function index(GameTeamSquadRepository $gameTeamSquadRepository): Response
    {
        return $this->render('game_team_squad/index.html.twig', [
            'game_team_squads' => $gameTeamSquadRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="game_team_squad_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gameTeamSquad = new GameTeamSquad();
        $form = $this->createForm(GameTeamSquadType::class, $gameTeamSquad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gameTeamSquad);
            $entityManager->flush();

            return $this->redirectToRoute('game_team_squad_index');
        }

        return $this->render('game_team_squad/new.html.twig', [
            'game_team_squad' => $gameTeamSquad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="game_team_squad_show", methods={"GET"})
     */
    public function show(GameTeamSquad $gameTeamSquad): Response
    {
        return $this->render('game_team_squad/show.html.twig', [
            'game_team_squad' => $gameTeamSquad,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="game_team_squad_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GameTeamSquad $gameTeamSquad): Response
    {
        $form = $this->createForm(GameTeamSquadType::class, $gameTeamSquad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('game_team_squad_index', [
                'id' => $gameTeamSquad->getId(),
            ]);
        }

        return $this->render('game_team_squad/edit.html.twig', [
            'game_team_squad' => $gameTeamSquad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="game_team_squad_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GameTeamSquad $gameTeamSquad): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gameTeamSquad->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gameTeamSquad);
            $entityManager->flush();
        }

        return $this->redirectToRoute('game_team_squad_index');
    }
}
