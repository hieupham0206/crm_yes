<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\PaymentDetail;
use App\Models\TimeBreak;
use App\Models\User;
use App\TechAPI\FptSms;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {
        $this->middleware(['rolepermission:view-tele-marketer-console'], ['only' => ['teleMarketerConsole']]);
        $this->middleware(['rolepermission:view-reception'], ['only' => ['reception']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->hasRole('TELE MARKETER')) {
            return redirect(route('tele_console'));
        }

        if ($user->hasRole('RECEPTION')) {
            return redirect(route('reception'));
        }

        return view('home');
    }

    public function teleMarketerConsole()
    {
        /** @var User $user */
        $user = auth()->user();
        $lead = new Lead();

        $diffLoginString   = $user->login_time_string;
        $isCheckedIn       = $user->isCheckedIn();
        $isLoadPrivateOnly = $user->isLoadPrivateOnly();

        $lastBreakTime   = TimeBreak::whereNotNull('start_break')->whereNull('end_break')->whereDate('start_break', Carbon::today())->latest()->first();
        $diffBreakString = $maxBreakTime = $startBreakValue = '';
        $startCallTime   = '00:00:00';
        if ($lastBreakTime) {
            $maxBreakTime    = $lastBreakTime->reason_break->time_alert * 60;
            $totalTimeBreak  = TimeBreak::whereDate('start_break', Carbon::today())->get()->sum(function (TimeBreak $timeBreak) {
                if ($timeBreak->end_break) {
                    return $timeBreak->end_break->diffInSeconds($timeBreak->start_break);
                }

                return now()->diffInSeconds($timeBreak->start_break);
            });
            $diffBreakString = gmdate('H:i:s', $totalTimeBreak);

            if ($diffBreakString) {
                $startBreakValue = $totalTimeBreak;
            } else {
                $diffTime        = now()->diffAsCarbonInterval($lastBreakTime->start_break);
                $diffBreakString = "{$diffTime->h}:{$diffTime->i}:{$diffTime->s}";
            }
        } elseif ($isCheckedIn) {
            if ($isLoadPrivateOnly) {
                $lead = Lead::where('user_id', $user->id)->orderBy('created_at')->orderBy('state')->stateAvailableToCall()->where('is_private', 1)->first();
            } else {
                $lead = Lead::where('user_id', $user->id)->orderBy('created_at')->orderBy('state')->stateAvailableToCall()->first();
            }
            $lead = $lead ?? Lead::getAvailable()->first();
            if ($lead) {
                $lead->update([
                    'call_date' => now()->toDateTimeString(),
                    'user_id'   => auth()->id(),
                ]);
                $user->putCallCache($lead);
            }

            //note: check thoi gian gọi hiện tại
            $startCallTime = session('startCallTime');
            if ( ! $startCallTime) {
                $startCallTime = '00:00:00';
                session(['startCallTime' => now()]);
            } else {
                $startCallTime = gmdate('H:i:s', now()->diffInSeconds($startCallTime));
            }
        }

        return view('tele_marketer_console', compact('lead', 'diffLoginString', 'diffBreakString', 'maxBreakTime', 'startBreakValue', 'startCallTime', 'isCheckedIn', 'isLoadPrivateOnly'));
    }

    public function reception()
    {
        $lead = new Lead();

        return view('reception_console', ['lead' => $lead]);
    }

    public function lang()
    {
        $lang    = config('app.locale');
        $strings = \File::get(resource_path("lang/{$lang}.json"));
        header('Content-Type: text/javascript');
        echo('window.lang = ' . $strings . ';');
        exit();
    }

    public function quickSearch()
    {
        $query   = request()->get('query');
        $results = [];
        if ($query) {
            $results = \App\Models\QuickSearch::search($query)->get()->map(function ($elem) {
                return [
                    'text' => $elem['search_text'],
                    'url'  => $elem['route'],
                ];
            })->toArray();
        }

        return view('layouts.partials.quicksearch_result')->with('results', $results);
    }

    public function monitorSale()
    {
        $users = User::whereKeyNot(1)->withCount([
            'appointments' => function ($q) {
                $q->whereDate('appointment_datetime', Carbon::today());
            },
        ])->with(['privates', 'private_stills'])->role([6, 9])->get();

        return view('dashboard.monitor_user', ['users' => $users]);
    }

    public function formUserDetail(User $user)
    {
        return view('dashboard_monitor._form_user_detail', ['user' => $user]);
    }

    public function sectionMonitorSale()
    {
        $filter = request()->get('filter');

        $users = User::whereKeyNot(1)->withCount([
            'appointments' => function ($q) {
                $q->whereDate('appointment_datetime', Carbon::today());
            },
        ])->with(['privates', 'private_stills'])->role([6, 9])->get();

        if ($filter) {
            switch ($filter) {
                case 'online':
                    $users = $users->filter(function (User $user) {
                        $user->getBgClassOnDashboard();

                        return $user->isOnline();
                    });
                    break;
                case 'offline':
                    $users = $users->filter(function (User $user) {
                        $user->getBgClassOnDashboard();

                        return ! $user->isOnline();
                    });
                    break;
                case 'busy':
                    $users = $users->filter(function (User $user) {
                        $user->getBgClassOnDashboard();

                        return $user->isPause();
                    });
                    break;
                case 'overtime':
                    $users = $users->filter(function (User $user) {
                        $user->getBgClassOnDashboard();

                        return $user->isPause();
                    });
                    break;
            }
        }

        return view('dashboard_monitor._section_monitor', ['users' => $users]);
    }

    public function checkPaymentNotification()
    {
//        $paymentDetails = PaymentDetail::where('pay_date', '<=', now()->addDays(3))
//                                       ->with(['contract', 'contract.member'])
//                                       ->get();
//
//        //Gửi mail
//        foreach ($paymentDetails as $paymentDetail) {
//            $member = $paymentDetail->contract->member;
//
//            //Gửi SMS
//            $fptSms = new FptSms();
//            $fptSms->sendRemindPayment('', $member->phone);
//        }
    }
}
