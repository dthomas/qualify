<?php

namespace App\Controller;

use App\Entity\Lead;
use App\Entity\Opportunity;
use App\Form\ExotelStatusCallbackType;
use App\Repository\LeadInteractionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TelephoneController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private EntityManagerInterface $em;

    private HttpClientInterface $client;

    public function __construct(EntityManagerInterface $em, HttpClientInterface $client)
    {
        $this->em = $em;
        $this->client = $client;
    }

    /**
     * @Route("/entity/{id}/dial", name="entity.dial")
     */
    public function dial(Request $request, $id): Response
    {
        $module = $request->query->get('module');

        if (is_null($module) || array_search($module, ['lead', 'opportunity'], true) === false) {
            return $this->invalidResponse();
        }

        switch ($module) {
            case 'lead':
                $entity = $this->em->getRepository(Lead::class)->find($id);
                break;
            case 'opportunity':
                $entity = $this->em->getRepository(Opportunity::class)->find($id);
                break;
        }

        if(is_null($entity)) {
            return $this->invalidResponse();
        }

        $url = $this->getParameter('app.exotel.dial_url');
        $success = true;
        try {
            $response = $this->client->request('POST', $url, [
                'body' => [
                    'From' => "+91{$this->getUser()->getPhone()}",
                    'To' => "+91{$entity->getContact()->getPhone()}",
                    'CallerId' => $this->getParameter('app.exotel.callerid'),
                    // 'StatusCallbackEvents[0]' => 'terminal',
                    // 'StatusCallbackContentType' => 'application/json',
                    'CustomField' => 'module=lead&id=' . $entity->getId(),
                ],
            ]);
        } catch (TransportExceptionInterface $e) {
            $success = false;
            $this->logger->alert($e->getMessage(), [$e->getCode()]);
        }

        if ($success) {
            return new Response('<span class="iconify" data-icon="ic:baseline-ring-volume" data-inline="false"></span>');
        }

        return $this->invalidResponse();
    }

    private function invalidResponse(): Response
    {
        return new Response('<span class="iconify" data-icon="ion:alert-circle-sharp" data-inline="false"></span>');
    }
}
