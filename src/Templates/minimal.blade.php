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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        
        body {
            background-color: #ffffff;
            color: #000000;
            font-size: 14px;
            line-height: 1.5;
            padding: 30px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .company-details h1 {
            margin: 0 0 10px 0;
            font-size: 1.8em;
            font-weight: 600;
        }
        
        .invoice-meta {
            text-align: right;
        }
        
        .invoice-meta h2 {
            margin: 0 0 10px 0;
            font-size: 1.5em;
            color: #333;
        }
        
        .parties {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
        }
        
        .party {
            width: 45%;
        }
        
        .party-title {
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 0.9em;
            color: #666;
        }
        
        .invoice-details {
            margin: 30px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        
        th {
            text-align: left;
            padding: 12px 8px;
            border-bottom: 1px solid #000;
            font-weight: 600;
        }
        
        td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
        }
        
        .summary {
            width: 300px;
            margin-left: auto;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        
        .summary-label {
            font-weight: 500;
        }
        
        .total {
            font-size: 1.1em;
            font-weight: 600;
            border-top: 1px solid #000;
            margin-top: 10px;
            padding-top: 10px;
        }
        
        .notes-section {
            margin: 30px 0;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .notes-title {
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .terms-section {
            margin: 30px 0;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.9em;
        }
        
        .terms-title {
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.9em;
            color: #666;
        }
        
        .signature-section {
            text-align: center;
        }
        
        .signature-line {
            width: 180px;
            height: 1px;
            background-color: #ddd;
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
            <div class="company-details">
                <h1>{{ $company['name'] }}</h1>
                <div>{{ $company['address'] }}</div>
            </div>
            <div class="invoice-meta">
                <h2>{{ __('invoicelite::invoice.invoice') }}</h2>
                <div>{{ $invoice_no }}</div>
                <div>{{ __('invoicelite::invoice.date') }}: {{ $date }}</div>
                <div>{{ __('invoicelite::invoice.due_date') }}: {{ $due_date }}</div>
            </div>
        </div>

        <div class="parties">
            <div class="party">
                <div class="party-title">{{ __('invoicelite::invoice.from') }}</div>
                <div><strong>{{ $company['name'] }}</strong></div>
                <div>{{ $company['email'] }}</div>
                <div>{{ $company['phone'] }}</div>
                <div>{{ $company['website'] }}</div>
            </div>

            <div class="party">
                <div class="party-title">{{ __('invoicelite::invoice.bill_to') }}</div>
                <div><strong>{{ $customer['name'] }}</strong></div>
                <div>{{ $customer['address'] }}</div>
                <div>{{ $customer['email'] }}</div>
                <div>{{ $customer['phone'] }}</div>
            </div>
        </div>

        <div class="invoice-details">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('invoicelite::invoice.description') }}</th>
                        <th width="80">{{ __('invoicelite::invoice.quantity') }}</th>
                        <th width="100">{{ __('invoicelite::invoice.price') }}</th>
                        <th width="120">{{ __('invoicelite::invoice.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    {!! $itemsHtml !!}
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
                    <span class="summary-label">{{ __('invoicelite::invoice.total') }}:</span>
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
            </div>
        </div>
    </div>
</body>
</html>