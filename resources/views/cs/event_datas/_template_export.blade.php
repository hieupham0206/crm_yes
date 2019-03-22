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
        <th>{{ $eventData->label('date') }}</th>
        <th>{{ $eventData->label('name') }}</th>
        <th>{{ $eventData->label('phone') }}</th>
        <th>{{ $eventData->label('code') }}</th>
        <th>{{ $eventData->label('TO') }}</th>
        <th>{{ $eventData->label('CS') }}</th>
        <th>{{ $eventData->label('REP') }}</th>
        <th>{{ $eventData->label('Tele') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($eventDatas as $eventData)
        <tr>
            <td>{{ $eventData->created_at->format('d-m-Y H:i:s') }}</td>
            <td>{{ $eventData->lead->name }}</td>
            <td>{{ $eventData->lead->phone }}</td>
            <td>{{ $eventData->code }}</td>

            <td>{{ optional($eventData->to)->name }}</td>
            <td>{{ optional($eventData->cs)->name }}</td>
            <td>{{ optional($eventData->rep)->name }}</td>
            <td>{{ optional($eventData->user)->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>