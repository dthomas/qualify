<?php

namespace App\Controller;

use App\Entity\Lead;
use App\Form\LeadFormType;
use App\Repository\LeadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/leads")
 */
class LeadController extends AbstractController
{
    /**
     * @Route("/", name="lead.list", methods={"GET"})
     */
    public function index(Request $request,LeadRepository $leadRepository): Response
    {
        $page = max($request->query->get('page', 1), 1);
        $limit = max($request->query->get('limit', 10), 10);
        $sort = $request->query->get('sort', 'createdAt');
        $dir = $request->query->get('dir', 'desc');
        $dir = $dir === 'asc';

        $leads = $leadRepository->findAllLeads($page, $limit, $sort, $dir);
        $maxPages = ceil($leads->count() / $limit);


        return $this->render('lead/index.html.twig', [
            'leads' => $leads,
            'limit' => $limit,
            'maxPages' => $maxPages,
            'page' => $page,
            'sort' => $sort,
            'dir' => $dir ? 'asc' : 'desc',
        ]);
    }

    /**
     * @Route("/new", name="lead.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lead = new Lead();
        $form = $this->createForm(LeadFormType::class, $lead);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lead->setCreatedAt(new \DateTime());
            $lead->setAccount($this->getUser()->getAccount());
            $lead->setCreatedBy($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lead);
            $entityManager->flush();

            $this->addFlash('notice', "Lead created successfully.");

            return $this->redirectToRoute('lead.list');
        }

        return $this->render('lead/new.html.twig', [
            'lead' => $lead,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lead.show", methods={"GET"})
     */
    public function show(Lead $lead): Response
    {
        return $this->render('lead/show.html.twig', [
            'lead' => $lead,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lead.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lead $lead): Response
    {
        $form = $this->createForm(LeadFormType::class, $lead);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lead->setUpdatedAt(new \DateTime());
            $lead->setUpdatedBy($this->getUser());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Lead updated successfully.');

            return $this->redirectToRoute('lead.list');
        }

        return $this->render('lead/edit.html.twig', [
            'lead' => $lead,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lead.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Lead $lead): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lead->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lead);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lead.list');
    }
}
