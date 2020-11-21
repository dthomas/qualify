<?php

namespace App\Controller;

use App\Entity\Opportunity;
use App\Entity\OpportunityItem;
use App\Form\AddressFormType;
use App\Form\ContactFormType;
use App\Form\OpportunityItemFormType;
use App\Form\OpportunityType;
use App\Repository\OpportunityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/opportunities")
 */
class OpportunityController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="opportunity.list", methods={"GET"})
     */
    public function index(Request $request, OpportunityRepository $opportunityRepository): Response
    {
        $page = max($request->query->get('page', 1), 1);
        $limit = max($request->query->get('limit', 10), 10);
        $sort = $request->query->get('sort', 'createdAt');
        $dir = $request->query->get('dir', 'desc');
        $dir = $dir === 'asc';

        $opportunities = $opportunityRepository->findAllOpportunities($page, $limit, $sort, $dir);
        $maxPages = ceil($opportunities->count() / $limit);

        return $this->render('opportunity/index.html.twig', [
            'opportunities' => $opportunities,
            'limit' => $limit,
            'maxPages' => $maxPages,
            'page' => $page,
            'sort' => $sort,
            'dir' => $dir ? 'asc' : 'desc',
        ]);
    }

    /**
     * @Route("/new", name="opportunity.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $opportunity = new Opportunity();
        $form = $this->createForm(OpportunityType::class, $opportunity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($opportunity);
            $entityManager->flush();

            return $this->redirectToRoute('opportunity.list');
        }

        return $this->render('opportunity/new.html.twig', [
            'opportunity' => $opportunity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="opportunity.show", methods={"GET"})
     */
    public function show(Opportunity $opportunity): Response
    {
        return $this->render('opportunity/show.html.twig', [
            'opportunity' => $opportunity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="opportunity.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Opportunity $opportunity): Response
    {
        $form = $this->createForm(OpportunityType::class, $opportunity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('opportunity.list');
        }

        return $this->render('opportunity/edit.html.twig', [
            'opportunity' => $opportunity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="opportunity.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Opportunity $opportunity): Response
    {
        if ($this->isCsrfTokenValid('delete' . $opportunity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($opportunity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('opportunity.list');
    }

    /**
     * @Route("/{id}/personal", name="opportunity.personal", methods={"GET"})
     */
    public function opportunityInfo(Request $request, Opportunity $opportunity): Response
    {
        return $this->render('opportunity/_personal.html.twig', [
            'opportunity' => $opportunity,
        ]);
    }

    /**
     * @Route("/{id}/product", name="opportunity.product", methods={"GET", "POST"})
     */
    public function opportunityProduct(Request $request, Opportunity $opportunity): Response
    {
        $item = new OpportunityItem();
        $form = $this->createForm(OpportunityItemFormType::class, $item);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $item->setAccount($this->getUser()->getAccount());
            $item->setOpportunity($opportunity);
            $item->setCreatedBy($this->getUser());
            $item->setCreatedAt(new \DateTime());

            $this->em->persist($item);
            $this->em->flush();
            $item = new OpportunityItem();
            $form = $this->createForm(OpportunityItemFormType::class, $item);
        }

        return $this->render('opportunity/_product.html.twig', [
            'opportunity' => $opportunity,
            'form' => $form->createView(),
            'items' => $opportunity->getOpportunityItems(),
        ]);
    }

    /**
     * @Route("/{id}/product/edit", name="opportunity.product.edit", methods={"GET", "POST"})
     */
    public function opportunityProductEdit(Request $request, OpportunityItem $item): Response
    {
        $form = $this->createForm(OpportunityItemFormType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item->setUpdatedBy($this->getUser());
            $item->setUpdatedAt(new \DateTime());

            $this->em->persist($item);
            $this->em->flush();
            $opportunity = $item->getOpportunity();

            $item = new OpportunityItem();
            $form = $this->createForm(OpportunityItemFormType::class, $item);
            dump($opportunity);

            return $this->render('opportunity/_product.html.twig', [
                'opportunity' => $opportunity,
                'form' => $form->createView(),
                'items' => $opportunity->getOpportunityItems(),
            ]);
        }

        return $this->render('opportunity/_opportunity_item_edit.html.twig', [
            'form' => $form->createView(),
            'opportunity' => $item->getOpportunity(),
            'id' => $item->getId(),
        ]);
    }

    /**
     * @Route("/{id}/contact.edit", name="opportunity.contact.edit", methods={"GET", "PATCH"})
     */
    public function editContact(Request $request, Opportunity $opportunity): Response
    {
        if ($request->query->get('cancel', 0) == 1) {
            return $this->render('opportunity/_contact_block.html.twig', [
                'opportunity' => $opportunity,
            ]);
        }

        $form = $this->createFormBuilder($opportunity)
            ->add('contact', ContactFormType::class, [
                'label' => false,
            ])
            ->setAction($this->generateUrl('opportunity.contact.edit', ['id' => $opportunity->getId()]))
            ->setMethod(Request::METHOD_PATCH)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $opportunity->setUpdatedAt(new \DateTime());
            $opportunity->setUpdatedBy($this->getUser());
            $this->em->persist($opportunity);
            $this->em->flush();

            return $this->render('opportunity/_contact_block.html.twig', [
                'opportunity' => $opportunity,
            ]);
        }

        return $this->render('opportunity/_contact_form.html.twig', [
            'form' => $form->createView(),
            'opportunity' => $opportunity,
        ]);
    }

    /**
     * @Route("/{id}/address.edit", name="opportunity.address.edit", methods={"GET", "PATCH"})
     */
    public function editAddress(Request $request, Opportunity $opportunity): Response
    {
        if ($request->query->get('cancel', 0) == 1) {
            return $this->render('opportunity/_address_block.html.twig', [
                'opportunity' => $opportunity,
            ]);
        }

        $form = $this->createFormBuilder($opportunity)
            ->add('address', AddressFormType::class, [
                'label' => false,
            ])
            ->setAction($this->generateUrl('opportunity.address.edit', ['id' => $opportunity->getId()]))
            ->setMethod(Request::METHOD_PATCH)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $opportunity->setUpdatedAt(new \DateTime());
            $opportunity->setUpdatedBy($this->getUser());
            $this->em->persist($opportunity);
            $this->em->flush();

            return $this->render('opportunity/_address_block.html.twig', [
                'opportunity' => $opportunity,
            ]);
        }

        return $this->render('opportunity/_address_form.html.twig', [
            'form' => $form->createView(),
            'opportunity' => $opportunity,
        ]);
    }

    /**
     * @Route("/{id}/lead", name="opportunity.lead", methods={"GET"})
     */
    public function opportunityLead(Request $request, Opportunity $opportunity)
    {
        return $this->render('opportunity/_lead.html.twig', [
            'opportunity' => $opportunity,
            'lead' => $opportunity->getParentLead(),
            'interactions' => $opportunity->getParentLead()->getLeadInteractions(),
        ]);
    }
}
