<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Footballer;
use App\Entity\Game;
use App\Entity\GameTeamSquad;
use App\Entity\League;
use App\Entity\Round;
use App\Entity\User;
use App\Form\EventsType;
use App\Form\GameManageSquadsType;
use App\Form\GameType;
use App\Repository\Dictionary\eventsTypeRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/game")
 */
class GameController extends AbstractController
{
    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/", name="game_index", methods={"GET"})
     */
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/new", name="game_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $game = new Game();
        $entityManager = $this->getDoctrine()->getManager();
        $round = $entityManager->getRepository(Round::class)->find($request->get('roundId'));
        $form = $this->createForm(GameType::class, $game, array('leagueId' => $request->get('leagueId')));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game->setRound($round);
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('round_show' , array('id' => $round->getId() ,'round' => $round));
        }

        return $this->render('game/new.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
            'roundId' => $request->get('roundId'),
            'round' => $round,

        ]);
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/show/{id}", name="game_show", methods={"GET"})
     */
    public function show(Game $game): Response
    {
        return $this->render('game/show.html.twig', [
            'game' => $game,
        ]);
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/panel/{id}", name="game_panel", methods={"GET"})
     */
    public function panel(Game $game): Response
    {
        return $this->render('game/panel.html.twig', [
            'game' => $game,
            'eventTypes' => $repository = $this->getDoctrine()->getRepository(\App\Entity\Dictionary\eventsType::class )->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/{id}/edit", name="game_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Game $game): Response
    {
        $form = $this->createForm(GameType::class, $game , array('leagueId' => $request->get('leagueId')));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('game_panel', [
                'id' => $game->getId(),
                'game' => $game,
            ]);
        }

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/{id}", name="game_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Game $game): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($game);
            $entityManager->flush();
        }

        return $this->redirectToRoute('game_index');
    }

    /**
     * @IsGranted("ROLE_USER" , message="Error 404, no permissions")
     * @Route("/usercomment/new" , name="user_comment_new_ajax" , methods={"GET","POST"})
     */
    public function addComment(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $userId = $request->get('username');
            $gameId = $request->get('game');
            $description = $request->get('description');

            $em = $this->getDoctrine()->getManager();
            $game = $em->getRepository(Game::class)->find($gameId);
            $user = $em->getRepository(User::class)->find($userId);
            $comment = new Comment();
            $comment->setGame($game);
            $comment->setDescription($description);
            $comment->setCreatedBt($user);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $em->persist($comment);
            $em->flush();

            $encoders = [
                new JsonEncoder(),
            ];
            $normalizers = [
                (new ObjectNormalizer())->setIgnoredAttributes(['updatedBy' , 'changedBy',
                    'game'  ,'updatedAt' , 'changedAt' , 'moderatingLeague'] )
            ];
            $serializer = new \Symfony\Component\Serializer\Serializer($normalizers,$encoders);
            $data = $serializer->serialize($comment , 'json');

            return new JsonResponse($data, 200, [],true);
        }
        return new JsonResponse([
            'type' => 'error',
            'message' => 'AJAX only'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER" , message="Error 404, no permissions")
     * @Route("/usercomment/delete" , name="user_comment_delete_ajax" , methods={"GET","POST"})
     */
    public function deleteComment(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $commentId = $request->get('commentId');

            $em = $this->getDoctrine()->getManager();
            $comment=$em->getRepository(Comment::class)->find($commentId);
            $em->remove($comment);
            $em->flush();

            $encoders = [
                new JsonEncoder(),
            ];
            $normalizers = [
                new ObjectNormalizer(),
            ];
            $serializer = new \Symfony\Component\Serializer\Serializer($normalizers,$encoders);
            $data = $serializer->serialize($commentId , 'json');

            return new JsonResponse($data, 200, [],true);
        }
        return new JsonResponse([
            'type' => 'error',
            'message' => 'AJAX only'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER" , message="Error 404, no permissions")
     * @Route("/usercomment/replace" , name="user_comment_edit_ajax" , methods={"GET","POST"})
     */
    public function editComment(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $commentId = $request->get('commentId');
            $description = $request->get('description');

            $em = $this->getDoctrine()->getManager();
            $comment = $em->getRepository(Comment::class)->find($commentId);
            $comment->setDescription($description);
            $em->flush();

            $encoders = [
                new JsonEncoder(),
            ];
            $normalizers = [
                (new ObjectNormalizer())->setIgnoredAttributes(['game' , 'moderatingLeague']),
            ];
            $serializer = new \Symfony\Component\Serializer\Serializer($normalizers,$encoders);
            $data = $serializer->serialize($comment , 'json');

            return new JsonResponse($data, 200, [],true);
        }
        return new JsonResponse([
            'type' => 'error',
            'message' => 'AJAX only'
        ]);
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/manageSquads/{id}" , name="game_manage_squads" , methods={"GET","POST"})
     */
    public function manageSquads($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $game = $entityManager->getRepository(Game::class)->find($id);

        //Squad Shit
        if(sizeof($game->getHomeTeam()->getGameTeamSquads()) < 11) {

            foreach($game->getHomeTeam()->getGameTeamSquads() as $existingSquad) {
                $game->getHomeTeam()->removeGameTeamSquad($existingSquad);
            }

            for($i = 0; $i < 11; $i++) {
              $player = new GameTeamSquad();
              $player->setIsReserve(false);
                $player->setNumber(99);
              $game->getHomeTeam()->addGameTeamSquad($player);
            }

            for($i = 0; $i < 7; $i++) {
                $player = new GameTeamSquad();
                $player->setIsReserve(true);
                $player->setNumber(99);
                $game->getHomeTeam()->addGameTeamSquad($player);
            }

        }

        if(sizeof($game->getAwayTeam()->getGameTeamSquads()) < 11) {

            foreach($game->getAwayTeam()->getGameTeamSquads() as $existingSquad) {
                $game->getAwayTeam()->removeGameTeamSquad($existingSquad);
            }

            for($i = 0; $i < 11; $i++) {
                $player = new GameTeamSquad();
                $player->setIsReserve(false);
                $player->setNumber(99);
                $game->getAwayTeam()->addGameTeamSquad($player);
            }

            for($i = 0; $i < 7; $i++) {
                $player = new GameTeamSquad();
                $player->setIsReserve(true);
                $player->setNumber(99);
                $game->getAwayTeam()->addGameTeamSquad($player);
            }

        }


        //Form handle shit
        $homeTeam = $game->getHomeTeam()->getClub()->getId();
        $awayTeam = $game->getAwayTeam()->getClub()->getId();
        $form = $this->createForm(GameManageSquadsType::class, $game, array('home' => $homeTeam , 'away' => $awayTeam));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('game_panel' , array('game' => $game, 'id' => $game->getId()));
        }

        return $this->render('game/manage_squads.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
            'home' => $homeTeam,
            'away' => $awayTeam,
        ]);
    }


    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/start/first/half/{id}" , name="game_start_first_half" , methods={"GET","POST"})
     */
    public function startFirstHalf(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $game->setFirstHalfStart(new \DateTime());
        $game->setStatus(2);
        $game->setState(1);
        $em->persist($game);
        $em->flush();
        return $this->redirectToRoute('game_panel' , array('game' => $game, 'id' => $game->getId()));
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/end/first/half/{id}" , name="game_end_first_half" , methods={"GET","POST"})
     */
    public function endFirstHalf(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $game->setFirstHalfEnd(new \DateTime());
        $em->persist($game);
        $em->flush();
        return $this->redirectToRoute('game_panel' , array('game' => $game, 'id' => $game->getId()));
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/start/second/half/{id}" , name="game_start_second_half" , methods={"GET","POST"})
     */
    public function startSecondHalf(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $game->setSecondHalfStart(new \DateTime());
        $game->setState(2);
        $em->persist($game);
        $em->flush();
        return $this->redirectToRoute('game_panel' , array('game' => $game, 'id' => $game->getId()));
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/end/second/half/{id}" , name="game_end_second_half" , methods={"GET","POST"})
     */
    public function endSecondHalf(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $game->setSecondHalfEnd(new \DateTime());
        $em->persist($game);
        $em->flush();
        return $this->redirectToRoute('game_panel' , array('game' => $game, 'id' => $game->getId()));
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/start/ext/first/half/{id}" , name="game_start_ext_first_half" , methods={"GET","POST"})
     */
    public function startExtFirstHalf(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $game->setExtentedFirstHalfStart(new \DateTime());
        $game->setState(3);
        $em->persist($game);
        $em->flush();
        return $this->redirectToRoute('game_panel' , array('game' => $game, 'id' => $game->getId()));
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/end/ext/first/half/{id}" , name="game_end_ext_first_half" , methods={"GET","POST"})
     */
    public function endExtFirstHalf(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $game->setExtentedFirstHalfEnd(new \DateTime());
        $em->persist($game);
        $em->flush();
        return $this->redirectToRoute('game_panel' , array('game' => $game, 'id' => $game->getId()));
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/start/ext/second/half/{id}" , name="game_start_ext_second_half" , methods={"GET","POST"})
     */
    public function startExtSecondHalf(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $game->setExtentedSecondHalfStart(new \DateTime());
        $game->setState(4);
        $em->persist($game);
        $em->flush();
        return $this->redirectToRoute('game_panel' , array('game' => $game, 'id' => $game->getId()));
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/end/ext/second/half/{id}" , name="game_end_ext_second_half" , methods={"GET","POST"})
     */
    public function endExtSecondHalf(Game $game)
    {
        $em = $this->getDoctrine()->getManager();
        $game->setExtentedSecondHalfEnd(new \DateTime());
        $em->persist($game);
        $em->flush();
        return $this->redirectToRoute('game_panel' , array('game' => $game, 'id' => $game->getId()));
    }

    /**
     * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
     * @Route("/end/game/{id}" , name="game_end_game" , methods={"GET","POST"})
     */
    public function endGame(Game $game , Request $request)
    {
        $round = $request->get('round');
        $roundId = $request->get('roundId');
        $em = $this->getDoctrine()->getManager();
        $game->setStatus(1);
        $game->setState(0);
        $em->persist($game);
        $em->flush();
        return $this->redirectToRoute('round_show' , array('round' => $round, 'id' => $roundId));
    }

}
