<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('invoicelite::invoice.invoice') }} {{ $invoice_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }
        
        body {
            background-color: #ffffff;
            color: #000000;
            font-size: 14px;
            line-height: 1.4;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #000;
        }
        
        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #000;
        }
        
        .header h1 {
            margin: 0;
            font-size: 2em;
            text-transform: uppercase;
        }
        
        .invoice-meta {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            border-bottom: 1px solid #000;
        }
        
        .company-info, .client-info {
            width: 45%;
        }
        
        .section-title {
            font-size: 1.1em;
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        
        .info-item {
            margin-bottom: 5px;
        }
        
        .invoice-details {
            padding: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th {
            border: 1px solid #000;
            text-align: left;
            padding: 8px;
            background-color: #f0f0f0;
        }
        
        td {
            border: 1px solid #000;
            padding: 8px;
        }
        
        .summary {
            width: 300px;
            margin-left: auto;
            padding: 20px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        
        .summary-label {
            font-weight: bold;
        }
        
        .total {
            font-size: 1.1em;
            font-weight: bold;
            border-top: 2px solid #000;
            margin-top: 10px;
            padding-top: 10px;
        }
        
        .notes-section {
            padding: 20px;
            border-top: 1px solid #000;
        }
        
        .notes-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .terms-section {
            padding: 20px;
            border-top: 1px solid #000;
            background-color: #f9f9f9;
        }
        
        .terms-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-top: 1px solid #000;
            font-size: 0.9em;
        }
        
        .signature-section {
            text-align: center;
        }
        
        .signature-line {
            width: 180px;
            height: 1px;
            background-color: #000;
            margin: 20px auto 10px;
        }
        
        .qr-section {
            text-align: center;
        }
        
        .footer-content {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>{{ __('invoicelite::invoice.invoice') }}</h1>
            <p>{{ $invoice_no }}</p>
        </div>

        <div class="invoice-meta">
            <div class="company-info">
                <div class="section-title">{{ __('invoicelite::invoice.from') }}</div>
                <div class="info-item"><strong>{{ $company['name'] }}</strong></div>
                <div class="info-item">{{ $company['address'] }}</div>
                <div class="info-item">{{ $company['email'] }}</div>
                <div class="info-item">{{ $company['phone'] }}</div>
                <div class="info-item">{{ $company['website'] }}</div>
            </div>

            <div class="client-info">
                <div class="section-title">{{ __('invoicelite::invoice.bill_to') }}</div>
                <div class="info-item"><strong>{{ $customer['name'] }}</strong></div>
                <div class="info-item">{{ $customer['address'] }}</div>
                <div class="info-item">{{ $customer['email'] }}</div>
                <div class="info-item">{{ $customer['phone'] }}</div>
            </div>
        </div>

        <div class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('invoicelite::invoice.item') }}</th>
                        <th>{{ __('invoicelite::invoice.qty') }}</th>
                        <th>{{ __('invoicelite::invoice.unit_price') }}</th>
                        <th>{{ __('invoicelite::invoice.line_total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @itemsTable
                </tbody>
            </table>

            <div class="summary">
                <div class="summary-row">
                    <span class="summary-label">{{ __('invoicelite::invoice.subtotal') }}:</span>
                    <span>{{ $subtotal }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">{{ __('invoicelite::invoice.tax') }} ({{ $tax }}%):</span>
                    <span>{{ $tax_amount }}</span>
                </div>
                <div class="summary-row total">
                    <span class="summary-label">{{ __('invoicelite::invoice.invoice_total') }}:</span>
                    <span>{{ $formatted_total }}</span>
                </div>
            </div>
        </div>

        @if(!empty($notes))
        <div class="notes-section">
            <div class="notes-title">{{ __('invoicelite::invoice.notes') }}</div>
            <div>{{ $notes }}</div>
        </div>
        @endif

        @if(!empty($terms))
        <div class="terms-section">
            <div class="terms-title">{{ __('invoicelite::invoice.terms') }}</div>
            <div>{{ $terms }}</div>
        </div>
        @endif

        <div class="footer">
            <div class="signature-section">
                @if(!empty($signature))
                <div><img src="{{ $signature }}" alt="Signature" style="max-width: 180px; max-height: 90px;"></div>
                @else
                <div class="signature-line"></div>
                @endif
                <div>{{ __('invoicelite::invoice.paid') }}</div>
            </div>
            <div class="qr-section">
                @if(!empty($qr_code))
                <img src="data:image/png;base64,{{ $qr_code }}" alt="QR Code" style="width: 90px; height: 90px;">
                <div style="margin-top: 10px; font-size: 11px;">{{ __('invoicelite::invoice.invoice') }} QR</div>
                @endif
            </div>
            <div class="footer-content">
                <p>{{ __('invoicelite::invoice.thank_you') }}</p>
                <p>{{ __('invoicelite::invoice.footer_note') }}</p>
            </div>
        </div>
    </div>
</body>
</html>