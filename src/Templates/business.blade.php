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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .invoice-container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .header-section {
            display: flex;
            justify-content: space-between;
            padding: 30px;
            background: linear-gradient(135deg, #2c3e50 0%, #4a6491 100%);
            color: white;
        }
        
        .company-info {
            flex: 1;
        }
        
        .invoice-header {
            flex: 1;
            text-align: right;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .company-details {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .invoice-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .invoice-details {
            font-size: 16px;
        }
        
        .invoice-details div {
            margin-bottom: 8px;
        }
        
        .content-section {
            display: flex;
            padding: 30px;
            gap: 30px;
        }
        
        .billing-section {
            flex: 1;
        }
        
        .billing-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        
        .billing-details {
            font-size: 14px;
            line-height: 1.8;
        }
        
        .billing-details div {
            margin-bottom: 5px;
        }
        
        .items-section {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        
        .items-section th {
            background-color: #3498db;
            color: white;
            text-align: left;
            padding: 15px;
            font-weight: 600;
        }
        
        .items-section td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .items-section tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .items-section tr:last-child td {
            border-bottom: none;
        }
        
        .summary-section {
            width: 350px;
            margin-left: auto;
            margin-top: 30px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .summary-row.total {
            border-top: 2px solid #3498db;
            border-bottom: 2px solid #3498db;
            font-weight: 700;
            font-size: 18px;
            margin-top: 5px;
            padding-top: 15px;
        }
        
        .notes-section {
            padding: 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #eee;
        }
        
        .notes-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .notes-content {
            font-size: 14px;
            line-height: 1.6;
            color: #555;
        }
        
        .terms-section {
            padding: 30px;
            background-color: #fff;
            border-top: 1px solid #eee;
        }
        
        .terms-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .terms-content {
            font-size: 13px;
            line-height: 1.6;
            color: #666;
        }
        
        .footer-section {
            display: flex;
            justify-content: space-between;
            padding: 30px;
            background-color: #2c3e50;
            color: white;
            align-items: center;
        }
        
        .signature-section {
            text-align: center;
        }
        
        .signature-line {
            width: 200px;
            height: 1px;
            background-color: #ddd;
            margin: 40px auto 10px;
        }
        
        .signature-text {
            font-size: 14px;
            color: #95a5a6;
        }
        
        .qr-section {
            text-align: center;
        }
        
        .qr-text {
            font-size: 12px;
            margin-top: 10px;
            color: #95a5a6;
        }
        
        .footer-content {
            text-align: right;
            font-size: 13px;
            color: #95a5a6;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                margin: 0;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header-section">
            <div class="company-info">
                <div class="company-name">{{ $company['name'] }}</div>
                <div class="company-details">{{ $company['address'] }}</div>
                <div class="company-details">{{ $company['email'] }}</div>
                <div class="company-details">{{ $company['phone'] }}</div>
                <div class="company-details">{{ $company['website'] }}</div>
            </div>
            <div class="invoice-header">
                <div class="invoice-title">{{ __('invoicelite::invoice.invoice') }}</div>
                <div class="invoice-details">
                    <div><strong>{{ __('invoicelite::invoice.invoice_no') }}:</strong> {{ $invoice_no }}</div>
                    <div><strong>{{ __('invoicelite::invoice.date') }}:</strong> {{ $date }}</div>
                    <div><strong>{{ __('invoicelite::invoice.due_date') }}:</strong> {{ $due_date }}</div>
                </div>
            </div>
        </div>
        
        <div class="content-section">
            <div class="billing-section">
                <div class="billing-title">{{ __('invoicelite::invoice.bill_to') }}</div>
                <div class="billing-details">
                    <div><strong>{{ $customer['name'] }}</strong></div>
                    <div>{{ $customer['address'] }}</div>
                    <div>{{ $customer['email'] }}</div>
                    <div>{{ $customer['phone'] }}</div>
                </div>
            </div>
        </div>
        
        <table class="items-section">
            <thead>
                <tr>
                    <th width="40%">{{ __('invoicelite::invoice.description') }}</th>
                    <th width="15%">{{ __('invoicelite::invoice.quantity') }}</th>
                    <th width="20%">{{ __('invoicelite::invoice.unit_price') }}</th>
                    <th width="25%">{{ __('invoicelite::invoice.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @itemsTable
            </tbody>
        </table>
        
        <div class="summary-section">
            <div class="summary-row">
                <span>{{ __('invoicelite::invoice.subtotal') }}:</span>
                <span>{{ $subtotal }}</span>
            </div>
            <div class="summary-row">
                <span>{{ __('invoicelite::invoice.tax') }} ({{ $tax }}%):</span>
                <span>{{ $tax_amount }}</span>
            </div>
            <div class="summary-row total">
                <span>{{ __('invoicelite::invoice.total') }}:</span>
                <span>{{ $formatted_total }}</span>
            </div>
        </div>
        
        @if(!empty($notes))
        <div class="notes-section">
            <div class="notes-title">{{ __('invoicelite::invoice.notes') }}</div>
            <div class="notes-content">{{ $notes }}</div>
        </div>
        @endif
        
        @if(!empty($terms))
        <div class="terms-section">
            <div class="terms-title">{{ __('invoicelite::invoice.terms') }}</div>
            <div class="terms-content">{{ $terms }}</div>
        </div>
        @endif
        
        <div class="footer-section">
            <div class="signature-section">
                @if(!empty($signature))
                <div><img src="{{ $signature }}" alt="Signature" style="max-width: 200px; max-height: 100px;"></div>
                @else
                <div class="signature-line"></div>
                @endif
                <div class="signature-text">{{ __('invoicelite::invoice.paid') }}</div>
            </div>
            <div class="qr-section">
                @if(!empty($qr_code))
                <img src="data:image/png;base64,{{ $qr_code }}" alt="QR Code" style="width: 100px; height: 100px;">
                <div class="qr-text">{{ __('invoicelite::invoice.invoice') }} QR</div>
                @endif
            </div>
            <div class="footer-content">
                <div>{{ __('invoicelite::invoice.thank_you') }}</div>
                <div>{{ __('invoicelite::invoice.footer_note') }}</div>
            </div>
        </div>
    </div>
</body>
</html>