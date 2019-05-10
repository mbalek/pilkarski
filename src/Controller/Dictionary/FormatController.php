<?php

namespace App\Controller\Dictionary;

use App\Entity\Dictionary\Format;
use App\Form\Dictionary\FormatType;
use App\Repository\Dictionary\FormatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN" , message="Error 404, no permissions")
 * @Route("/dictionary/format")
 */
class FormatController extends AbstractController
{
    /**
     * @Route("/", name="dictionary_format_index", methods={"GET"})
     */
    public function index(FormatRepository $formatRepository): Response
    {
        return $this->render('dictionary/format/index.html.twig', [
            'formats' => $formatRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="dictionary_format_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $format = new Format();
        $form = $this->createForm(FormatType::class, $format);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($format);
            $entityManager->flush();

            return $this->redirectToRoute('dictionary_format_index');
        }

        return $this->render('dictionary/format/new.html.twig', [
            'format' => $format,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dictionary_format_show", methods={"GET"})
     */
    public function show(Format $format): Response
    {
        return $this->render('dictionary/format/show.html.twig', [
            'format' => $format,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dictionary_format_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Format $format): Response
    {
        $form = $this->createForm(FormatType::class, $format);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dictionary_format_index', [
                'id' => $format->getId(),
            ]);
        }

        return $this->render('dictionary/format/edit.html.twig', [
            'format' => $format,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dictionary_format_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Format $format): Response
    {
        if ($this->isCsrfTokenValid('delete'.$format->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($format);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dictionary_format_index');
    }
}
