<?php

namespace Paymenter\Extensions\Others\InvoicePDF;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;

class InvoicePDFController
{
    /**
     * Generate PDF from custom HTML template
     */
    public function generatePDF(Request $request)
    {
        try {
            $invoiceId = $request->input('invoice_id');
            
            if (!$invoiceId) {
                return response()->json(['error' => 'Invoice ID is required'], 400);
            }

            // Get the invoice with all related data
            $invoice = Invoice::with([
                'user',
                'items',
                'transactions.gateway'
            ])->findOrFail($invoiceId);

            // Generate HTML using the custom template
            $html = $this->generateInvoiceHTML($invoice);

            // Generate PDF using DomPDF
                    $pdf = PDF::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'defaultFontSize' => 10,
            'isFontSubsettingEnabled' => true,
            'isPhpEnabled' => false,
            'isJavascriptEnabled' => false,
            'isRemoteEnabled' => false,
            'chroot' => public_path(),
            'tempDir' => storage_path('app/temp'),
            'logOutputFile' => storage_path('logs/pdf.log'),
            'pdfBackend' => 'CPDF'
        ]);

            // Generate the PDF content
            $pdfContent = $pdf->output();

            // Return PDF as response
            return Response::make($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="invoice-' . $invoice->number . '.pdf"',
                'Content-Length' => strlen($pdfContent)
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'PDF generation failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate HTML using custom template
     */
    private function generateInvoiceHTML($invoice)
    {
        // You can provide your custom HTML template here
        // For now, I'll create a professional template as an example
        $html = $this->getCustomTemplate($invoice);
        
        return $html;
    }

    /**
     * Get custom HTML template from external file
     */
    private function getCustomTemplate($invoice)
    {
        // Read the external HTML template file
        $templatePath = __DIR__ . '/template.html';
        
        if (!file_exists($templatePath)) {
            throw new \Exception('Template file not found: ' . $templatePath);
        }
        
        $html = file_get_contents($templatePath);
        
        // Replace placeholder values with dynamic invoice data
        $html = $this->replaceTemplateVariables($html, $invoice);
        
        return $html;
    }
    
    /**
     * Replace template variables with actual invoice data
     */
    private function replaceTemplateVariables($html, $invoice)
    {

        // Basic invoice information
        $html = str_replace('{{{ invoice_number }}}', $invoice->number, $html);
        $html = str_replace('{{{ invoice_date }}}', $invoice->created_at->format('d M Y'), $html);
        // Customer information (Issued To)
        $html = str_replace('{{{ customer_name }}}', $invoice->user->name ?? 'N/A', $html);
        $html = str_replace('{{{ customer_address }}}', $invoice->user->properties()->where('key', 'address')->first()->value ?? 'N/A', $html);
        $html = str_replace('{{{ customer_city }}}', $invoice->user->properties()->where('key', 'city')->first()->value ?? 'N/A', $html);
        $html = str_replace('{{{ customer_state }}}', $invoice->user->properties()->where('key', 'state')->first()->value ?? 'N/A', $html);
        $html = str_replace('{{{ customer_zip }}}', $invoice->user->properties()->where('key', 'zip')->first()->value ?? 'N/A', $html);
        $html = str_replace('{{{ customer_country }}}', $invoice->user->properties()->where('key', 'country')->first()->value ?? 'N/A', $html);
        
        // Company information (Bill To)
        $companyName = config('settings.company_name', 'Your Company');
        $companyAddress = config('settings.company_address', 'Company Address');
        $companyAddress2 = config('settings.company_address2', 'Company Address 2');
        $companyCity = config('settings.company_city', 'City');
        $companyZip = config('settings.company_zip', 'ZIP');
        $companyState = config('settings.company_state', 'State');
        $companyCountry = config('settings.company_country', 'Country');
        
        $html = str_replace('{{{ company_name }}}', $companyName, $html);
        $html = str_replace('{{{ company_address }}}', $companyAddress, $html);
        $html = str_replace('{{{ company_address2 }}}', $companyAddress2, $html);
        $html = str_replace('{{{ company_zip }}}', $companyZip, $html);
        $html = str_replace('{{{ company_city }}}', $companyCity, $html);
        $html = str_replace('{{{ company_state }}}', $companyState, $html);
        $html = str_replace('{{{ company_country }}}', $companyCountry, $html);

        
        // Status button
        $statusClass = $invoice->status === 'paid' ? 'btn-paid' : 'btn-pending';
        $statusText = ucfirst($invoice->status);
        $html = str_replace('{{{ invoice_status_class }}}', $statusClass, $html);
        $html = str_replace('{{{ invoice_status }}}', $statusText, $html);
        
        // Invoice items
        $itemsHtml = '';
        foreach ($invoice->items as $item) {
            $itemsHtml .= '
                    <tr>
                        <td>' . htmlspecialchars($item->description) . '</td>
                        <td class="text-right">' . $item->formattedPrice . '</td>
                        <td class="text-right">' . $item->quantity . '</td>
                        <td class="text-right">' . $item->formattedTotal . '</td>
                    </tr>';
        }
        
        // Replace the sample item with actual items
        $html = str_replace('{{{ invoice_items }}}', $itemsHtml, $html);
        
        // Summary section (subtotal, tax, total)
        // You may need to adjust this based on your invoice structure
        $subtotal = $invoice->formattedTotal->format($invoice->formattedTotal->price - $invoice->formattedTotal->tax);
        $tax = $invoice->formattedTotal->format($invoice->formattedTotal->tax);
        $total = $invoice->formattedTotal->format($invoice->formattedTotal->price);
        
        $html = str_replace('{{{ complete_subtotal }}}', $subtotal, $html);
        $html = str_replace('{{{ complete_tax }}}', $tax, $html);
        $html = str_replace('{{{ complete_total }}}', $total, $html);
        
        // Transactions
        if ($invoice->transactions->isNotEmpty()) {
            $transactionsHtml = '';
            foreach ($invoice->transactions as $transaction) {
                $transactionsHtml .= '
                    <tr>
                        <td>' . ($transaction->created_at ? $transaction->created_at->format('d M Y H:i') : 'N/A') . '</td>
                        <td>' . $transaction->transaction_id . '</td>
                        <td>' . ($transaction->gateway ? $transaction->gateway->name : 'N/A') . '</td>
                        <td class="text-right">' . $transaction->formattedAmount . '</td>
                    </tr>';
            }
            
            // Replace the sample transaction
            $html = str_replace('{{{ invoice_transactions }}}', $transactionsHtml, $html);
        } else {
            $html = str_replace('{{{ invoice_transactions }}}', '', $html);
        }
        
        return $html;
    }
}
