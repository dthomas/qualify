<?php

namespace App\Controller;

use App\Entity\Lead;
use App\Entity\LeadInteraction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reports")
 */
class ReportController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="report.list")
     */
    public function index(): Response
    {
        return $this->render('report/index.html.twig', []);
    }

    /**
     * @Route("/leads", name="report.lead")
     */
    public function lead(Request $request): Response
    {
        $products = [];
        $users = [];

        return $this->render('report/lead.html.twig', [
            'products' => json_encode($products),
            'users' => json_encode($users),
        ]);
    }

    /**
     * @Route("/leads/products", name="report.lead.products")
     */
    public function leadProducts(Request $request): Response
    {
        $conn = $this->em->getConnection();
        $sql = <<<SQL
            SELECT p.name AS product, COUNT(l.product_id) AS count
            FROM products p
            JOIN leads l ON p.id = l.product_id
            WHERE l.account_id = :account AND p.account_id = :account
            GROUP BY p.name
            ORDER BY p.name
        SQL;

        $statement = $conn->prepare($sql);
        $statement->execute(['account' => $this->getUser()->getAccount()->getId()]);
        $products = $statement->fetchAllNumeric();

        return $this->render('report/_lead.products.html.twig', [
            'products' => json_encode($products),
            'first' => false,
        ]);
    }

    /**
     * @Route("/leads/sources", name="report.lead.sources")
     */
    public function leadUsers(Request $request): Response
    {
        $first = $request->query->get('first', 1);

        $conn = $this->em->getConnection();
        $sql = <<<SQL
            SELECT ls.name AS source, COUNT(l.lead_source_id) AS count
            FROM lead_sources ls
            JOIN leads l ON ls.id = l.lead_source_id
            WHERE l.account_id = :account AND ls.account_id = :account
            GROUP BY ls.name
            ORDER BY ls.name
        SQL;

        $statement = $conn->prepare($sql);
        $statement->execute(['account' => $this->getUser()->getAccount()->getId()]);
        $sources = $statement->fetchAllNumeric();

        return $this->render('report/_lead.sources.html.twig', [
            'sources' => json_encode($sources),
            'first' => $first === 1 ? true : false,
        ]);
    }

    /**
     * @Route("/leads/time", name="report.lead.time")
     */
    public function leadTime(Request $request): Response
    {
        $first = $request->query->get('first', 1);

        $conn = $this->em->getConnection();
        $sql = <<<SQL
            SELECT DISTINCT(DATE(l.created_at)) AS ldate, COUNT(DATE(l.created_at)) AS count
            FROM leads l
            WHERE l.account_id = :account
            GROUP BY ldate
            ORDER BY ldate
        SQL;

        $statement = $conn->prepare($sql);
        $statement->execute(['account' => $this->getUser()->getAccount()->getId()]);
        $leads = $statement->fetchAllNumeric();

        return $this->render('report/_lead.time.html.twig', [
            'leads' => json_encode($leads),
            'first' => $first === 1 ? true : false,
        ]);
    }

    /**
     * @Route("/leads/stages", name="report.lead.stages")
     */
    public function leadStage(Request $request): Response
    {
        $first = $request->query->get('first', 1);

        $conn = $this->em->getConnection();
        $sql = <<<SQL
            SELECT ls.name AS stage, COUNT(l.lead_stage_id) AS count
            FROM lead_stages ls
            JOIN leads l ON ls.id = l.lead_stage_id
            WHERE l.account_id = :account AND ls.account_id = :account
            GROUP BY ls.name
            ORDER BY ls.name
        SQL;

        $statement = $conn->prepare($sql);
        $statement->execute(['account' => $this->getUser()->getAccount()->getId()]);
        $stages = $statement->fetchAllNumeric();

        return $this->render('report/_lead.stages.html.twig', [
            'stages' => json_encode($stages),
            'first' => $first === 1 ? true : false,
        ]);
    }
}