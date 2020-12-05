<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard")
 */
class DashboardController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }

    /**
     * @Route("/appointments", name="dashboard.appointments.today")
     */
    public function appointments(): Response
    {
        $today = (new \DateTime('today'))->format('Y-m-d H:i:s');
        $tomorrow = (new \DateTime('tomorrow'))->format('Y-m-d H:i:s');
        $conn = $this->em->getConnection();

        $sql = <<<SQL
            SELECT o.name AS customer_name, p.name AS product_name, o.id, i.status, i.appointment_at AS schedule
            FROM opportunity_items i
            JOIN opportunities o ON o.id = i.opportunity_id
            JOIN products p ON p.id = i.product_id
            WHERE i.appointment_at >= :today AND i.appointment_at < :tomorrow AND i.account_id = :account
            ORDER BY i.appointment_at
        SQL;

        $statement = $conn->prepare($sql);
        $statement->execute([
            'account' => $this->getUser()->getAccount()->getId(),
            'today' => $today,
            'tomorrow' => $tomorrow,
        ]);

        return $this->render('dashboard/_appointments.html.twig', [
            'leads' => $statement->fetchAllAssociative(),
        ]);
    }

    /**
     * @Route("/follow_up/opportuninies", name="dashboard.followup.opportunities.today")
     */
    public function followUpOpportunities(): Response
    {
        $today = (new \DateTime('today'))->format('Y-m-d H:i:s');
        $tomorrow = (new \DateTime('tomorrow'))->format('Y-m-d H:i:s');
        $conn = $this->em->getConnection();

        $sql = <<<SQL
            SELECT o.name AS customer_name, p.name AS product_name, o.id, i.status, i.callback_at AS schedule
            FROM opportunity_items i
            JOIN opportunities o ON o.id = i.opportunity_id
            JOIN products p ON p.id = i.product_id
            WHERE i.callback_at >= :today AND i.callback_at < :tomorrow AND i.account_id = :account
            ORDER BY i.callback_at
        SQL;

        $statement = $conn->prepare($sql);
        $statement->execute([
            'account' => $this->getUser()->getAccount()->getId(),
            'today' => $today,
            'tomorrow' => $tomorrow,
        ]);

        return $this->render('dashboard/_callback.opportunities.html.twig', [
            'leads' => $statement->fetchAllAssociative(),
        ]);
    }

    /**
     * @Route("/follow_up/leads", name="dashboard.followup.leads.today")
     */
    public function followUpLeads(): Response
    {
        $today = (new \DateTime('today'))->format('Y-m-d H:i:s');
        $tomorrow = (new \DateTime('tomorrow'))->format('Y-m-d H:i:s');
        $conn = $this->em->getConnection();

        $sql = <<<SQL
            SELECT l.name AS customer_name, p.name AS product_name, l.id, s.name AS stage, i.callback_at AS schedule
            FROM lead_interactions i
            JOIN leads l ON l.id = i.parent_lead_id
            LEFT JOIN products p ON p.id = l.product_id
            LEFT JOIN lead_stages s ON s.id = i.lead_stage_id
            WHERE i.callback_at >= :today AND i.callback_at < :tomorrow AND l.account_id = :account
            ORDER BY i.callback_at
        SQL;

        $statement = $conn->prepare($sql);
        $statement->execute([
            'account' => $this->getUser()->getAccount()->getId(),
            'today' => $today,
            'tomorrow' => $tomorrow,
        ]);

        return $this->render('dashboard/_callback.leads.html.twig', [
            'leads' => $statement->fetchAllAssociative(),
        ]);
    }
}
