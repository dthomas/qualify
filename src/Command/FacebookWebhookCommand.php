<?php

namespace App\Command;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class FacebookWebhookCommand extends Command
{
    private ContainerBagInterface $params;

    protected static $defaultName = 'app:facebook:webhook';

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Registers Webhook with Facebook Servers');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $webhook_url = $this->params->get('app.facebook.webhook.url');
        $lead_app_id = $this->params->get('app.facebook.lead.app.id');
        $lead_app_secret = $this->params->get('app.facebook.lead.app.secret');
        $lead_app_token = $this->params->get('app.facebook.lead.app.access.token');
        $webhook_verify_token = $this->params->get('app.facebook.webhook.verify.token');

        $io = new SymfonyStyle($input, $output);
        
        $io->info("Please run bin/console app:config:check to verify the configuration.");

        $fb = new Facebook([
            'app_id' => $lead_app_id,
            'app_secret' => $lead_app_secret,
            'default_graph_version' => 'v9.0',
        ]);

        try {
            $response = $fb->post("/{$lead_app_id}/subscriptions", [
                'object' => 'page',
                'callback_url' => $webhook_url,
                'fields' => 'leadgen',
                'include_values' => true,
                'verify_token' => $webhook_verify_token,
            ], $lead_app_token);
        } catch (FacebookResponseException $e) {
            $io->error("Graph returned error {$e->getMessage()}");
            $io->warning("Please run bin/console app:config:check to verify the configuration");
            return Command::FAILURE;
        } catch (FacebookSDKException $e) {
            $io->error("SDK returned error: {$e->getMessage()}");
            $io->warning("Please run bin/console app:config:check to verify the configuration");
            return Command::FAILURE;
        }

        if (!$response->isError()) {
            $io->success("Successfully subscribed to the webhook endpoint.");
            return Command::SUCCESS;
        } else {
            $io->error("An error occured. Please try again!");
            return Command::FAILURE;
        }
    }
}
