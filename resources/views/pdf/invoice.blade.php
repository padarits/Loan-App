<!-- resources/views/pdf/invoice.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rēķins #{{ $document_number }}</title>
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
        .header img { 
            max-width: 150px; 
            margin-bottom: 10px; 
        }
        .document-details { 
            width: 100%; 
            margin-bottom: 5px; 
            table-layout: fixed; 
            border-collapse: collapse; 
        }
        .document-details td { 
            padding: 0px; 
            vertical-align: top; 
            width: 50%; 
            /*border: 1px solid #000; */
        }
        .section-title { 
            font-weight: bold; 
            margin-top: 20px; 
            font-size: 12pt; 
        }
        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        .items-table th, .items-table td { 
            border: 1px solid #000; 
            padding: 8px; 
            text-align: left; 
            vertical-align: middle;
        }
        .items-table th { 
            background-color: #f2f2f2; 
            text-align: center;
        }
        /* Definējiet kolonnas platumus */
        .items-table th.description, .items-table td.description {
            width: 50%;
        }
        .items-table th.quantity, .items-table td.quantity {
            width: 10%;
            text-align: center;
        }
        .items-table th.unit-price, .items-table td.unit-price {
            width: 20%;
            text-align: right;
        }
        .items-table th.amount, .items-table td.amount {
            width: 20%;
            text-align: right;
        }
        .total { 
            text-align: right; 
            font-weight: bold; 
            margin-top: 20px; 
            font-size: 14pt;
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
    </style>
</head>
<body>
    <div class="header">
        <!-- Iekļaujiet uzņēmuma logotipu, ja nepieciešams -->
        <!-- <img src="{{ public_path('images/logo.png') }}" alt="Uzņēmuma logotips"> -->
        <h1>Pavadzīme Nr. {{ $document_number }}</h1>
        <p>Datums: {{ $document_date }}</p>

        <table class="document-details">
            <tr>
                <td width="50%">
                    <strong>Saņēmējs:</strong><br>
                    {{ $receiver_name }}<br>
                    Reģ. nr.: {{ $receiver_reg_number }}<br>
                    Adrese: {{ $receiver_address }}
                </td>
                <td width="50%">
                    <strong>Piegādātājs:</strong><br>
                    {{ $supplier_name }}<br>
                    Reģ. nr.: {{ $supplier_reg_number }}<br>
                    Adrese: {{ $supplier_address }}
                </td>
            </tr>
        </table>
        <p class="left-align"><strong>Saņemšanas vieta:</strong> {{ $receiving_location }}</p>      
    </div>

    {{--<table class="invoice-details">
        <tr>
            <td>
                <strong>Rēķina saņēmējs:</strong><br>
                {{ $customerName }}<br>
                {{ $customerAddress }}<br>
                {{ $customerCity }}, {{ $customerCountry }}
            </td>
            <td style="text-align: right;">
                <strong>Rēķina Nr.: </strong> {{ $invoiceNumber }}<br>
                <strong>Datums: </strong> {{ $date }}<br>
                <strong>Apmaksas termiņš: </strong> {{ $dueDate }}
            </td>
        </tr>
    </table>--}}

    <!-- Preču/Pakalpojumu Tabula -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="nr">Nr.</th>
                <th class="description">Apraksts</th>
                <th class="quantity">Daudzums</th>
                <th class="unit-price">Vienības cena</th>
                <th class="amount">Summa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lines as $index=>$item)
                <tr>
                    <td class="nr">{{$index + 1}}.</td>
                    <td class="description">{{ $item['product_name'] }}</td>
                    <td class="quantity">{{ $item['quantity'] }}</td>
                    <td class="unit-price">{{ $item['price'] }} €</td>
                    <td class="amount">{{ $item['total'] }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Kopējā summa: {{ $totalAmount }} €
    </div>

    <p class="left-align"><strong>Izsniedzējs:</strong> {{ $issuer_name }}</p>
    @if(!empty($additional_info))
        <p class="left-align"><strong>Papildu informācija:</strong> {{ $additional_info }}</p>
    @endif

    <div class="footer">
        
    </div>

</body>
</html>
