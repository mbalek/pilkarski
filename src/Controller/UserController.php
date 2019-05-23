<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\ModeratorType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN" , message="Error 404, no permissions")
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();
        $role = $roles[0];
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll();


        return $this->render('user/index.html.twig', [
            'users' => $users,
            'role' => $role,
        ]);
    }

    /**
     * @Route("/new/administrator", name="user_new_admin", methods={"GET","POST"})
     */
    public function newAdmin(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->addRole('ROLE_ADMIN');
            $user->removeRole('ROLE_USER');
            $user->setEnabled(true);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'type' => $request->get('type'),
        ]);
    }

    /**
     * @Route("/new/moderator", name="user_new_moderator", methods={"GET","POST"})
     */
    public function newModerator(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(ModeratorType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->addRole("ROLE_MODERATOR");
            $user->removeRole('ROLE_USER');
            $user->setEnabled(true);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'type' => $request->get('type'),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selected = $form->get('permissions')->getData();
            $roles = $user->getRoles();
            if($roles[0] != $selected)
            {
                $user->addRole($selected);
                $user->removeRole($roles[0]);
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

}
