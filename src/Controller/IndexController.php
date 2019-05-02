<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\League;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        foreach ($games as $game)
        {
            $date = $game->getGameDateTime();
            $end = clone $date;
            $end->modify('+180 minute');
            $now = new \DateTime();
            if ( $end > $now && $now >= $game->getGameDateTime())
            {
                array_push($liveGames , $game);
            }

        }
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
}