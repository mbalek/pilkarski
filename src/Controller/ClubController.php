<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\League;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
 * @Route("/club")
 */
class ClubController extends AbstractController
{
    /**
     * @Route("/league/{id}", name="club_index", methods={"GET"})
     */
    public function index(ClubRepository $clubRepository, Request $request): Response
    {
        return $this->render('club/index.html.twig', [
            'clubs' => $clubRepository->findBy(array('league' => $request->get('league'))),
            'league' => $request->get('league'),
        ]);
    }

    /**
     * @Route("/new", name="club_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository(League::class)->findOneBy(array('id'=>$request->get('league')));

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $club->setLeague($league);
            $entityManager->persist($club);
            $entityManager->flush();

            return $this->redirectToRoute('league_show' ,array('id'=>$league->getId()));
        }

        return $this->render('club/new.html.twig', [
            'club' => $club,
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/league/{id}", name="club_show", methods={"GET"})
     */
    public function show(Club $club, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository(League::class)->findOneBy(array('id'=>$request->get('league')));

        return $this->render('club/show.html.twig', [
            'club' => $club,
            'league' => $league,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="club_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Club $club): Response
    {
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository(League::class)->findOneBy(array('id'=>$request->get('league')));

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('league_show' ,array('id'=>$league->getId()));
        }

        return $this->render('club/edit.html.twig', [
            'club' => $club,
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="club_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Club $club): Response
    {
        $league = $request->get('league');
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($club);
            $entityManager->flush();
        }

        return $this->redirectToRoute('league_show' ,array('id'=>$league));
    }
}
