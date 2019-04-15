<?php

namespace App\Http\Controllers;

use App\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $now = Carbon::now();
        $year = $now->subMonthsNoOverflow(3)->year;
        $start = $year . '-04-01';
        $end = $year + 1 . '-03-31';
        $member = $this->getMembers();

        $calendars = Calendar::whereBetween('date', [$start, $end])->get();
        $days = Calendar::whereBetween('date', [$start, $end])->count();
        $data = [
          'year' => $year,
          'days' => $days,
          'calendar' => $calendars,
          'data' => $member,
        ];
        return $data;
    }

    /**
     * メンバー取得
     * @param  String|string
     * @return Member
     */
    private function getMembers(String $sort = 'asc')
    {
        return Auth::user()
                    ->categories()
                    ->with(['members' => function($query) {
                        $query->orderBy('sort', 'asc');
                    }])
                    ->orderBy('sort', 'asc')
                    ->get();
    }
}
