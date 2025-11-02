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
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            font-size: 13px;
            line-height: 1.4;
        }
        
        .invoice-container {
            max-width: 850px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #eee;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            padding: 30px;
            border-bottom: 3px solid #e74c3c;
        }
        
        .company-section {
            flex: 1;
        }
        
        .company-logo {
            max-width: 200px;
            max-height: 80px;
            margin-bottom: 15px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .company-info {
            font-size: 13px;
            color: #7f8c8d;
            line-height: 1.6;
        }
        
        .invoice-section {
            text-align: right;
            flex: 1;
        }
        
        .invoice-title {
            font-size: 36px;
            font-weight: 700;
            color: #e74c3c;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .invoice-meta {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .invoice-meta div {
            margin-bottom: 8px;
        }
        
        .details-section {
            display: flex;
            padding: 30px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        
        .bill-to {
            flex: 1;
        }
        
        .bill-to-title {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        
        .bill-to-details {
            font-size: 13px;
            line-height: 1.7;
        }
        
        .bill-to-details div {
            margin-bottom: 5px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .items-table th {
            background-color: #34495e;
            color: white;
            text-align: left;
            padding: 15px;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
        }
        
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .summary-section {
            width: 300px;
            margin-left: auto;
            padding: 0 30px 30px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .summary-row.total {
            border-top: 2px solid #e74c3c;
            border-bottom: 2px solid #e74c3c;
            font-weight: 700;
            font-size: 16px;
            margin-top: 5px;
            padding-top: 15px;
            color: #e74c3c;
        }
        
        .notes-section {
            padding: 30px;
            background-color: #fff;
            border-top: 1px solid #eee;
        }
        
        .notes-title {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        
        .notes-content {
            font-size: 13px;
            line-height: 1.6;
            color: #555;
        }
        
        .terms-section {
            padding: 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #eee;
        }
        
        .terms-title {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        
        .terms-content {
            font-size: 12px;
            line-height: 1.6;
            color: #666;
        }
        
        .footer-section {
            display: flex;
            justify-content: space-between;
            padding: 30px;
            background-color: #2c3e50;
            color: white;
        }
        
        .signature-section {
            text-align: center;
            flex: 1;
        }
        
        .signature-line {
            width: 180px;
            height: 1px;
            background-color: #95a5a6;
            margin: 30px auto 10px;
        }
        
        .signature-text {
            font-size: 13px;
            color: #bdc3c7;
        }
        
        .qr-section {
            text-align: center;
            flex: 1;
        }
        
        .qr-text {
            font-size: 12px;
            margin-top: 10px;
            color: #bdc3c7;
        }
        
        .footer-content {
            text-align: right;
            flex: 1;
            font-size: 12px;
            color: #bdc3c7;
            line-height: 1.6;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                margin: 0;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="company-section">
                @if(!empty($company['logo']))
                <img src="{{ $company['logo'] }}" alt="Company Logo" class="company-logo">
                @endif
                <div class="company-name">{{ $company['name'] }}</div>
                <div class="company-info">
                    <div>{{ $company['address'] }}</div>
                    <div>{{ $company['email'] }}</div>
                    <div>{{ $company['phone'] }}</div>
                    <div>{{ $company['website'] }}</div>
                </div>
            </div>
            <div class="invoice-section">
                <div class="invoice-title">{{ __('invoicelite::invoice.invoice') }}</div>
                <div class="invoice-meta">
                    <div><strong>{{ __('invoicelite::invoice.invoice_no') }}:</strong> {{ $invoice_no }}</div>
                    <div><strong>{{ __('invoicelite::invoice.date') }}:</strong> {{ $date }}</div>
                    <div><strong>{{ __('invoicelite::invoice.due_date') }}:</strong> {{ $due_date }}</div>
                </div>
            </div>
        </div>
        
        <div class="details-section">
            <div class="bill-to">
                <div class="bill-to-title">{{ __('invoicelite::invoice.bill_to') }}</div>
                <div class="bill-to-details">
                    <div><strong>{{ $customer['name'] }}</strong></div>
                    <div>{{ $customer['address'] }}</div>
                    <div>{{ $customer['email'] }}</div>
                    <div>{{ $customer['phone'] }}</div>
                </div>
            </div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%">{{ __('invoicelite::invoice.description') }}</th>
                    <th width="15%">{{ __('invoicelite::invoice.quantity') }}</th>
                    <th width="15%">{{ __('invoicelite::invoice.unit_price') }}</th>
                    <th width="20%">{{ __('invoicelite::invoice.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                {!! $itemsHtml !!}
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
                <div><img src="{{ $signature }}" alt="Signature" style="max-width: 180px; max-height: 90px;"></div>
                @else
                <div class="signature-line"></div>
                @endif
                <div class="signature-text">{{ __('invoicelite::invoice.paid') }}</div>
            </div>
            <div class="qr-section">
                @if(!empty($qr_code))
                <img src="data:image/png;base64,{{ $qr_code }}" alt="QR Code" style="width: 90px; height: 90px;">
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