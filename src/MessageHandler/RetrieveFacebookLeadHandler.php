<?php

namespace App\MessageHandler;

use App\Entity\Contact;
use App\Entity\FacebookLeadUpdate;
use App\Entity\Lead;
use App\Message\RetrieveFacebookLead;
use App\Repository\FacebookLeadUpdateRepository;
use App\Repository\FacebookPageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RetrieveFacebookLeadHandler implements MessageHandlerInterface
{
    private ContainerBagInterface $params;
    private EntityManagerInterface $em;
    private FacebookLeadUpdateRepository $fbRepository;
    private LoggerInterface $logger;

    public function __construct(
        ContainerBagInterface $params,
        EntityManagerInterface $em,
        FacebookLeadUpdateRepository $fbRepository,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->fbRepository = $fbRepository;
        $this->logger = $logger;
        $this->params = $params;
    }

    public function __invoke(RetrieveFacebookLead $message)
    {
        $update = $this->fbRepository->find($message->getUid());

        if (is_null($update)) {
            $this->logger->critical("RetrieveFacebookLead:: Lead not found", ['id' => $message->getUid()->toRfc4122()]);
            return;
        }

        $page = $update->getFacebookPage();

        $fb = $this->getFacebookHelper();

        try {
            $response = $fb->get(
                "/{$update->getLeadgenId()}",
                $page->getAccessToken()
            );
        } catch (FacebookResponseException $e) {
            $this->logger->critical('Graph returned an error: ' . $e->getMessage());
            return;
        } catch (FacebookSDKException $e) {
            $this->logger->critical('Facebook SDK returned an error: ' . $e->getMessage());
            return;
        }
        $data = $response->getGraphNode()->asArray();

        foreach ($data["field_data"] as $d) {

            switch ($d["name"]) {
                case "FULL_NAME":
                    $name = $d["values"][0];
                    break;
                case "EMAIL":
                    $email = $d["values"][0];
                    break;
                case "PHONE":
                    $phone = $d["values"][0];
                    break;
                default:
                    break;
            }
        }

        $contact = new Contact();
        if (isset($email)) {
            $contact->setEmail($email);
        }

        if (isset($phone)) {
            $contact->setPhone($phone);
        }

        $lead = new Lead();
        $lead
            ->setName($name ?? 'Blank Name')
            ->setContact($contact)
            ->setCreatedAt(new \DateTime())
            ->setAccount($page->getAccount())
            ->setCreatedBy($page->getAccount()->getOwner())
            ->setLeadSource($page->getLeadSource());
        $update
            ->setProcessCount($update->getProcessCount() + 1)
            ->setLeadCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setCreatedLead($lead);

        $this->em->persist($lead);
        $this->em->persist($update);

        $this->em->flush();
    }

    private function getFacebookHelper(): Facebook
    {
        $lead_app_id = $this->params->get('app.facebook.lead.app.id');
        $lead_app_secret = $this->params->get('app.facebook.lead.app.secret');

        return new Facebook([
            'app_id' => $lead_app_id,
            'app_secret' => $lead_app_secret,
            'default_graph_version' => 'v9.0',
        ]);
    }
}
