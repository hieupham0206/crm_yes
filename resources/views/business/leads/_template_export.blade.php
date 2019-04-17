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
        <th>{{ $lead->label('name') }}</th>
        <th>{{ $lead->label('title') }}</th>
        <th>{{ $lead->label('email') }}</th>
        <th>{{ $lead->label('birthday') }}</th>
        <th>{{ $lead->label('province') }}</th>
        <th>{{ $lead->label('phone') }}</th>
        <th>{{ $lead->label('state') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($leads as $lead)
        <tr>
            <td>{{ $lead->name }}</td>
            <td>{{ $lead->title }}</td>
            <td>{{ $lead->email }}</td>
            <td>{{ optional($lead->birthday)->format('d-m-Y') }}</td>
            <td>{{ optional($lead->province)->name }}</td>
            <td>{{ $lead->phone }}</td>
            <td>{{ $lead->state_text }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>