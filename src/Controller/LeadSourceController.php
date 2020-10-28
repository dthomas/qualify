<?php

namespace App\Controller;

use App\Entity\LeadSource;
use App\Form\LeadSourceFormType;
use App\Repository\LeadSourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings/lead_sources")
 */
class LeadSourceController extends AbstractController
{
    /**
     * @Route("/", name="lead_source.list", methods={"GET"})
     */
    public function index(Request $request, LeadSourceRepository $leadSourceRepository): Response
    {
        $page = max($request->query->get('page', 1), 1);
        $limit = max($request->query->get('limit', 10), 10);
        $sort = $request->query->get('sort', 'createdAt');
        $dir = $request->query->get('dir', 'desc');
        $dir = $dir === 'asc';

        $lead_sources = $leadSourceRepository->findAllLeadSources($page, $limit, $sort, $dir);
        $maxPages = ceil($lead_sources->count() / $limit);

        return $this->render('lead_source/index.html.twig', [
            'lead_sources' => $lead_sources,
            'limit' => $limit,
            'maxPages' => $maxPages,
            'page' => $page,
            'sort' => $sort,
            'dir' => $dir ? 'asc' : 'desc',
        ]);
    }

    /**
     * @Route("/new", name="lead_source.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $leadSource = new LeadSource();
        $form = $this->createForm(LeadSourceFormType::class, $leadSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leadSource->setCreatedAt(new \DateTime());
            $leadSource->setAccount($this->getUser()->getAccount());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($leadSource);
            $entityManager->flush();

            return $this->redirectToRoute('lead_source.list');
        }

        return $this->render('lead_source/new.html.twig', [
            'lead_source' => $leadSource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lead_source.show", methods={"GET"})
     */
    public function show(LeadSource $leadSource): Response
    {
        return $this->render('lead_source/show.html.twig', [
            'lead_source' => $leadSource,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lead_source.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LeadSource $leadSource): Response
    {
        $form = $this->createForm(LeadSourceFormType::class, $leadSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leadSource->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lead_source.list');
        }

        return $this->render('lead_source/edit.html.twig', [
            'lead_source' => $leadSource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lead_source.delete", methods={"DELETE"})
     */
    public function delete(Request $request, LeadSource $leadSource): Response
    {
        if ($this->isCsrfTokenValid('delete' . $leadSource->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($leadSource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lead_source.list');
    }
}
