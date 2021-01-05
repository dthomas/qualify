<?php

namespace App\Controller\Webhook;

use App\Entity\FacebookLeadUpdate;
use App\Message\RetrieveFacebookLead;
use App\Repository\FacebookPageRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class FacebookController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private EntityManagerInterface $em;
    private FacebookPageRepository $pageRepository;

    public function __construct(EntityManagerInterface $em, FacebookPageRepository $pageRepository)
    {
        $this->em = $em;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @Route("/webhook/facebook", name="webhook.facebook")
     */
    public function __invoke(Request $request): Response
    {
        $webhook_token = $this->getParameter('app.facebook.webhook.verify.token');

        if ($request->query->get('hub_verify_token') === $webhook_token) {
            $this->logger->info("Verify Facebook Webhook", ['token' => $request->query->get('hub_verify_token')]);
            return new Response($request->query->get('hub_challenge'));
        }

        if (($request->getMethod() === Request::METHOD_POST) && $request->headers->has('X-Hub-Signature')) {

            if (!$this->verifyRequestHash($request->getContent(), $request->headers->get('X-Hub-Signature'))) {
                return new Response('', 422);
            }

            $data = json_decode($request->getContent(), true);

            if ($data['object'] === "page") {
                foreach ($data['entry'] as $entry) {
                    $changes = $entry['changes'];
                    $update_id = $entry['id'];
                    $update_time = $entry['time'];
                    $update_received = (new \DateTime())->setTimestamp($update_time);
                    $id_list = [];

                    foreach ($changes as $change) {
                        $field = $change['field'];

                        if ($field === "leadgen") {
                            $leadgen_id = $change['value']['leadgen_id'];
                            $page_id = $change['value']['page_id'];
                            $form_id = $change['value']['form_id'];
                            $adgroup_id = $change['value']['adgroup_id'] ?? 0;
                            $ad_id = $change['value']['ad_id'] ?? 0;
                            $created = $change['value']['created_time'];

                            $page = $this->pageRepository->findOneBy(['fbid' => $page_id, 'subscribed' => true]);

                            if (is_null($page)) {
                                $this->logger->critical("Page not found while processing facebook lead", ['page_id' => $page_id]);
                                break;
                            }

                            $lead_time = (new \DateTime())->setTimestamp($created);

                            $fbUpdate = new FacebookLeadUpdate();
                            $id = Uuid::v4();
                            $fbUpdate
                                ->setId($id)
                                ->setLeadgenId($leadgen_id)
                                ->setPageId($page_id)
                                ->setFacebookPage($page)
                                ->setFormId($form_id)
                                ->setAdgroupId($adgroup_id)
                                ->setAdId($ad_id)
                                ->setCreatedTime($lead_time)
                                ->setAccount($page->getAccount())
                                ->setUpdateId($update_id)
                                ->setUpdateTime($update_received)
                                ->setProcessCount(0)
                                ->setCreatedAt(new \DateTime());

                            $this->em->persist($fbUpdate);
                            $id_list[] = $id;
                        }
                    }
                }

                $this->em->flush();

                foreach ($id_list as $uid) {
                    $this->dispatchMessage(new RetrieveFacebookLead($uid));
                }
            }
        }

        return new Response('', 200);
    }

    private function verifyRequestHash(string $body, string $header_signature): bool
    {
        $appsecret = $this->getParameter('app.facebook.lead.app.secret');
        $expected_signature = hash_hmac('sha1', $body, $appsecret);

        $signature = '';
        if (
            strlen($header_signature) == 45 &&
            substr($header_signature, 0, 5) == 'sha1='
        ) {
            $signature = substr($header_signature, 5);
        }
        if (hash_equals($signature, $expected_signature)) {
            return true;
        } else {
            return false;
        }
    }
}
