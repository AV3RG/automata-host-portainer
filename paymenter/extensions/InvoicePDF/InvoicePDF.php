<?php

namespace Paymenter\Extensions\Others\InvoicePDF;

use App\Classes\Extension\Extension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePDF extends Extension
{
    /**
     * Get all the configuration for the extension
     * 
     * @param array $values
     * @return array
     */
    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'Notice',
                'type' => 'placeholder',
                'label' => 'This extension provides PDF download functionality for invoices using custom HTML templates.',
            ],
        ];
    }

    public function enabled()
    {
        // Extension is enabled, no additional setup required
    }

    public function boot()
    {
        require __DIR__ . '/routes/web.php';
    }
}
