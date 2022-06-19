<?php

namespace Mazimez\Gigapay\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Mazimez\Gigapay\Exceptions\GigapayException;
use Mazimez\Gigapay\Webhook;

class WebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gigapay:webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create different webhooks for Gigapay events';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            Webhook::createEmployeeCreatedWebhook();
            Webhook::createEmployeeNotifiedWebhook();
            Webhook::createEmployeeClaimedWebhook();
            Webhook::createEmployeeVerifiedWebhook();
            Webhook::createPayoutCreatedWebhook();
            Webhook::createPayoutNotifiedWebhook();
            Webhook::createPayoutAcceptedWebhook();
            Webhook::createInvoiceCreatedWebhook();
            Webhook::createInvoicePaidWebhook();
        } catch (GigapayException $th) {
            $this->error($th->getMessage());
            return 0;
        } catch (Exception $th) {
            $this->error($th->getMessage());
            return 0;
        }

        $this->info('Webhook created, please add event listeners to listen to different events');
    }
}