<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\FacebookPage;
use App\Entity\LeadSource;
use Doctrine\ORM\EntityManagerInterface;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/settings/facebook")
 */
class FacebookLeadsController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function getFacebookHelper(): Facebook
    {
        $lead_app_id = $this->getParameter('app.facebook.lead.app.id');
        $lead_app_secret = $this->getParameter('app.facebook.lead.app.secret');

        return new Facebook([
            'app_id' => $lead_app_id,
            'app_secret' => $lead_app_secret,
            'default_graph_version' => 'v9.0',
        ]);
    }

    /**
     * @Route("/leads", name="settings.facebook.leads")
     */
    public function index(): Response
    {
        $fb = $this->getFacebookHelper();

        $redirect_url = $this->generateUrl("settings.facebook.authorized", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = [
            'email', 'leads_retrieval', 'pages_manage_ads',
            'pages_read_engagement', 'pages_show_list', 'pages_manage_metadata',
        ];

        $login_url = $helper->getLoginUrl($redirect_url, $permissions);

        /** @var Account $account */
        $account = $this->getUser()->getAccount();
        $settings = $account->getSettings();
        $pages = $account->getFacebookPages();

        return $this->render('facebook_leads/index.html.twig', [
            'facebook_login_url' => $login_url,
            'linked_account' => isset($settings['facebook_access_token']),
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/authorized", name="settings.facebook.authorized")
     */
    public function authorized(Request $request)
    {
        $fb = $this->getFacebookHelper();

        $helper = $fb->getRedirectLoginHelper();

        try {
            $access_token = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->redirectToRoute('settings.facebook.leads');
        } catch (FacebookSDKException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->redirectToRoute('settings.facebook.leads');
        }

        if (!isset($access_token)) {
            if ($helper->getError()) {
                $this->addFlash('notice', $helper->getErrorDescription());
            } else {
                $this->addFlash('notice', "Bad response received from Facebook. Please try again!");
            }
            return $this->redirectToRoute('settings.facebook.leads');
        }

        $oauth_client = $fb->getOAuth2Client();
        // var_dump($oauth_client->debugToken($access_token));

        if (!$access_token->isLongLived()) {
            try {
                $access_token = $oauth_client->getLongLivedAccessToken($access_token);
            } catch (FacebookSDKException $e) {
                $this->addFlash('notice', $e->getMessage());
                return $this->redirectToRoute('settings.facebook.leads');
            }
        }

        /** @var Account $account */
        $account = $this->getUser()->getAccount();
        $settings = $account->getSettings();
        $settings['facebook_access_token'] = $access_token->getValue();
        $account->setSettings($settings);

        $this->em->persist($account);
        $this->em->flush();

        $this->addFlash('notice', "Facebook Account Connected");
        return $this->redirectToRoute('settings.facebook.leads');
    }

    /**
     * @Route("/pages", name="settings.facebook.pages")
     */
    public function retriveFacebookPages(): Response
    {
        $fb = $this->getFacebookHelper();
        /** @var Account $account */
        $account = $this->getUser()->getAccount();
        $settings = $account->getSettings();

        try {
            $response = $fb->get('/me/accounts', $settings['facebook_access_token']);
        } catch (FacebookResponseException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->redirectToRoute('settings.facebook.leads');
        } catch (FacebookSDKException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->redirectToRoute('settings.facebook.leads');
        }

        foreach ($response->getGraphEdge()->asArray() as $p) {
            /** @var FacebookPage $page */
            $page = $this->em->getRepository(FacebookPage::class)->findOneBy(['fbid' => $p['id']]);
            if (is_null($page)) {
                $page = new FacebookPage();
                $page->setSubscribed(false);
                $page->setAccount($account);
                $page->setFbid($p['id']);
                $page->setCreatedAt(new \DateTime());
            } else {
                $page->setUpdatedAt(new \DateTime());
            }

            $page->setName($p['name']);
            $page->setAccessToken($p['access_token']);
            $this->em->persist($page);
        }
        $this->em->flush();

        return $this->render('facebook_leads/pages.html.twig', [
            'pages' => $account->getFacebookPages(),
        ]);
    }

    /**
     * @Route("/subscribe/{id}", name="settings.facebook.subscribe.page")
     */
    public function subscribePage($id): Response
    {
        $account = $this->getUser()->getAccount();
        $settings = $account->getSettings();
        /** @var FacebookPage $page */
        $page = $this->em->getRepository(FacebookPage::class)->findOneBy(['fbid' => $id, 'subscribed' => false]);

        if (is_null($page)) {
            $this->addFlash('notice', "You are not authorized to subscribe to this page.");
            return $this->redirectToRoute('settings.facebook.leads');
        }

        $fb = $this->getFacebookHelper();
        try {
            $response = $fb->post(
                "/$id/subscribed_apps",
                ['subscribed_fields' => 'leadgen'],
                $page->getAccessToken()
            );
        } catch (FacebookResponseException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->redirectToRoute('settings.facebook.leads');
        } catch (FacebookSDKException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->redirectToRoute('settings.facebook.leads');
        }

        if ($response->isError()) {
            $this->addFlash('notice', "Unable to subscribe to the page. Please try again!");
            return $this->redirectToRoute('settings.facebook.leads');
        }

        $page->setSubscribed(true);
        $this->em->persist($page);
        $this->em->flush();

        return $this->redirectToRoute('settings.facebook.leads');
    }

    /**
     * @Route("/unsubscribe/{id}", name="settings.facebook.unsubscribe.page")
     */
    public function unsubscribePage($id): Response
    {
        $account = $this->getUser()->getAccount();
        $settings = $account->getSettings();
        /** @var FacebookPage $page */
        $page = $this->em->getRepository(FacebookPage::class)->findOneBy(['fbid' => $id, 'subscribed' => true]);

        if (is_null($page)) {
            $this->addFlash('notice', "You are not subscribed to this page.");
            return $this->redirectToRoute('settings.facebook.leads');
        }

        $fb = $this->getFacebookHelper();
        try {
            $response = $fb->delete(
                "/$id/subscribed_apps",
                [],
                $settings['facebook_pages'][$id]['access_token']
            );
        } catch (FacebookResponseException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->redirectToRoute('settings.facebook.leads');
        } catch (FacebookSDKException $e) {
            $this->addFlash('notice', $e->getMessage());
            return $this->redirectToRoute('settings.facebook.leads');
        }

        if ($response->isError()) {
            $this->addFlash('notice', "Unable to unsubscribe to the page. Please try again!");
            return $this->redirectToRoute('settings.facebook.leads');
        }

        $page->setSubscribed(false);
        $this->em->persist($page);
        $this->em->flush();

        return $this->redirectToRoute('settings.facebook.leads');
    }

    /**
     * @Route("/form/{id}/source", name="settings.facebook.page.lead_source", methods={"GET", "POST"})
     */
    public function setPageLeadSource(FacebookPage $page, Request $request)
    {
        $form = $this->createFormBuilder($page)
            ->add('leadSource', EntityType::class, [
                'class' => LeadSource::class,
                'choice_label' => 'name',
                'label' => false,
            ])
            // ->add('save', SubmitType::class)
            ->setAction($this->generateUrl('settings.facebook.page.lead_source', ['id' => $page->getId()]))
            ->setMethod("POST")
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page->setLeadSource($form->get('leadSource')->getData());
            $this->em->persist($page);
            $this->em->flush();

            return $this->render('facebook_leads/_lead_source_label.html.twig', [
                'page' => $page,
            ]);
        }

        return $this->render('facebook_leads/_lead_source_form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
        ]);
    }
}
