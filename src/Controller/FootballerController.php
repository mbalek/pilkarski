<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Footballer;
use App\Entity\League;
use App\Form\FootballerType;
use App\Repository\FootballerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/footballer")
 */
class FootballerController extends AbstractController
{
    /**
     * @Route("/club/{id}", name="footballer_index", methods={"GET"})
     */
    public function index(FootballerRepository $footballerRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository(League::class)->findOneBy(array('id'=>$request->get('league')));
        return $this->render('footballer/index.html.twig', [
            'footballers' => $footballerRepository->findBy(array('club' => $request->get('id'))),
            'club' => $request->get('id'),
            'league' => $league,
        ]);
    }

    /**
     * @Route("/new", name="footballer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $footballer = new Footballer();
        $form = $this->createForm(FootballerType::class, $footballer);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository(Club::class)->findOneBy(array('id'=>$request->get('club')));

        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository(League::class)->findOneBy(array('id'=>$request->get('league')));

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $footballer->setClub($club);
            $entityManager->persist($footballer);
            $entityManager->flush();
            return $this->redirectToRoute('club_show' , array('id' =>$club->getId() , 'league'=>$league->getId() ));
        }

        return $this->render('footballer/new.html.twig', [
            'footballer' => $footballer,
            'club' => $club,
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="footballer_show", methods={"GET"})
     */
    public function show(Footballer $footballer): Response
    {
        return $this->render('footballer/show.html.twig', [
            'footballer' => $footballer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="footballer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Footballer $footballer): Response
    {
        $form = $this->createForm(FootballerType::class, $footballer);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository(Club::class)->findOneBy(array('id'=>$request->get('club')));

        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository(League::class)->findOneBy(array('id'=>$request->get('league')));

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('club_show' , array('id' =>$club->getId() , 'league'=>$league->getId() ));
        }

        return $this->render('footballer/edit.html.twig', [
            'footballer' => $footballer,
            'club' => $club,
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="footballer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Footballer $footballer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository(Club::class)->findOneBy(array('id'=>$request->get('club')));

        $em = $this->getDoctrine()->getManager();
        $league = $em->getRepository(League::class)->findOneBy(array('id'=>$request->get('league')));

        if ($this->isCsrfTokenValid('delete'.$footballer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($footballer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('club_show' , array('id' =>$club->getId() , 'league'=>$league->getId() ));
    }
}
