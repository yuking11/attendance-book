<?php

namespace App\Http\Controllers;

use App\User;
use App\Calendar;
use Illuminate\Http\Request;
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

        $calendars = Calendar::whereBetween('date', [$start, $end])->get();
        $days = Calendar::whereBetween('date', [$start, $end])->count();
        $data = [
          'calendar' => $calendars,
          'year' => $year,
          'days' => $days,
        ];
        return $data;
    }
}
