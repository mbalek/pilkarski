<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Footballer;
use App\Entity\GameTeam;
use App\Entity\Game;
use App\Entity\GameTeamSquad;
use App\Form\GameTeamType;
use App\Repository\GameTeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/gameteam")
 */
class GameTeamController extends AbstractController
{
    /**
     * @Route("/", name="game_team_index", methods={"GET"})
     */
    public function index(GameTeamRepository $gameTeamRepository): Response
    {
        return $this->render('game_team/index.html.twig', [
            'game_teams' => $gameTeamRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="game_team_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gameTeam = new GameTeam();

        /*
        $tag1 = new Tag();
        $tag1->setName('tag1');
        $task->getTags()->add($tag1);
        */


        $player1 = new GameTeamSquad();
        $player2 = new GameTeamSquad();
        $player3 = new GameTeamSquad();
        $player4 = new GameTeamSquad();
        $gameTeam->getGameTeamSquads()->add($player1);
        $gameTeam->getGameTeamSquads()->add($player2);
        $gameTeam->getGameTeamSquads()->add($player3);
        $gameTeam->getGameTeamSquads()->add($player4);



        $form = $this->createForm(GameTeamType::class, $gameTeam);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /*$entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gameTeam);
            $entityManager->flush();*/

            return $this->redirectToRoute('game_team_index');
        }

        return $this->render('game_team/new.html.twig', [
            'game_team' => $gameTeam,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="game_team_show", methods={"GET"})
     */
    public function show(GameTeam $gameTeam): Response
    {
        return $this->render('game_team/show.html.twig', [
            'game_team' => $gameTeam,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="game_team_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GameTeam $gameTeam): Response
    {
        $form = $this->createForm(GameTeamType::class, $gameTeam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('game_team_index', [
                'id' => $gameTeam->getId(),
            ]);
        }

        return $this->render('game_team/edit.html.twig', [
            'game_team' => $gameTeam,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="game_team_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GameTeam $gameTeam): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gameTeam->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gameTeam);
            $entityManager->flush();
        }

        return $this->redirectToRoute('game_team_index');
    }

    /**
     * @Route("/load/footballer", name="game_team_load_footballer" , methods={"GET","POST"})
     */
    public function loadFootballer(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $clubid = $request->get('clubId');
            $em = $this->getDoctrine()->getManager();
            $club = $em->getRepository(Club::class)->find($clubid);
            $footballers = $em->getRepository(Footballer::class)->findBy(array('club' => $club->getId()));

            $encoders = [
                new JsonEncoder(),
            ];
            $normalizers = [
                (new ObjectNormalizer())->setIgnoredAttributes(['imageFile' , 'image' , 'gameTeamSquads',
                    'country' , 'club' , 'position']),
            ];
            $serializer = new \Symfony\Component\Serializer\Serializer($normalizers,$encoders);
            $data = $serializer->serialize($footballers , 'json');

            return new JsonResponse($data, 200, [],true);
        }
        return new JsonResponse([
            'type' => 'error',
            'message' => 'AJAX only'
        ]);
    }
}
