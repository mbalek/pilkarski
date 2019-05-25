<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Events;
use App\Entity\Footballer;
use App\Entity\Game;
use App\Entity\League;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $leagues = $em->getRepository(League::class)->findAll();
        $games = $em->getRepository(Game::class)->findAll();
        return $this->render('index/index.html.twig', [
            'leagues' => $leagues,
            'games' => $games,
        ]);
    }

    /**
     * @Route("/livegames" , name="liveGames")
     */
    public function liveGames()
    {
        $em = $this->getDoctrine()->getManager();

        $games = $em->getRepository(Game::class)->findBy(array('status' => 2));
        $leagues = $em->getRepository(League::class)->findAll();

        return $this->render('index/live_matches.html.twig', [
            'liveGames' => $games,
            'leagues' => $leagues,
        ]);
    }

    /**
     * @route("/display/match/{id}" , name="index_game_show" , methods={"GET"})
     */
    public function showIndex(Game $game): Response
    {
        $em = $this->getDoctrine()->getManager();
        $leagues = $em->getRepository(League::class)->findAll();
        return $this->render('game/user_index_show.html.twig', [
            'game' => $game,
            'leagues' => $leagues,
        ]);
    }

    /**
     * @route("/display/live/match/{id}" , name="index_live_game_show" , methods={"GET"})
     */
    public function showLiveIndex(Game $game): Response
    {
        $em = $this->getDoctrine()->getManager();
        $leagues = $em->getRepository(League::class)->findAll();
        return $this->render('game/user_live_match.html.twig', [
            'game' => $game,
            'leagues' => $leagues,
        ]);
    }


    /**
     * @route("/display/league/{id}" , name="index_league_show" , methods={"GET"})
     */
    public function showLeagueIndex(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $leagues = $em->getRepository(League::class)->findAll();
        $league = $em->getRepository(League::class)->find($request->get('id'));
        return $this->render('index/league_show.html.twig', [
            'leagues' => $leagues,
            'league' => $league,
        ]);
    }

    /**
     * @route("/display/club/footballers/" , name="index_footballers_show" , methods={"GET"})
     */
    public function showFootballersIndex(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $leagues = $em->getRepository(League::class)->findAll();
        $footballers = $em->getRepository(Footballer::class)->findBy(array('club' => $request->get('club')));
        $club = $em->getRepository(Club::class)->find($request->get('club'));
        return $this->render('index/footballers_show.html.twig', [
            'leagues' => $leagues,
            'footballers' => $footballers,
            'club' => $club
        ]);
    }

    /**
     * @route("/display/club/footballers/foot/" , name="index_footballer_show" , methods={"GET"})
     */
    public function showFootballerIndex(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $footballer = $em->getRepository(Footballer::class)->find($request->get('id'));
        $leagues = $em->getRepository(League::class)->findAll();
        $club = $request->get('club');
        return $this->render('index/footballer_show.html.twig', [
            'club' => $club,
            'footballer' => $footballer,
            'leagues' => $leagues
        ]);
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/matches/user", name="user_matches", methods={"GET"})
     */
    public function getUserMatches(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $league = $this->getUser()->getModeratingLeague();
        $user = $this->getUser();

        return $this->render('user/user_matches.html.twig', [
            'league' => $league,
            'user' => $user
        ]);
    }

    /**
     * @Route("/ajax/events", name="ajax_events", methods={"GET"})
     */
    public function getEventsAjax(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $gameId = $request->get('gameId');
            $em = $this->getDoctrine()->getManager();
            $game = $em->getRepository(Game::class)->find($gameId);
            $events = $em->getRepository(Events::class)->findBy(array('game' => $game));

            $responseArray = [];
            for ($i = 0; $i < count($events); $i++) {
                $name1 = null;
                $name2 = null;
                $surname1 = null;
                $surname2 = null;
                $number1 = null;
                $number2 = null;
                $player1 = $events[$i]->getPlayer1();
                $player2 = $events[$i]->getPlayer2();
                if ($player1 != null) {
                    $footballer1 = $player1->getFootballer();
                    $name1 = $footballer1->getName();
                    $surname1 = $footballer1->getSurname();
                    $number1 = $player1->getNumber();
                }
                if ($player2 != null) {
                    $footballer2 = $player2->getFootballer();
                    $name2 = $footballer2->getName();
                    $surname2 = $footballer2->getSurname();
                    $number2 = $player2->getNumber();
                }

                array_push($responseArray, [
                    "name1" => $name1,
                    "surname1" => $surname1, "number1" => $number1, "name2" => $name2, "surname2" => $surname2, "number2" => $number2, "eventType" => $events[$i]->getEventType(),
                    "message" => $events[$i]->getMessage(),
                    'minute' => $events[$i]->getMinute(),
                    'otherData' => $events[$i]->getOtherData(), "id" => $events[$i]->getId()
                ]);
            }
            $encoders = [
                new JsonEncoder(),
            ];
            $normalizers = [
                (new ObjectNormalizer())->setIgnoredAttributes(['player1', 'player2', 'game']),
            ];
            $serializer = new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);
            $data = $serializer->serialize($responseArray, 'json');

            return new JsonResponse($data, 200, [], true);
        }
        return new JsonResponse([
            'type' => 'error',
            'message' => 'AJAX only'
        ]);
    }
}
