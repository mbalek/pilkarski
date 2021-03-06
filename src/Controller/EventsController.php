<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Game;
use App\Entity\GameTeam;
use App\Entity\GameTeamSquad;
use App\Entity\Substitution;
use App\Form\EventsType;
use App\Repository\EventsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
 * @Route("/events")
 */
class EventsController extends AbstractController
{
    /**
     * @Route("/", name="events_index", methods={"GET"})
     */
    public function index(EventsRepository $eventsRepository): Response
    {
        return $this->render('events/index.html.twig', [
            'events' => $eventsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="events_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $event = new Events();
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('events_index');
        }

        return $this->render('events/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/newFromPanel/{id}/{type}", name="events_new_from_panel", methods={"GET","POST"})
     */
    public function newFromPanel($id, $type, Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $event = new Events();
        if($request->request->get('minute')) $event->setMinute($request->request->get('minute'));


        //Basic Event
        if( $type === "1" ) {

            $event->setMessage($request->request->get('message'));
            $event->setEventType($type);
            $event->setGame($this->getDoctrine()->getRepository(Game::class)->find($id));

            $em->persist($event);
            $em->flush();


            return $this->redirectToRoute('game_panel', [ 'id' => $id]);
        } else if( $type === "2" )  {
        //Card event
                $event->setMessage($request->request->get('message'));
                $event->setEventType($type);
                $event->setGame($this->getDoctrine()->getRepository(Game::class)->find($id));
                $event->setOtherData($request->request->get('cardColor'));

                $player = $this->getDoctrine()->getRepository(GameTeamSquad::class)->find($request->request->get('player'));
                $event->setPlayer1($player);

                if ($request->request->get('cardColor') == 'red')
                    $player->getFootballer()->setRedCards($player->getFootballer()->getRedCards()+1);

                if ($request->request->get('cardColor') == 'yellow')
                $player->getFootballer()->setYellowCards($player->getFootballer()->getYellowCards()+1);

                $em->persist($event);
                $em->flush();





                return $this->redirectToRoute('game_panel', [ 'id' => $id]);
        } else if( $type === "3" ) {
            //Penalty event
            $event->setMessage($formData = $request->request->get('message'));
            $event->setEventType($type);
            $event->setGame($this->getDoctrine()->getRepository(Game::class)->find($id));
            $x = $this->getDoctrine()->getRepository(GameTeam::class)->find($request->request->get('forTeam'));
            $event->setOtherData($x->getId());
            $em->persist($event);
            $em->flush();


            return $this->redirectToRoute('game_panel', [ 'id' => $id]);
        } else if( $type === "4" ) {
            //Goal event
            $event->setMessage($formData = $request->request->get('message'));
            $event->setEventType($type);
            $event->setGame($this->getDoctrine()->getRepository(Game::class)->find($id));

            $p1 = $em->getRepository(GameTeamSquad::class)->find($request->request->get('scored'));
            if (($request->request->get('assisted') != "-1")) $p2 = $em->getRepository(GameTeamSquad::class)->find($request->request->get('assisted'));

            if ($request->request->get('owngoal') != "on") $p1->getFootballer()->setGoals($p1->getFootballer()->getGoals()+1);

            //Own goal handling
            if ($request->request->get('owngoal') == "on") {
                $game = $this->getDoctrine()->getRepository(Game::class)->find($id);
                $playerTeam = $p1->getGameTeam();

                if($game->getHomeTeam()->getId() == $playerTeam->getId()) $game->getAwayTeam()->setScore($game->getAwayTeam()->getScore()+1);
                else $game->getHomeTeam()->setScore($game->getHomeTeam()->getScore()+1);

            }
            else {
                $p1->getGameTeam()->setScore($p1->getGameTeam()->getScore()+1);
            }



            if (($request->request->get('assisted') != "-1")) $p2->getFootballer()->setAssists($p2->getFootballer()->getAssists()+1);


            $event->setPlayer1($p1);
            if (($request->request->get('assisted') != "-1")) $event->setPlayer2($p2);

            $event->setOtherData($request->request->get('owngoal'));
            $em->persist($event);
            $em->flush();


            return $this->redirectToRoute('game_panel', [ 'id' => $id]);
        }
        else if( $type === "5" ) {

            //Sub event
            $event->setMessage($formData = $request->request->get('message'));
            $event->setEventType($type);
            $event->setGame($this->getDoctrine()->getRepository(Game::class)->find($id));

            $p1 = $em->getRepository(GameTeamSquad::class)->find($request->request->get('off'));
            $p2 = $em->getRepository(GameTeamSquad::class)->find($request->request->get('in'));

            $event->setPlayer1($p1);
            $event->setPlayer2($p2);

            $sub = new Substitution();
            $sub->setPlayerIn($p2);
            $em->persist($sub);

            $p1->setSubstitution($sub);


            $event->setOtherData($sub->getId());
            $em->persist($event);
            $em->flush();


            return $this->redirectToRoute('game_panel', [ 'id' => $id]);

        } else if( $type === "6" ) {
            //Penalty Shootout
            $event->setMessage($formData = $request->request->get('message'));
            $event->setEventType($type);
            $event->setGame($this->getDoctrine()->getRepository(Game::class)->find($id));

            $p1 = $em->getRepository(GameTeamSquad::class)->find($request->request->get('scored'));
            $event->setPlayer1($p1);

            $p1->getGameTeam()->setPenaltyShootoutScore($p1->getGameTeam()->getPenaltyShootoutScore()+1);


            $em->persist($event);
            $em->flush();


            return $this->redirectToRoute('game_panel', [ 'id' => $id]);
        }

        return $this->redirectToRoute('game_panel', [ 'id' => $id]);
    }

    /**
     * @Route("/undo_event/{id}/{type}", name="events_undo", methods={"GET","POST"})
     */
    public function undoEvent($id, $type, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Events::class)->find($id);
        $game = $event->getGame();


        //Basic Event
        if( $type === "1" ) {

            $em->remove($event);
            $em->flush();


        } else if( $type === "2" )  {
            //Card event

            //Undo statistics change
            $player = $event->getPlayer1();
            if ($event->getOtherData() == 'red')
                $player->getFootballer()->setRedCards($player->getFootballer()->getRedCards()-1);

            if ($event->getOtherData() == 'yellow')
                $player->getFootballer()->setYellowCards($player->getFootballer()->getYellowCards()-1);

            //Remove event entry
            $em->remove($event);
            $em->flush();

        } else if( $type === "4" ) {
            //Goal event

            $p1 = $event->getPlayer1();
            $p2 = $event->getPlayer2();

            if ($event->getOtherData() != "on") $p1->getFootballer()->setGoals($p1->getFootballer()->getGoals()-1);

            //Own goal handling
            if ($event->getOtherData() == "on") {
                $game = $event->getGame();
                $playerTeam = $p1->getGameTeam();

                if($game->getHomeTeam()->getId() == $playerTeam->getId()) $game->getAwayTeam()->setScore($game->getAwayTeam()->getScore()-1);
                else $game->getHomeTeam()->setScore($game->getHomeTeam()->getScore()-1);

            }
            else {
                $p1->getGameTeam()->setScore($p1->getGameTeam()->getScore()-1);
            }


            if ($p2) $p2->getFootballer()->setAssists($event->getPlayer2()->getFootballer()->getAssists()-1);

            //Remove event entry
            $em->remove($event);
            $em->flush();
        }
        else if( $type === "5" ) {
            //Sub event

            $p1 = $event->getPlayer1();

            //Delete substitution entry
            $sub = $p1->getSubstitution();
            $p1->removeSubstituted($sub);

            //Delete event entry
            $em->remove($event);
            $em->flush();

        } else if( $type === "6" ) {
            //Penalty Shootout

            //Undo statistics entries
            $p1 = $event->getPlayer1();
            $p1->getGameTeam()->setPenaltyShootoutScore($p1->getGameTeam()->getPenaltyShootoutScore()-1);

            //Delete event entry
            $em->remove($event);
            $em->flush();
        }

        return $this->redirectToRoute('game_panel', [ 'id' => $game->getId()]);
    }

    /**
     * @Route("/{id}", name="events_show", methods={"GET"})
     */
    public function show(Events $event): Response
    {
        return $this->render('events/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="events_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Events $event): Response
    {
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('events_index', [
                'id' => $event->getId(),
            ]);
        }

        return $this->render('events/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="events_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Events $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('events_index');
    }
}
