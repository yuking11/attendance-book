<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStatus;
use App\Calendar;
use App\Member;
use App\Status;
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
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id = null)
    {

        $now = Carbon::now();
        // $year = $now->subMonthsNoOverflow(3)->year;
        // $start = $year . '-04-01';
        // $end = $year + 1 . '-03-31';
        $year = substr($id, 0, 4);
        $month = substr($id, 4, 2);
        $start = $year . '-' . $month . '-01';
        $end = $year . '-' . $month . '-31';

        $calendars = Calendar::whereBetween('date', [$start, $end])->get();
        $days = Calendar::whereBetween('date', [$start, $end])->count();
        $data = [
          'year' => $year,
          'days' => $days,
          'calendar' => $calendars,
          'data' => $this->getMembers(),
          'statuses' => $this->getStatuses(),
        ];
        return $data;
    }

    /**
     * ステータス受け取り
     * @param UpdateStatus $request
     * @return \Illuminate\Http\Response
     */
    public function status(UpdateStatus $request)
    {
        if ($request->status) {
          return $this->update($request);
        } else {
          return $this->delete($request);
        }
    }

    /**
     * ステータス更新
     * @param UpdateStatus $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStatus $request)
    {
        DB::beginTransaction();

        try {
            $data = Status::updateOrCreate(
                [
                    'calendar_id' => $request->calendar_id,
                    'member_id' => $request->member_id,
                ],
                [
                    'status' => $request->status
                ]
            );
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        // リソースの更新なので
        // レスポンスコードは200(OK)を返却する
        return response($this->getMembers(), 200);
    }

    /**
     * ステータス削除
     * @param DeleteMember $request
     * @return \Illuminate\Http\Response
     */
    public function delete(UpdateStatus $request)
    {
        DB::beginTransaction();

        try {
            $data = Status::where('calendar_id', $request->calendar_id)
                      ->where('member_id', $request->member_id);

            if (! $data) {
                abort(404, '状態が見つかりませんでした。');
            }

            $data->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        // リソースの更新なので
        // レスポンスコードは200(OK)を返却する
        return response($this->getMembers(), 200);
    }

    /**
     * メンバー取得
     * @param  String|string
     * @return Member
     */
    private function getMembers(String $sort = 'asc')
    {
        return Auth::user()
                    // ->members()
                    ->categories()
                    ->with(['members' => function($query) {
                        $query
                          ->with(['statuses'])
                          ->orderBy('sort', 'asc')
                          ->orderBy('name', 'asc');
                    }])
                    ->orderBy('sort', $sort)
                    ->orderBy('name', $sort)
                    ->get();
    }

    /**
     * ステータス取得
     * @param  String|string
     * @return Status
     */
    private function getStatuses()
    {
        return Auth::user()
                    ->statuses()
                    ->get(['calendar_id', 'member_id', 'status']);
    }
}
