<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\League;
use App\Form\LeagueType;
use App\Repository\LeagueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_MODERATOR" , message="Error 404, no permissions")
 * @Route("/league")
 */
class LeagueController extends AbstractController
{
    /**
     * @Route("/", name="league_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();
        $role = $roles[0];
        $em = $this->getDoctrine()->getManager();

        if($role == "ROLE_MODERATOR")
        {
            $leag = $user->getModeratingLeague();
            $leagues = $em->getRepository(League::class)->findBy(array('id' => $leag->getId()));
        }
        else
        {
            $leagues = $em->getRepository(League::class)->findAll();
        }

        return $this->render('league/index.html.twig', [
            'leagues' => $leagues,
            'username' => $user->getUsername(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN" , message="Error 404, no permissions")
     * @Route("/new", name="league_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $league = new League();
        $form = $this->createForm(LeagueType::class, $league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($league);
            $entityManager->flush();

            return $this->redirectToRoute('league_index');
        }

        return $this->render('league/new.html.twig', [
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="league_show", methods={"GET"})
     */
    public function show(League $league , Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $clubs = $em->getRepository(Club::class)->findBy(array('league' => $league->getId()));

        return $this->render('league/show.html.twig', [
            'league' => $league,
            'clubs' => $clubs
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN" , message="Error 404, no permissions")
     * @Route("/{id}/edit", name="league_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, League $league): Response
    {
        $form = $this->createForm(LeagueType::class, $league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('league_index', [
                'id' => $league->getId(),
            ]);
        }

        return $this->render('league/edit.html.twig', [
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN" , message="Error 404, no permissions")
     * @Route("/{id}", name="league_delete", methods={"DELETE"})
     */
    public function delete(Request $request, League $league): Response
    {
        if ($this->isCsrfTokenValid('delete'.$league->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($league);
            $entityManager->flush();
        }

        return $this->redirectToRoute('league_index');
    }
}
