<?php

namespace Paymenter\Extensions\Others\Greeter;

use App\Classes\Extension\Extension;
use Illuminate\Support\Facades\Event;
use App\Events\Invoice\Paid as InvoicePaidEvent;
use Illuminate\Support\Facades\Log;
use Paymenter\Extensions\Others\Greeter\Services\GreeterService;

class Greeter extends Extension
{
    /**
     * Get all the configuration for the extension
     *
     * @param  array  $values
     * @return array
     */
    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'resend_api_key',
                'type' => 'text',
                'label' => 'Resend API Key',
                'description' => 'Your Resend API key for sending emails',
                'required' => true,
                'encrypted' => true,
            ],
            [
                'name' => 'from_email',
                'type' => 'text',
                'label' => 'From Email',
                'description' => 'The email address to send greetings from (must be verified in Resend)',
                'required' => true,
                'default' => 'noreply@yourdomain.com',
            ],
            [
                'name' => 'from_name',
                'type' => 'text',
                'label' => 'From Name',
                'description' => 'The name to display as the sender',
                'required' => true,
                'default' => 'Your Company',
            ],
            [
                'name' => 'email_subject',
                'type' => 'text',
                'label' => 'Email Subject',
                'description' => 'Subject line for the greeting email (use {customer_name} for personalization)',
                'required' => true,
                'default' => 'Welcome {customer_name}! Thank you for your payment!',
            ],
            [
                'name' => 'enable_greeting',
                'type' => 'checkbox',
                'label' => 'Enable Greeting Emails',
                'description' => 'Send greeting emails when invoices are paid',
                'default' => true,
            ],
            [
                'name' => 'notice',
                'type' => 'placeholder',
                'label' => 'This extension sends personalized greeting emails to customers when their invoices are paid. Configure your Resend API key and email settings above.',
            ],
        ];
    }

    public function boot()
    {
        // Check if extension is enabled and configured
        if (!$this->config('enable_greeting') || empty($this->config('resend_api_key'))) {
            Log::info('Greeter Extension: Disabled or not configured. Skipping event listener registration.');
            return;
        }

        // Register the GreeterService with the service container
        $this->app->singleton('Paymenter\Extensions\Others\Greeter\Services\GreeterService', function ($app) {
            return new GreeterService(
                $this->config('resend_api_key'),
                $this->config('from_email'),
                $this->config('from_name'),
                $this->config('email_subject')
            );
        });

        // Listen for invoice paid events
        Event::listen(
            InvoicePaidEvent::class,
            function (InvoicePaidEvent $event) {
                \Log::info('Greeter Extension: Invoice paid event received');
                try {
                    $this->handleInvoicePaid($event);
                } catch (\Exception $e) {
                    Log::error('Greeter Extension: Failed to handle invoice paid event: ' . $e->getMessage(), [
                        'invoice_id' => $event->invoice->id ?? 'unknown',
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    if (config('settings.debug')) {
                        throw $e;
                    }
                }
            }
        );

        Log::info('Greeter Extension: Event listener registered successfully');
    }

    /**
     * Handle invoice paid event
     */
    private function handleInvoicePaid(InvoicePaidEvent $event)
    {
        $invoice = $event->invoice;
        $user = $invoice->user;

        if (!$user || !$user->email) {
            Log::warning('Greeter Extension: No user or email found for invoice', [
                'invoice_id' => $invoice->id ?? 'unknown'
            ]);
            return;
        }

        // Get the greeter service
        $greeterService = app('Paymenter\Extensions\Others\Greeter\Services\GreeterService');

        // Send personalized greeting email
        $result = $greeterService->sendGreetingEmail($user, $invoice);

        if ($result['success']) {
            Log::info('Greeter Extension: Greeting email sent successfully', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'invoice_id' => $invoice->id,
                'email_id' => $result['email_id'] ?? null
            ]);
        } else {
            Log::error('Greeter Extension: Failed to send greeting email', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'invoice_id' => $invoice->id,
                'error' => $result['error'] ?? 'Unknown error'
            ]);
        }
    }

    public function enabled()
    {
        // Extension is enabled, no additional setup required
        Log::info('Greeter Extension: Extension enabled successfully');
    }
}
