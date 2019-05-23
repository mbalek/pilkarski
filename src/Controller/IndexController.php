<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Footballer;
use App\Entity\Game;
use App\Entity\League;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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

        $games = $em->getRepository(Game::class)->findAll();
        $leagues = $em->getRepository(League::class)->findAll();
        $liveGames=[];

        return $this->render('index/live_matches.html.twig', [
            'liveGames' => $liveGames,
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
        return $this->render('game/user_index_show.html.twig',[
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
        return $this->render('index/league_show.html.twig' , [
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
        return $this->render('index/footballers_show.html.twig' , [
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
        return $this->render('index/footballer_show.html.twig' , [
           'club'=>$club,
           'footballer'=>$footballer,
           'leagues'=>$leagues
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

        return $this->render('user/user_matches.html.twig' , [
            'league' => $league,
        ]);
    }
}
