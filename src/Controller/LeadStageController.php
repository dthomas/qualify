<?php

namespace App\Controller;

use App\Entity\LeadStage;
use App\Form\LeadStageFormType;
use App\Repository\LeadStageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings/lead_stages")
 */
class LeadStageController extends AbstractController
{
    /**
     * @Route("/", name="lead_stage.list", methods={"GET"})
     */
    public function index(Request $request, LeadStageRepository $leadStageRepository): Response
    {
        $page = max($request->query->get('page', 1), 1);
        $limit = max($request->query->get('limit', 10), 10);
        $sort = $request->query->get('sort', 'createdAt');
        $dir = $request->query->get('dir', 'desc');
        $dir = $dir === 'asc';

        $lead_stages = $leadStageRepository->findAllLeadStages($page, $limit, $sort, $dir);
        $maxPages = ceil($lead_stages->count() / $limit);

        return $this->render('lead_stage/index.html.twig', [
            'lead_stages' => $lead_stages,
            'limit' => $limit,
            'maxPages' => $maxPages,
            'page' => $page,
            'sort' => $sort,
            'dir' => $dir ? 'asc' : 'desc',
        ]);
    }

    /**
     * @Route("/new", name="lead_stage.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $leadStage = new LeadStage();
        $form = $this->createForm(LeadStageFormType::class, $leadStage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leadStage->setCreatedAt(new \DateTime());
            $leadStage->setAccount($this->getUser()->getAccount());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($leadStage);
            $entityManager->flush();

            $this->addFlash('success', 'Lead stage was added');

            return $this->redirectToRoute('lead_stage.list');
        }

        return $this->render('lead_stage/new.html.twig', [
            'lead_stage' => $leadStage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lead_stage.show", methods={"GET"})
     */
    public function show(LeadStage $leadStage): Response
    {
        return $this->render('lead_stage/show.html.twig', [
            'lead_stage' => $leadStage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lead_stage.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LeadStage $leadStage): Response
    {
        $form = $this->createForm(LeadStageFormType::class, $leadStage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leadStage->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lead_stage.list');
        }

        return $this->render('lead_stage/edit.html.twig', [
            'lead_stage' => $leadStage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lead_stage.delete", methods={"DELETE"})
     */
    public function delete(Request $request, LeadStage $leadStage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$leadStage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($leadStage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lead_stage.list');
    }
}
