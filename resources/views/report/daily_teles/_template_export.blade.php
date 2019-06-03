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
@php
    $totalQ = $totalNq = $totalNorep = $totalOverfl = $total3Pm = $totalReapp = $totalApp = $totalShow = $totalFinalDeal = 0
@endphp
<table id="customer_info">
    <thead>
    <tr>
        <th>{{ $user->label('name') }}</th>
        <th>{{ $user->label('role') }}</th>
        <th>Tổng số cuộc gọi</th>
        <th>Tổng thời gian gọi</th>
        <th>{{ $user->label('queue') }}</th>
        <th>{{ $user->label('not_queue') }}</th>
        <th>{{ $user->label('no_rep') }}</th>
        <th>{{ $user->label('overflow') }}</th>
        <th>{{ $user->label('3pm_event') }}</th>
        <th>{{ $user->label('re_app') }}</th>
        <th>{{ $user->label('total_app') }}</th>
        <th>{{ $user->label('rate_app') }}</th>
        <th>{{ $user->label('total_show') }}</th>
        <th>{{ $user->label('deal') }}</th>
        <th>{{ $user->label('rate_deal') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        @php
                $appointments       = $user->appointments;
                $totalAppointments  = $user->appointments_count;
                $totalQueue         = $appointments->filter(function (App\Models\Appointment $app) {
                    return $app->is_queue == 1;
                })->count();
                $totalShow         = $appointments->filter(function (App\Models\Appointment $app) {
                    return $app->is_show_up == 1;
                })->count();
                $totalNotQueue      = $appointments->filter(function (App\Models\Appointment $app) {
                    return $app->is_queue == 0;
                })->count();
                $totalNoRep         = $appointments->sum(function (App\Models\Appointment $app) {
                    return $app->noRepEvents->count();
                });
                $totalOverflow      = $appointments->sum(function (App\Models\Appointment $app) {
                    return $app->overflowEvents->count();
                });
                $totalCancel        = $appointments->filter(function (App\Models\Appointment $app) {
                    return $app->state == -1;
                })->count();
                $totalReAppointment = $appointments->sum(function (App\Models\Appointment $app) {
                    return $app->busyEvents->count();
                });
                $total3pmEvent      = $appointments->filter(function (App\Models\Appointment $app) {
                    return $app->appointment_datetime->isSameHour(\Illuminate\Support\Carbon::createFromTime(13, 0, 0));
                })->count();
                $totalDeal          = $appointments->sum(function (App\Models\Appointment $app) {
                    return $app->dealEvents->count();
                });
    //            $rate               = $totalQueue > 0 ? $totalDeal / $totalQueue * 0.1 : 0;
                $rateDeal        = $totalAppointments > 0 ? $totalQueue / $totalAppointments * 0.1 : 0;
                $rateApp        = $totalAppointments > 0 ? $totalShow / $totalAppointments * 0.1 : 0;

                $totalQ += $totalQueue;
                $totalNq += $totalNotQueue;
                $totalNorep += $totalNoRep;
                $totalOverfl += $totalOverflow;
                $total3Pm += $total3pmEvent;
                $totalReapp += $totalReAppointment;
                $totalApp += $totalAppointments;
                $totalShow += $totalQueue + $totalNotQueue + $totalNoRep + $totalOverflow + $totalCancel + $totalReAppointment + $total3pmEvent;
                $totalFinalDeal += $totalDeal;

                $historyCall = $historyCalls->filter(function ($arr) use ($user) {
                    return $arr->user_id === $user->id;
                })->first();
                $totalSecond  = $historyCall ? $historyCall->total_call : 0;
                $formatedTime = 0;
                if ($totalSecond > 0) {
                    $hours   = floor($totalSecond / 3600);
                    $minutes = floor(($totalSecond / 60) % 60);
                    $seconds = $totalSecond % 60;

                    $formatedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                }
        @endphp

        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ optional($user->roles[0])->name }}</td>
            <td>{{ $totalQueue }}</td>
            <th>{{ $user->history_calls_count }}</th>
            <th>{{ $formatedTime }}</th>
            <td>{{ $totalNotQueue }}</td>
            <td>{{ $totalNoRep }}</td>
            <td>{{ $totalOverflow }}</td>
            <td>{{ $total3pmEvent }}</td>
            <td>{{ $totalReAppointment }}</td>
            <td>{{ $totalAppointments }}</td>
            <td>{{ $rateApp }}</td>
            <td>{{ $totalQueue + $totalNotQueue + $totalNoRep + $totalOverflow + $totalCancel + $totalReAppointment + $total3pmEvent }}</td>
            <td>{{ $totalDeal }}</td>
            <td>{{ $rateDeal }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td></td>
        <td>Total</td>
        <td>{{ $totalQ }}</td>
        <td>{{ $totalNq }}</td>
        <td>{{ $totalNorep }}</td>
        <td>{{ $totalOverfl }}</td>
        <td>{{ $total3Pm }}</td>
        <td>{{ $totalReapp }}</td>
        <td>{{ $totalApp }}</td>
        <td></td>
        <td>{{ $totalShow }}</td>
        <td>{{ $totalFinalDeal }}</td>
    </tr>
    </tfoot>
</table>
</body>
</html>