<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        #customer_info table {
            border-collapse: collapse;
        }

        #customer_info th,#customer_info td {
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
        <th>{{ $contract->label('contract_no') }}</th>
        <th>{{ $contract->label('lead_name') }}</th>
        <th>{{ $contract->label('phone') }}</th>
        <th>{{ $contract->label('membership') }}</th>
        <th>{{ $contract->label('value') }}</th>
        <th>{{ $contract->label('debt') }}</th>
        <th>{{ $contract->label('start_date') }}</th>
        <th>{{ $contract->label('limit') }}</th>
        <th>{{ $contract->label('state') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($contracts as $contract)
        <tr>
            <td>{{ $contract->contract_no }}</td>
            <td>{{ optional($contract->member)->name }}</td>
            <td>{{ optional($contract->member)->phone }}</td>
            <td>{{ $contract->membership_text }}</td>
            <td>{{ number_format($contract->amount) }}</td>
            <td>{{ number_format($contract->debt) }}</td>
            <td>{{ optional($contract->created_at)->format('d-m-Y') }}</td>
            <td>{{ $contract->limit }}</td>
            <td>{{ \App\Enums\ContractState::getDescription($contract->state) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>