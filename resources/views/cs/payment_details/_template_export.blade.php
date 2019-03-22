<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        #customer_info table {
            border-collapse: collapse;
        }

        #customer_info th, #customer_info td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<table id="customer_info">
    <thead>
    <tr>
        <th>Mã hợp đồng</th>
        <th>{{ $paymentDetail->label('pay_date') }}</th>
        <th>{{ $paymentDetail->label('amount') }}</th>
        <th>{{ $paymentDetail->label('pay_date_real') }}</th>
        <th>{{ $paymentDetail->label('amount') }}</th>
        <th>{{ 'Phí' }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($paymentDetails as $paymentDetail)
        <tr>
            <td>{{ optional($paymentDetail->contract)->contract_no }}</td>
            <td>{{ optional($paymentDetail->pay_date)->format('d-m-Y') }}</td>
            <td>{{ number_format($paymentDetail->total_paid_deal) }}</td>
            <td>{{ optional($paymentDetail->pay_date_real)->format('d-m-Y') }}</td>
            <td>{{ number_format($paymentDetail->total_paid_real) }}</td>
            <td>{{ optional($paymentDetail->payment_cost)->cost }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>