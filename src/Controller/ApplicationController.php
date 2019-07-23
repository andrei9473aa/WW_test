<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Comment;
use App\Form\ApplicationManageType;
use App\Form\ApplicationType;
use App\Form\CommentType;
use App\Form\SetApplicationManagerType;
use App\Repository\ApplicationRepository;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/application")
 */
class ApplicationController extends AbstractController
{
    /**
     * @Route("/", name="application_index", methods={"GET"})
     * @IsGranted({"ROLE_MODERATOR", "ROLE_MANAGER"})
     */
    public function index(ApplicationRepository $applicationRepository): Response
    {
        if($this->isGranted('ROLE_MANAGER')) {
            return $this->render('application/index.html.twig', [
                'applications' => $applicationRepository->findBy(['manager' => $this->getUser()]),
            ]);
        }

        return $this->render('application/index.html.twig', [
            'applications' => $applicationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/manage", name="application_manage", methods={"GET","POST"})
     * @IsGranted("MANAGE", subject="application")
     */
    public function manage(Request $request, Application $application, CommentRepository $commentRepository) {

        $applicationForm = $this->createForm(ApplicationManageType::class, $application);
        $applicationForm->handleRequest($request);

        $commentForm = $this->createForm(CommentType::class);

        $comments = $commentRepository->findWithAuthor($this->getUser());

        if ($applicationForm->isSubmitted() && $applicationForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('application_manage', [
                'id' => $application->getId(),
            ]);
        }

        return $this->render('application/manage.html.twig', [
            'application' => $application,
            'applicationForm' => $applicationForm->createView(),
            'commentForm' => $commentForm->createView(),
            'comments' => $comments,
        ]);

    }

    /**
     * @Route("/new", name="application_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $application->setAuthor($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($application);
            $entityManager->flush();

            return $this->redirectToRoute('application_index');
        }

        return $this->render('application/new.html.twig', [
            'application' => $application,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="application_show", methods={"GET"})
     * @IsGranted("WATCH", subject="application")
     */
    public function show(Application $application): Response
    {
        return $this->render('application/show.html.twig', [
            'application' => $application,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="application_edit", methods={"GET","POST"})
     * @IsGranted("EDIT", subject="application")
     */
    public function edit(Request $request, Application $application): Response
    {
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('application_index');
        }

        return $this->render('application/edit.html.twig', [
            'application' => $application,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="application_delete", methods={"DELETE"})
     * @IsGranted("DELETE", subject="application")
     */
    public function delete(Request $request, Application $application): Response
    {
        if ($this->isCsrfTokenValid('delete'.$application->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($application);
            $entityManager->flush();
        }

        return $this->redirectToRoute('application_index');
    }

    /**
     * @Route("/{id}/comment/new", name="application_comment", methods={"POST"})
     * @IsGranted("MANAGE", subject="application")
     */
    public function commentApplication(Request $request, Application $application): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setApplication($application);
            $comment->setAuthor($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('application_manage', [
                'id' => $application->getId(),
            ]);
        }
    }

    /**
     * @Route("/{id}/set-manager", name="application_set_manager", methods={"GET","POST"})
     * @IsGranted("ROLE_MODERATOR")
     */
    public function setManager(Request $request, Application $application) {

        $form = $this->createForm(SetApplicationManagerType::class, $application);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('application_set_manager', [
                'id' => $application->getId(),
            ]);
        }

        return $this->render('application/set_manager.html.twig', [
            'application' => $application,
            'form' => $form->createView(),
        ]);

    }
}
