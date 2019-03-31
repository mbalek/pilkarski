<?php

namespace App\Controller\Dictionary;

use App\Entity\Dictionary\eventsType;
use App\Form\Dictionary\eventsTypeType;
use App\Repository\Dictionary\eventsTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dictionary/events/type")
 */
class eventsTypeController extends AbstractController
{
    /**
     * @Route("/", name="dictionary_events_type_index", methods={"GET"})
     */
    public function index(eventsTypeRepository $eventsTypeRepository): Response
    {
        return $this->render('dictionary/events_type/index.html.twig', [
            'events_types' => $eventsTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="dictionary_events_type_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $eventsType = new eventsType();
        $form = $this->createForm(eventsTypeType::class, $eventsType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($eventsType);
            $entityManager->flush();

            return $this->redirectToRoute('dictionary_events_type_index');
        }

        return $this->render('dictionary/events_type/new.html.twig', [
            'events_type' => $eventsType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dictionary_events_type_show", methods={"GET"})
     */
    public function show(eventsType $eventsType): Response
    {
        return $this->render('dictionary/events_type/show.html.twig', [
            'events_type' => $eventsType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dictionary_events_type_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, eventsType $eventsType): Response
    {
        $form = $this->createForm(eventsTypeType::class, $eventsType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dictionary_events_type_index', [
                'id' => $eventsType->getId(),
            ]);
        }

        return $this->render('dictionary/events_type/edit.html.twig', [
            'events_type' => $eventsType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dictionary_events_type_delete", methods={"DELETE"})
     */
    public function delete(Request $request, eventsType $eventsType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eventsType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($eventsType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dictionary_events_type_index');
    }
}
