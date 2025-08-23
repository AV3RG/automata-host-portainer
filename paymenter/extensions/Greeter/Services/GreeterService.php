<?php

namespace Paymenter\Extensions\Others\Greeter\Services;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Resend\Resend;
use Resend\Exceptions\ResendException;

class GreeterService
{
    private $resend;
    private $fromEmail;
    private $fromName;
    private $emailSubject;

    public function __construct(string $apiKey, string $fromEmail, string $fromName, string $emailSubject = null)
    {
        $this->resend = new Resend($apiKey);
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->emailSubject = $emailSubject ?? 'Welcome {customer_name}! Thank you for your payment!';
    }

    /**
     * Send a personalized greeting email to a user
     */
    public function sendGreetingEmail(User $user, Invoice $invoice): array
    {
        try {
            // Generate personalized email content
            $emailContent = $this->generateEmailContent($user, $invoice);
            
            // Send email via Resend
            $response = $this->resend->emails->create([
                'from' => $this->fromName . ' <' . $this->fromEmail . '>',
                'to' => [$user->email],
                'subject' => $this->getPersonalizedSubject($user),
                'html' => $emailContent,
                'text' => $this->generateTextVersion($emailContent),
            ]);

            return [
                'success' => true,
                'email_id' => $response->id ?? null,
                'message' => 'Email sent successfully'
            ];

        } catch (ResendException $e) {
            Log::error('Greeter Extension: Resend API error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'invoice_id' => $invoice->id
            ]);

            return [
                'success' => false,
                'error' => 'Resend API error: ' . $e->getMessage()
            ];

        } catch (\Exception $e) {
            Log::error('Greeter Extension: General error sending email', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'invoice_id' => $invoice->id
            ]);

            return [
                'success' => false,
                'error' => 'General error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate personalized email content
     */
    private function generateEmailContent(User $user, Invoice $invoice): string
    {
        // Get invoice details
        $invoiceItems = $invoice->items ?? collect();
        $totalAmount = $invoice->total ?? 0;
        $currency = $invoice->currency ?? 'USD';
        
        // Format the amount
        $formattedAmount = number_format($totalAmount, 2) . ' ' . $currency;

        // Generate HTML email content
        $html = $this->getEmailTemplate();
        
        // Replace placeholders with actual data
        $html = str_replace('{customer_name}', $user->name ?? $user->username ?? 'Valued Customer', $html);
        $html = str_replace('{customer_email}', $user->email, $html);
        $html = str_replace('{invoice_id}', $invoice->id, $html);
        $html = str_replace('{invoice_total}', $formattedAmount, $html);
        $html = str_replace('{payment_date}', $invoice->paid_at ? $invoice->paid_at->format('F j, Y') : 'Today', $html);
        
        // Generate invoice items list
        $itemsList = '';
        foreach ($invoiceItems as $item) {
            $itemAmount = number_format($item->price ?? 0, 2) . ' ' . $currency;
            $itemsList .= '<tr><td>' . htmlspecialchars($item->description ?? 'Service') . '</td><td>' . $itemAmount . '</td></tr>';
        }
        $html = str_replace('{invoice_items}', $itemsList, $html);

        return $html;
    }

    /**
     * Get personalized email subject
     */
    private function getPersonalizedSubject(User $user): string
    {
        return str_replace('{customer_name}', $user->name ?? $user->username ?? 'Valued Customer', $this->emailSubject);
    }

    /**
     * Generate plain text version of the email
     */
    private function generateTextVersion(string $html): string
    {
        // Simple HTML to text conversion
        $text = strip_tags($html);
        $text = str_replace('&nbsp;', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        return $text;
    }

    /**
     * Get the email template
     */
    private function getEmailTemplate(): string
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Welcome to Automata</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .greeting { font-size: 24px; margin-bottom: 20px; color: #2c3e50; }
                .message { font-size: 16px; margin-bottom: 25px; }
                .invoice-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea; }
                .invoice-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                .invoice-table th, .invoice-table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
                .invoice-table th { background-color: #f8f9fa; font-weight: bold; }
                .total { font-size: 18px; font-weight: bold; color: #2c3e50; text-align: right; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 14px; }
                .button { display: inline-block; background: #667eea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>🎉 Welcome to Automata</h1>
                <p>Thank you for your payment</p>
            </div>
            
            <div class="content">
                <div class="greeting">Hello {customer_name}!</div>
                
                <div class="message">
                    We\'ve received your payment and want to express our sincere gratitude for choosing our services. 
                    Enjoy your servers and have a great day!
                </div>
                
                <div class="message">
                    If you have any questions about your servers or need assistance with your services, 
                    please don\'t hesitate to contact our support team. We\'re here to help!
                </div>
            </div>
            
        </body>
        </html>';
    }

    /**
     * Test the Resend API connection
     */
    public function testConnection(): array
    {
        try {
            // Try to get account information to test the API key
            $account = $this->resend->accounts->get();
            
            return [
                'success' => true,
                'message' => 'Connection successful',
                'account' => $account
            ];
        } catch (ResendException $e) {
            return [
                'success' => false,
                'error' => 'Resend API error: ' . $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'General error: ' . $e->getMessage()
            ];
        }
    }
}
