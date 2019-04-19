<?php

namespace App\Http\Controllers;

use App\Http\Requests\AggregateStatus;
use App\Calendar;
use App\Member;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AggregateController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d');

        $calendarIds = Calendar::whereBetween('date', [$start, $end])->get(['id'])->toArray();

        return response($this->getMembers('asc', $calendarIds), 200);
    }

    /**
     * ステータス集計
     *
     * @param AggregateStatus|$request
     * @return \Illuminate\Http\Response
     */
    public function count(AggregateStatus $request)
    {
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));

        $calendarIds = Calendar::whereBetween('date', [$start, $end])->get(['id'])->toArray();

        return response($this->getMembers('asc', $calendarIds), 200);
    }

    /**
     * メンバー取得
     * @param  String|string
     * @param  String|string
     * @return Member
     */
    private function getMembers(String $sort = 'asc', Array $ids = [])
    {
        return Auth::user()
                    ->categories()
                    ->with(['members' => function($query) use($ids) {
                        $query
                          ->withCount(['statuses' => function($query) use($ids) {
                            $query
                              ->whereIn('calendar_id', $ids);
                          }])
                          ->orderBy('sort', 'asc')
                          ->orderBy('name', 'asc');
                    }])
                    ->orderBy('sort', $sort)
                    ->orderBy('name', $sort)
                    ->get();
    }
}
