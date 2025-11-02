<?php

namespace SubhashLadumor1\InvoiceLite\Services;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;

class TemplateManager
{
    /**
     * Render the invoice template with data
     */
    public function render(string $template, array $data, string $language = 'en'): string
    {
        // Set the application locale
        App::setLocale($language);
        
        // Ensure required data is present
        $data = array_merge([
            'invoice_no' => '',
            'date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+30 days')),
            'customer' => [
                'name' => '',
                'email' => '',
                'address' => '',
                'phone' => ''
            ],
            'company' => [
                'name' => config('invoicelite.company.name', 'Your Company Name'),
                'address' => config('invoicelite.company.address', '123 Main Street'),
                'email' => config('invoicelite.company.email', 'info@company.com'),
                'phone' => config('invoicelite.company.phone', '+1 234 567 8900'),
                'website' => config('invoicelite.company.website', 'https://company.com'),
                'logo' => config('invoicelite.company.logo', ''),
            ],
            'items' => [],
            'subtotal' => 0,
            'tax' => 0,
            'tax_amount' => 0,
            'total' => 0,
            'formatted_total' => '$0.00',
            'currency' => config('invoicelite.default_currency', 'USD'),
            'notes' => '',
            'terms' => '',
            'signature' => '',
            'qr_code' => ''
        ], $data);
        
        // Generate QR code for the invoice
        if (class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode')) {
            $qrData = "Invoice: {$data['invoice_no']}\n";
            $qrData .= "Amount: {$data['formatted_total']}\n";
            $qrData .= "Date: {$data['date']}\n";
            $qrData .= "Company: {$data['company']['name']}\n";
            $qrData .= "Customer: {$data['customer']['name']}";
            
            try {
                $data['qr_code'] = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->generate($qrData));
            } catch (\Exception $e) {
                $data['qr_code'] = ''; // QR code generation failed
            }
        } else {
            $data['qr_code'] = ''; // QR code package not available
        }
        
        // Try to render using Laravel's view system
        try {
            // Check if custom template exists
            $viewName = "invoicelite::{$template}";
            if (!View::exists($viewName)) {
                // Fallback to modern template
                $viewName = 'invoicelite::modern';
            }
            
            // Render the view with data
            return View::make($viewName, $data)->render();
        } catch (\Exception $e) {
            // Fallback to file-based rendering if View system fails
            return $this->renderFromFile($template, $data);
        }
    }
    
    /**
     * Fallback rendering method using file content
     */
    protected function renderFromFile(string $template, array $data): string
    {
        $templatePath = __DIR__."/../Templates/{$template}.blade.php";
        
        // Check if custom template exists in published views
        $publishedTemplate = resource_path("views/vendor/invoicelite/{$template}.blade.php");
        if (file_exists($publishedTemplate)) {
            $templatePath = $publishedTemplate;
        }
        
        // If template doesn't exist, fallback to modern
        if (!file_exists($templatePath)) {
            $templatePath = __DIR__.'/../Templates/modern.blade.php';
        }
        
        // Simulate rendering by replacing placeholders
        $content = file_get_contents($templatePath);
        
        // Replace basic placeholders
        $content = str_replace('{{ $invoice_no }}', $data['invoice_no'] ?? '', $content);
        $content = str_replace('{{ $date }}', $data['date'] ?? date('Y-m-d'), $content);
        $content = str_replace('{{ $due_date }}', $data['due_date'] ?? date('Y-m-d', strtotime('+30 days')), $content);
        $content = str_replace('{{ $customer.name }}', $data['customer']['name'] ?? '', $content);
        $content = str_replace('{{ $customer.email }}', $data['customer']['email'] ?? '', $content);
        $content = str_replace('{{ $customer.address }}', $data['customer']['address'] ?? '', $content);
        $content = str_replace('{{ $customer.phone }}', $data['customer']['phone'] ?? '', $content);
        
        // Company information
        $content = str_replace('{{ $company.name }}', $data['company']['name'] ?? '', $content);
        $content = str_replace('{{ $company.address }}', $data['company']['address'] ?? '', $content);
        $content = str_replace('{{ $company.email }}', $data['company']['email'] ?? '', $content);
        $content = str_replace('{{ $company.phone }}', $data['company']['phone'] ?? '', $content);
        $content = str_replace('{{ $company.website }}', $data['company']['website'] ?? '', $content);
        
        // Tax information
        $content = str_replace('{{ $tax }}', $data['tax'] ?? 0, $content);
        
        // Add items table
        $itemsHtml = '';
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $itemName = $item['name'] ?? '';
                $itemQty = $item['qty'] ?? 0;
                $itemPrice = $item['price'] ?? 0;
                $itemTotal = (($item['price'] ?? 0) * ($item['qty'] ?? 1));
                
                $itemsHtml .= "<tr>";
                $itemsHtml .= "<td>{$itemName}</td>";
                $itemsHtml .= "<td>{$itemQty}</td>";
                $itemsHtml .= "<td>" . number_format($itemPrice, 2) . "</td>";
                $itemsHtml .= "<td>" . number_format($itemTotal, 2) . "</td>";
                $itemsHtml .= "</tr>";
            }
        }
        
        $content = str_replace('@itemsTable', $itemsHtml, $content);
        
        // Replace totals with proper formatting
        $content = str_replace('{{ $subtotal }}', number_format($data['subtotal'] ?? 0, 2), $content);
        $content = str_replace('{{ $tax_amount }}', number_format($data['tax_amount'] ?? 0, 2), $content);
        $content = str_replace('{{ $total }}', number_format($data['total'] ?? 0, 2), $content);
        $content = str_replace('{{ $formatted_total }}', $data['formatted_total'] ?? '', $content);
        $content = str_replace('{{ $notes }}', $data['notes'] ?? '', $content);
        $content = str_replace('{{ $terms }}', $data['terms'] ?? '', $content);
        $content = str_replace('{{ $signature }}', $data['signature'] ?? '', $content);
        
        // QR Code
        if (!empty($data['qr_code'])) {
            $content = str_replace('{{ $qr_code }}', $data['qr_code'], $content);
        } else {
            $content = str_replace('<img src="data:image/png;base64,{{ $qr_code }}"', '<!-- QR Code placeholder -->', $content);
        }
        
        return $content;
    }
    
    /**
     * Get available templates
     */
    public function getAvailableTemplates(): array
    {
        return ['modern', 'classic', 'minimal', 'business', 'corporate'];
    }
}