<?php

namespace App\Http\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereKeyNot(1)->withCount(['appointments'])
                     ->role([6, 9])->orderBy('username')->get();

        return view('dashboard_monitor.monitor_user', ['users' => $users]);
    }

    public function formUserDetail(User $user)
    {
        return view('dashboard_monitor._form_user_detail', ['user' => $user]);
    }

    public function sectionMonitorSale()
    {
        $filter = request()->get('filter');

        $users = User::whereKeyNot(1)->withCount(['appointments'])
                     ->role([6])->orderBy('last_login', 'desc')->get();

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
}
