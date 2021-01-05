<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class ConfigCheckCommand extends Command
{
    private ContainerBagInterface $params;

    protected static $defaultName = 'app:config:check';

    public function __construct(ContainerBagInterface $params)
    {
        $this->params = $params;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Check Application Configuration and Environment Variables');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $app_url = $this->params->get('app.url');
        $exotel_dial_url = $this->params->get('app.exotel.dial_url');
        $exotel_callerid = $this->params->get('app.exotel.callerid');
        $webhook_url = $this->params->get('app.facebook.webhook.url');
        $lead_app_id = $this->params->get('app.facebook.lead.app.id');
        $lead_app_secret = $this->params->get('app.facebook.lead.app.secret');
        $lead_app_token = $this->params->get('app.facebook.lead.app.access.token');
        $webhook_verify_token = $this->params->get('app.facebook.webhook.verify.token');

        $io = new SymfonyStyle($input, $output);
        $has_error = false;

        if (is_null($app_url)) {
            $has_error = true;
            $io->error("Facebook Webhook URL cannot be blank");
        }

        if (is_null($exotel_dial_url)) {
            $has_error = true;
            $io->error("App ID cannot be blank");
        }
        
        if (is_null($exotel_callerid)) {
            $has_error = true;
            $io->error("App secret cannot be blank");
        }

        if (is_null($webhook_url)) {
            $has_error = true;
            $io->error("Facebook Webhook URL cannot be blank");
        }

        if (is_null($lead_app_id)) {
            $has_error = true;
            $io->error("App ID cannot be blank");
        }
        
        if (is_null($lead_app_secret)) {
            $has_error = true;
            $io->error("App secret cannot be blank");
        }

        if (is_null($lead_app_token)) {
            $has_error = true;
            $io->error("App access token cannot be blank");
        }

        if (is_null($webhook_verify_token)) {
            $has_error = true;
            $io->error("Webhook Verify Token cannot be blank");
        }

        if($has_error) {
            $io->warning("Errors found. Please correct and re-run the command to verify.");
            return Command::FAILURE;
        }

        $io->success("All parameters configured.");
        
        return Command::SUCCESS;
    }
}
