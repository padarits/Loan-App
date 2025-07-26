<!DOCTYPE html>  
<html>  
<head>  
    <meta charset="utf-8">  
    <title>Loan Agreement #{{ $contract_number }}</title>  
    <style>  
        body {  
            font-family: sans-serif;  
            font-size: 12pt;  
            color: #000;  
            margin: 0;  
            padding: 0;  
        }  
        .header {  
            margin-bottom: 20px;  
            padding: 0px;  
        }  
        .header h1 {  
            margin: 0;  
            padding: 0;  
        }  
        .contract-details {  
            width: 100%;  
            margin-bottom: 5px;  
            table-layout: fixed;  
            border-collapse: collapse;  
        }  
        .contract-details td {  
            padding: 0px;  
            vertical-align: top;  
            width: 50%;  
        }  
        .section-title {  
            font-weight: bold;  
            margin-top: 20px;  
            font-size: 12pt;  
        }  
        .terms {  
            margin: 20px 0;  
        }  
        .amortization-table {  
            width: 100%;  
            border-collapse: collapse;  
            margin-bottom: 20px;  
        }  
        .amortization-table th,  
        .amortization-table td {  
            border: 1px solid #000;  
            padding: 8px;  
            text-align: left;  
            vertical-align: middle;  
        }  
        .amortization-table th {  
            background-color: #f2f2f2;  
            text-align: center;  
        }  
        .footer {  
            text-align: center;  
            font-size: 10pt;  
            color: #777;  
            position: fixed;  
            bottom: 0;  
            width: 100%;  
            padding: 10px 0;  
        }  
        .left-align {  
            text-align: left;  
        }  
        .signatures {  
            margin-top: 40px;  
            display: flex;  
            justify-content: space-between;  
        }  
        .signature-block {  
            width: 45%;  
            text-align: center;  
        }  
        .signature-line {  
            margin-top: 50px;  
            border-top: 1px solid #000;  
            width: 80%;  
            margin-left: auto;  
            margin-right: auto;  
        }  
        
        .amortization-table td:nth-child(3),  
        .amortization-table td:nth-child(4),  
        .amortization-table td:nth-child(5),  
        .amortization-table td:nth-child(6) {  
            text-align: right;  
        }  
    </style>  
</head>  
<body>  
<div class="header">  
    <h1>Loan Agreement #{{ $contract_number }}</h1>  
    <p>Date: {{ $contract_date }}</p>  
    <table class="contract-details">  
        <tr>  
            <td>  
                <strong>Lender:</strong><br>  
                {{ $lender_name }}<br>  
                Reg. No.: {{ $lender_reg_number }}<br>  
                Address: {{ $lender_address }}  
            </td>  
            <td>  
                <strong>Borrower:</strong><br>  
                {{ $borrower_name }}<br>  
                Reg. No.: {{ $borrower_reg_number }}<br>  
                Address: {{ $borrower_address }}  
            </td>  
        </tr>  
    </table>  
</div>  
  
<div class="terms">  
    <p>  
        This Loan Agreement (“Agreement”) is made and entered into by and between the above-named Lender and Borrower.  
        The Lender agrees to lend the principal amount of <strong>{{ $principal }}</strong> at an annual interest rate of  
        <strong>{{ $annual_rate }}%</strong>, for a term of <strong>{{ $term_months }}</strong> months, commencing on  
        <strong>{{ $start_date }}</strong>. The Borrower agrees to repay the above principal and the accrued interest in   
        monthly installments as detailed below.  
    </p>  
</div>  
  
<h2>Amortization Schedule</h2>  
  
@php  
    // Prepare totals  
    $sumMonthlyPayment = 0;  
    $sumInterest = 0;  
    $sumPrincipal = 0;  
    $sumBalance = 0;  
@endphp  
  
<table class="amortization-table">  
    <thead>  
        <tr>  
            <th>Payment #</th>  
            <th>Payment Date</th>  
            <th>Monthly Payment</th>  
            <th>Interest Portion</th>  
            <th>Principal Portion</th>  
            <th>Remaining Balance</th>  
        </tr>  
    </thead>  
    <tbody>  
        @foreach ($payments as $payment)  
            @php  
                $sumMonthlyPayment += $payment['monthly_payment'];  
                $sumInterest += $payment['interest_portion'];  
                $sumPrincipal += $payment['principal_portion'];  
                $sumBalance += $payment['remaining_balance'];  
            @endphp  
            <tr>  
                <td>{{ $payment['payment_number'] }}</td>  
                <td>{{ $payment['payment_date'] }}</td>  
                <td>{{ $payment['monthly_payment'] }}</td>  
                <td>{{ $payment['interest_portion'] }}</td>  
                <td>{{ $payment['principal_portion'] }}</td>  
                <td>{{ $payment['remaining_balance'] }}</td>  
            </tr>  
        @endforeach  
    </tbody>  
    <tfoot>  
        <tr>  
            <th colspan="2" style="text-align:right;">Totals:</th>  
            <th>{{ number_format($sumMonthlyPayment, 2) }}</th>  
            <th>{{ number_format($sumInterest, 2) }}</th>  
            <th>{{ number_format($sumPrincipal, 2) }}</th>  
            <th></th>  
        </tr>  
    </tfoot>  
</table>  
  
<div class="signatures">  
    <div class="signature-block">  
        <p><strong>Lender:</strong> {{ $lender_name }}</p>  
        <div class="signature-line"></div>  
        <p>Date: __________________</p>  
    </div>  
    <div class="signature-block">  
        <p><strong>Borrower:</strong> {{ $borrower_name }}</p>  
        <div class="signature-line"></div>  
        <p>Date: __________________</p>  
    </div>  
</div>  
  
<div class="footer">  
    <p>This document was generated automatically and serves as a Loan Agreement with the attached Amortization Schedule.</p>  
</div>  
</body>  
</html>  