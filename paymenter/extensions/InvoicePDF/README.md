# InvoicePDF Extension

This extension provides server-side PDF generation for invoices using custom HTML templates and DomPDF.

## Features

- **Server-side PDF generation** - No client-side compatibility issues
- **Custom HTML templates** - Full control over PDF appearance
- **Professional styling** - Clean, business-appropriate invoice design
- **Complete invoice data** - Includes all invoice details, items, and transactions
- **Secure** - Uses authentication middleware

## Installation

1. The extension is automatically loaded when placed in the `extensions/InvoicePDF/` directory
2. No additional configuration required
3. Routes are automatically registered

## Usage

### Frontend (JavaScript)

```javascript
function downloadInvoiceAsPDF() {
    fetch('/invoices/download-pdf', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/pdf'
        },
        body: JSON.stringify({
            invoice_id: 'INVOICE_ID_HERE'
        })
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'invoice.pdf';
        a.click();
        window.URL.revokeObjectURL(url);
    });
}
```

### Backend Route

```
POST /invoices/download-pdf
```

**Parameters:**
- `invoice_id` (required): The ID of the invoice to generate PDF for

**Response:**
- PDF file with proper headers for download

## Customizing the HTML Template

### Location
The HTML template is located in `InvoicePDFController.php` in the `getCustomTemplate()` method.

### Current Template Features
- Company header with logo area
- Invoice title and number
- Bill To and From sections
- Invoice details (date, due date, status)
- Invoice items table
- Payment history table (if transactions exist)
- Total amount section
- Professional styling with CSS

### How to Customize

1. **Edit the `getCustomTemplate()` method** in `InvoicePDFController.php`
2. **Replace the entire HTML string** with your custom template
3. **Use invoice data variables** like:
   - `$invoice->number` - Invoice number
   - `$invoice->user->name` - Customer name
   - `$invoice->items` - Invoice items collection
   - `$invoice->transactions` - Payment transactions
   - `$invoice->formattedTotal` - Total amount

### Example Custom Template Structure

```php
private function getCustomTemplate($invoice)
{
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Invoice ' . $invoice->number . '</title>
        <style>
            /* Your custom CSS here */
        </style>
    </head>
    <body>
        <!-- Your custom HTML structure here -->
        <div class="header">
            <h1>Invoice #' . $invoice->number . '</h1>
        </div>
        
        <div class="customer-info">
            <h3>Bill To:</h3>
            <p>' . $invoice->user->name . '</p>
            <p>' . $invoice->user->address . '</p>
        </div>
        
        <!-- Add more sections as needed -->
    </body>
    </html>';
    
    return $html;
}
```

### Available Invoice Data

```php
$invoice = Invoice::with([
    'user',           // Customer information
    'items',          // Invoice line items
    'transactions.gateway', // Payment history
    'total'           // Invoice totals
])->findOrFail($invoiceId);
```

**User Data:**
- `$invoice->user->name`
- `$invoice->user->address`
- `$invoice->user->city`
- `$invoice->user->state`
- `$invoice->user->country`
- `$invoice->user->zip`

**Invoice Data:**
- `$invoice->number`
- `$invoice->status`
- `$invoice->created_at`
- `$invoice->due_date`
- `$invoice->formattedTotal`

**Items Data:**
```php
foreach ($invoice->items as $item) {
    $item->description
    $item->formattedPrice
    $item->quantity
    $item->formattedTotal
}
```

**Transactions Data:**
```php
foreach ($invoice->transactions as $transaction) {
    $transaction->created_at
    $transaction->transaction_id
    $transaction->gateway->name
    $transaction->formattedAmount
}
```

## Styling Guidelines

### CSS Best Practices for PDF
- Use **standard fonts** (Arial, Helvetica, Times New Roman)
- Avoid **modern CSS functions** (oklch, hsl, lab)
- Use **solid colors** instead of gradients
- Keep **layout simple** (avoid complex flexbox/grid)
- Use **print-friendly units** (mm, pt, in)

### Recommended CSS Properties
```css
body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    line-height: 1.4;
    color: #333;
    margin: 0;
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}
```

## Testing

1. **Test the extension loading:**
   ```
   GET /test-invoice-pdf
   ```
   Should return: `{"message": "InvoicePDF extension is working!"}`

2. **Test PDF generation:**
   - Navigate to any invoice page
   - Click the download button
   - PDF should generate and download

## Troubleshooting

### Common Issues

1. **Extension not loading:**
   - Check file permissions
   - Verify namespace matches directory structure
   - Check Laravel logs for errors

2. **PDF generation fails:**
   - Verify DomPDF is installed
   - Check invoice data exists
   - Review server error logs

3. **Template not rendering:**
   - Check HTML syntax
   - Verify CSS compatibility
   - Test with simple HTML first

### Debug Mode

To enable debug mode, modify the `generatePDF` method:

```php
} catch (\Exception $e) {
    \Log::error('PDF Generation Error: ' . $e->getMessage());
    \Log::error('Stack trace: ' . $e->getTraceAsString());
    
    return response()->json([
        'error' => 'PDF generation failed: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], 500);
}
```

## Security

- **Authentication required** - Uses `auth` middleware
- **CSRF protection** - Requires valid CSRF token
- **Input validation** - Invoice ID is validated
- **Data sanitization** - HTML output is properly escaped

## Performance

- **Efficient queries** - Uses eager loading for relationships
- **Memory management** - PDF content is streamed
- **Caching ready** - Template can be cached if needed

## Future Enhancements

- **Template caching** - Store compiled templates
- **Multiple templates** - Different designs for different invoice types
- **Email integration** - Send PDFs via email
- **Batch processing** - Generate multiple PDFs
- **Custom fonts** - Support for company branding fonts
