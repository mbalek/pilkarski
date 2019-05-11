<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Footballer;
use App\Entity\Game;
use App\Entity\GameTeamSquad;
use App\Entity\League;
use App\Entity\User;
use App\Form\GameManageSquadsType;
use App\Form\GameType;
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
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('game_index');
        }

        return $this->render('game/new.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
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
     * @Route("/{id}/edit", name="game_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Game $game): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('game_index', [
                'id' => $game->getId(),
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
                (new ObjectNormalizer())->setIgnoredAttributes([ 'updatedBy' , 'changedBy', 'game' ,'updatedAt' , 'changedAt'] )
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
                (new ObjectNormalizer())->setIgnoredAttributes(['game']),
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
        $form = $this->createForm(GameManageSquadsType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('game_index');
        }

        return $this->render('game/manage_squads.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

}
