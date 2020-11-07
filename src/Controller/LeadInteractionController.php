<?php

namespace App\Controller;

use App\Entity\Lead;
use App\Entity\LeadInteraction;
use App\Entity\LeadSource;
use App\Entity\Product;
use App\Form\AddressFormType;
use App\Form\ContactFormType;
use App\Form\LeadInteractionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeadInteractionController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/leads/{id}/interaction", name="lead.interaction.new")
     */
    public function interaction(Request $request, Lead $lead): Response
    {
        $interaction = new LeadInteraction();
        $form = $this->createForm(LeadInteractionFormType::class, $interaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $interaction->setCreatedAt(new \DateTime());
            $interaction->setParentLead($lead);
            $interaction->setAccount($lead->getAccount());

            $this->em->persist($interaction);
            $this->em->flush();

            $this->addFlash('success', 'lead.interaction.added');
            return $this->redirectToRoute('lead.list');
        }

        return $this->render('lead_interaction/new.html.twig', [
            'form' => $form->createView(),
            'lead' => $lead,
        ]);
    }

    /**
     * @Route("/leads/{id}/info", name="lead.info", methods={"GET"})
     */
    public function leadInfo(Request $request, Lead $lead): Response
    {
        return $this->render('lead_interaction/_lead.html.twig', [
            'lead' => $lead,
        ]);
    }

    /**
     * @Route("/leads/{id}/contact.edit", name="lead.contact.edit", methods={"GET", "PATCH"})
     */
    public function editContact(Request $request, Lead $lead): Response
    {
        if ($request->query->get('cancel', 0) == 1) {
            return $this->render('lead_interaction/_contact_block.html.twig', [
                'lead' => $lead,
            ]);
        }

        $form = $this->createFormBuilder($lead)
            ->add('contact', ContactFormType::class, [
                'label' => false,
            ])
            ->setAction($this->generateUrl('lead.contact.edit', ['id' => $lead->getId()]))
            ->setMethod(Request::METHOD_PATCH)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lead->setUpdatedAt(new \DateTime());
            $lead->setUpdatedBy($this->getUser());
            $this->em->persist($lead);
            $this->em->flush();

            return $this->render('lead_interaction/_contact_block.html.twig', [
                'lead' => $lead,
            ]);
        }

        return $this->render('lead_interaction/_contact_form.html.twig', [
            'form' => $form->createView(),
            'lead' => $lead,
        ]);
    }

    /**
     * @Route("leads/{id}/address.edit", name="lead.address.edit", methods={"GET", "PATCH"})
     */
    public function editAddress(Request $request, Lead $lead): Response
    {
        if ($request->query->get('cancel', 0) == 1) {
            return $this->render('lead_interaction/_address_block.html.twig', [
                'lead' => $lead,
            ]);
        }

        $form = $this->createFormBuilder($lead)
            ->add('address', AddressFormType::class, [
                'label' => false,
            ])
            ->setAction($this->generateUrl('lead.address.edit', ['id' => $lead->getId()]))
            ->setMethod(Request::METHOD_PATCH)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lead->setUpdatedAt(new \DateTime());
            $lead->setUpdatedBy($this->getUser());
            $this->em->persist($lead);
            $this->em->flush();

            return $this->render('lead_interaction/_address_block.html.twig', [
                'lead' => $lead,
            ]);
        }

        return $this->render('lead_interaction/_address_form.html.twig', [
            'form' => $form->createView(),
            'lead' => $lead,
        ]);
    }

    /**
     * @Route("/leads/{id}/product.edit", name="lead.product.edit", methods={"GET", "PATCH"})
     */
    public function editProduct(Request $request, Lead $lead): Response
    {
        if ($request->query->get('cancel', 0) == 1) {
            return $this->render('lead_interaction/_product_block.html.twig', [
                'lead' => $lead,
            ]);
        }

        $form = $this->createFormBuilder($lead)
            ->add('leadSource', EntityType::class, [
                'class' => LeadSource::class,
                'choice_label' => 'name',
                'disabled' => true,
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'placeholder' => 'Please select',
                'required' => false,
            ])
            ->setAction($this->generateUrl('lead.product.edit', ['id' => $lead->getId()]))
            ->setMethod(Request::METHOD_PATCH)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lead->setUpdatedAt(new \DateTime());
            $lead->setUpdatedBy($this->getUser());
            $this->em->persist($lead);
            $this->em->flush();

            return $this->render('lead_interaction/_product_block.html.twig', [
                'lead' => $lead,
            ]);
        }

        return $this->render('lead_interaction/_product_form.html.twig', [
            'form' => $form->createView(),
            'lead' => $lead,
        ]);
    }

    /**
     * @Route("/leads/{id}/user.interaction", name="lead.user.interaction", methods={"GET", "POST"})
     */
    public function userInteraction(Request $request, Lead $lead): Response
    {
        $interaction = new LeadInteraction();
        $form = $this->createForm(LeadInteractionFormType::class, $interaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $interaction->setCreatedAt(new \DateTime());
            $interaction->setParentLead($lead);
            $interaction->setUser($this->getUser());
            $interaction->setAccount($lead->getAccount());

            $this->em->persist($interaction);
            $this->em->flush();

            $this->addFlash('success', 'lead.interaction.added');
            return $this->redirectToRoute('lead.list');
        }

        return $this->render('lead_interaction/_user_interaction.html.twig', [
            'form' => $form->createView(),
            'lead' => $lead,
            'interactions' => $lead->getLeadInteractions(),
        ]);
    }
}
