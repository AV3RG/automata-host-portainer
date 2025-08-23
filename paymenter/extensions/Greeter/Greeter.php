<?php

namespace Paymenter\Extensions\Others\Greeter;

use App\Classes\Extension\Extension;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use App\Events\Invoice\Paid as InvoicePaidEvent;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Invoice;

class Greeter extends Extension
{
    private $fromEmail;
    private $fromName;
    private $emailSubject;

    /**
     * Get extension configuration
     */
    public function getConfig($values = [])
    {
        try {
            return [
                [
                    'name' => 'enable_greeting',
                    'type' => 'checkbox',
                    'label' => 'Enable Greeting Emails',
                    'description' => 'Send greeting emails when invoices are paid',
                    'default' => true,
                ],
                [
                    'name' => 'resend_api_key',
                    'type' => 'text',
                    'label' => 'Resend API Key',
                    'description' => 'Your Resend API key for sending emails',
                    'required' => true,
                ],
                [
                    'name' => 'from_email',
                    'type' => 'email',
                    'label' => 'From Email',
                    'description' => 'The email address to send greetings from (must be verified in Resend)',
                    'required' => true,
                ],
                [
                    'name' => 'from_name',
                    'type' => 'text',
                    'label' => 'From Name',
                    'description' => 'The name to display as sender',
                    'required' => true,
                    'default' => 'Paymenter',
                ],
                [
                    'name' => 'email_subject',
                    'type' => 'text',
                    'label' => 'Email Subject',
                    'description' => 'Subject line for greeting emails (use {name} for personalization)',
                    'required' => true,
                    'default' => 'Thank you for your payment, {name}!',
                ],
                [
                    'name' => 'notice',
                    'type' => 'placeholder',
                    'label' => 'This extension sends personalized greeting emails to customers when their invoices are paid. Configure your Resend API key and email settings above.',
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Greeter Extension: Failed to get config', [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Boot the extension
     */
    public function boot()
    {
        
        // Check if extension is enabled and configured
        if (!$this->config('enable_greeting') || empty($this->config('resend_api_key'))) {
            return;
        }

        // Initialize email settings
        $this->fromEmail = $this->config('from_email');
        $this->fromName = $this->config('from_name');
        $this->emailSubject = $this->config('email_subject');

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

    }

    /**
     * Check if email settings are properly configured
     */
    private function isEmailReady(): bool
    {
        return $this->fromEmail && $this->fromName && $this->emailSubject;
    }

    /**
     * Handle invoice paid event
     */
    private function handleInvoicePaid(InvoicePaidEvent $event)
    {
        // Check if email settings are ready
        if (!$this->isEmailReady()) {
            Log::warning('Greeter Extension: Email settings not ready, skipping email sending', [
                'invoice_id' => $event->invoice->id ?? 'unknown'
            ]);
            return;
        }

        $invoice = $event->invoice;
        $user = $invoice->user;

        if (!$user || !$user->email) {
            Log::warning('Greeter Extension: No user or email found for invoice', [
                'invoice_id' => $invoice->id ?? 'unknown'
            ]);
            return;
        }

        // Send personalized greeting email
        $result = $this->sendGreetingEmail($user, $invoice);

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

    /**
     * Send a personalized greeting email to a user
     */
    private function sendGreetingEmail(User $user, Invoice $invoice): array
    {
        // Check if email settings are ready
        if (!$this->isEmailReady()) {
            return [
                'success' => false,
                'error' => 'Email settings not properly configured'
            ];
        }

        try {
            // Generate personalized email content
            $emailContent = $this->generateEmailContent($user, $invoice);
            $subject = $this->getPersonalizedSubject($user);
            $textContent = $this->generateTextVersion($emailContent);
            
            // Send multipart email with both HTML and text versions
            Mail::send([], [], function($message) use ($user, $subject, $emailContent, $textContent) {
                $message->to($user->email)
                        ->subject($subject)
                        ->from($this->fromEmail, $this->fromName)
                        ->setBody($emailContent, 'text/html')
                        ->addPart($textContent, 'text/plain');
            });

            return [
                'success' => true,
                'email_id' => null,
                'message' => 'Multipart email sent successfully via Laravel mail (HTML + Text)'
            ];

        } catch (\Exception $e) {
            Log::error('Greeter Extension: Failed to send email', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'invoice_id' => $invoice->id
            ]);

            return [
                'success' => false,
                'error' => 'Failed to send email: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate personalized email content
     */
    private function generateEmailContent(User $user, Invoice $invoice): string
    {
        $template = $this->getEmailTemplate();
        
        // Replace placeholders with actual data
        $content = str_replace(
            ['{name}', '{email}', '{invoice_id}', '{amount}', '{date}'],
            [
                $user->name ?? 'Valued Customer',
                $user->email,
                $invoice->id,
                $invoice->total ?? 'N/A',
                $invoice->created_at ? $invoice->created_at->format('M d, Y') : 'N/A'
            ],
            $template
        );
        
        return $content;
    }

    /**
     * Generate personalized subject line
     */
    private function getPersonalizedSubject(User $user): string
    {
        return str_replace(
            '{name}',
            $user->name ?? 'Valued Customer',
            $this->emailSubject
        );
    }

    /**
     * Generate text version of email content
     */
    private function generateTextVersion(string $htmlContent): string
    {
        // Simple HTML to text conversion
        $text = strip_tags($htmlContent);
        $text = str_replace(['&nbsp;', '&amp;', '&lt;', '&gt;'], [' ', '&', '<', '>'], $text);
        return $text;
    }

    /**
     * Get email template
     */
    private function getEmailTemplate(): string
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Thank You for Your Payment!</title>
        </head>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                <div style="background: #4F46E5; color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
                    <h1 style="margin: 0; font-size: 28px;">Thank You!</h1>
                    <p style="margin: 10px 0 0 0; font-size: 16px;">Your payment has been received</p>
                </div>
                
                <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px;">
                    <div style="background: white; padding: 25px; border-radius: 8px; margin: 20px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <h2 style="margin: 0 0 20px 0; color: #1f2937; font-size: 24px;">Hello {name}!</h2>
                        <p style="margin: 0 0 15px 0; font-size: 16px; color: #374151;">Thank you for your payment! We have successfully processed your invoice and your account has been updated.</p>
                        <p style="margin: 0; font-size: 16px; color: #374151;">We appreciate your business and look forward to serving you again.</p>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 30px; color: #6b7280; font-size: 14px;">
                    <p style="margin: 0;">If you have any questions, please don\'t hesitate to contact our support team.</p>
                </div>
            </div>
        </body>
        </html>';
    }

    /**
     * Test the email configuration
     */
    public function testConnection(): array
    {
        // Check if email settings are ready
        if (!$this->isEmailReady()) {
            return [
                'success' => false,
                'error' => 'Email settings not properly configured'
            ];
        }

        try {
            // Create test HTML and text content
            $testHtml = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>Greeter Extension Test</title>
            </head>
            <body style="font-family: Arial, sans-serif; padding: 20px;">
                <h1 style="color: #4F46E5;">Greeter Extension Test Email</h1>
                <p>This is a test email to verify your email configuration is working correctly.</p>
                <p><strong>From:</strong> ' . $this->fromName . ' (' . $this->fromEmail . ')</p>
                <p><strong>Sent:</strong> ' . now()->format('M d, Y H:i:s') . '</p>
            </body>
            </html>';
            
            $testText = "Greeter Extension Test Email\n\n" .
                       "This is a test email to verify your email configuration is working correctly.\n\n" .
                       "From: " . $this->fromName . " (" . $this->fromEmail . ")\n" .
                       "Sent: " . now()->format('M d, Y H:i:s') . "\n";

            // Send multipart test email
            Mail::send([], [], function($message) use ($testHtml, $testText) {
                $message->to($this->fromEmail)
                        ->subject('Greeter Extension Test Email')
                        ->from($this->fromEmail, $this->fromName)
                        ->setBody($testHtml, 'text/html')
                        ->addPart($testText, 'text/plain');
            });
            
            return [
                'success' => true,
                'message' => 'Multipart test email sent successfully (HTML + Text). Check your inbox to verify configuration.',
                'from_email' => $this->fromEmail,
                'from_name' => $this->fromName
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Failed to send test email: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check if extension is enabled
     */
    public function enabled(): bool
    {
        return $this->config('enable_greeting') && !empty($this->config('resend_api_key'));
    }
}
