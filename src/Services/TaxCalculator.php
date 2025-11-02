<?php

namespace SubhashLadumor1\InvoiceLite\Services;

class TaxCalculator
{
    /**
     * Calculate tax for each item
     */
    public function calculateItemsTax(array $items, float $taxRate): array
    {
        foreach ($items as &$item) {
            $item['tax_rate'] = $taxRate;
            $item['tax_amount'] = $this->calculateTaxAmount($item['price'] * $item['qty'], $taxRate);
            $item['total'] = ($item['price'] * $item['qty']) + $item['tax_amount'];
        }
        
        return $items;
    }

    /**
     * Calculate subtotal from items
     */
    public function calculateSubtotal(array $items): float
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['qty'] ?? 1);
        }
        return $subtotal;
    }

    /**
     * Calculate tax amount
     */
    public function calculateTaxAmount(float $amount, float $taxRate): float
    {
        return $amount * ($taxRate / 100);
    }

    /**
     * Calculate total amount
     */
    public function calculateTotal(float $subtotal, float $taxAmount): float
    {
        return $subtotal + $taxAmount;
    }

    /**
     * Get country-specific tax rules
     */
    public function getCountryTaxRules(string $countryCode): array
    {
        $taxRules = [
            'IN' => ['name' => 'GST', 'rate' => 18.0],      // India - GST
            'US' => ['name' => 'Sales Tax', 'rate' => 7.5], // USA - Sales Tax (varies by state)
            'GB' => ['name' => 'VAT', 'rate' => 20.0],      // UK - VAT
            'DE' => ['name' => 'VAT', 'rate' => 19.0],      // Germany - VAT
            'FR' => ['name' => 'VAT', 'rate' => 20.0],      // France - VAT
            'CA' => ['name' => 'HST/GST', 'rate' => 5.0],   // Canada - Federal GST
            'AU' => ['name' => 'GST', 'rate' => 10.0],      // Australia - GST
            'JP' => ['name' => 'Consumption Tax', 'rate' => 10.0], // Japan - Consumption Tax
            'CN' => ['name' => 'VAT', 'rate' => 13.0],      // China - VAT
            'KR' => ['name' => 'VAT', 'rate' => 10.0],      // South Korea - VAT
            'IT' => ['name' => 'VAT', 'rate' => 22.0],      // Italy - VAT
            'ES' => ['name' => 'VAT', 'rate' => 21.0],      // Spain - VAT
            'NL' => ['name' => 'VAT', 'rate' => 21.0],      // Netherlands - VAT
            'SE' => ['name' => 'VAT', 'rate' => 25.0],      // Sweden - VAT
            'NO' => ['name' => 'VAT', 'rate' => 25.0],      // Norway - VAT
            'DK' => ['name' => 'VAT', 'rate' => 25.0],      // Denmark - VAT
            'FI' => ['name' => 'VAT', 'rate' => 24.0],      // Finland - VAT
            'BE' => ['name' => 'VAT', 'rate' => 21.0],      // Belgium - VAT
            'AT' => ['name' => 'VAT', 'rate' => 20.0],      // Austria - VAT
            'PT' => ['name' => 'VAT', 'rate' => 23.0],      // Portugal - VAT
            'CH' => ['name' => 'VAT', 'rate' => 7.7],       // Switzerland - VAT
            'RU' => ['name' => 'VAT', 'rate' => 20.0],      // Russia - VAT
            'BR' => ['name' => 'ICMS', 'rate' => 18.0],     // Brazil - ICMS
            'MX' => ['name' => 'IVA', 'rate' => 16.0],      // Mexico - IVA
            'ZA' => ['name' => 'VAT', 'rate' => 15.0],      // South Africa - VAT
            'SG' => ['name' => 'GST', 'rate' => 8.0],       // Singapore - GST
            'MY' => ['name' => 'SST', 'rate' => 10.0],      // Malaysia - Sales and Service Tax
            'TH' => ['name' => 'VAT', 'rate' => 7.0],       // Thailand - VAT
            'ID' => ['name' => 'VAT', 'rate' => 11.0],      // Indonesia - VAT
            'PH' => ['name' => 'VAT', 'rate' => 12.0],      // Philippines - VAT
            'VN' => ['name' => 'VAT', 'rate' => 10.0],      // Vietnam - VAT
            'TR' => ['name' => 'VAT', 'rate' => 20.0],      // Turkey - VAT
            'SA' => ['name' => 'VAT', 'rate' => 15.0],      // Saudi Arabia - VAT
            'AE' => ['name' => 'VAT', 'rate' => 5.0],       // UAE - VAT
            'IL' => ['name' => 'VAT', 'rate' => 17.0],      // Israel - VAT
        ];
        
        return $taxRules[strtoupper($countryCode)] ?? ['name' => 'Tax', 'rate' => 0.0];
    }
}