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
        $calendars = $this->getCalendars($id);

        $data = [
          'days' => $calendars->count(),
          'calendar' => $calendars->get(),
          'data' => $this->getMembers('asc', $calendars->get(['id'])->toArray()),
        ];
        return $data;
    }

    /**
     * ステータス受け取り
     * @param UpdateStatus|$request
     * @return \Illuminate\Http\Response
     */
    public function status(UpdateStatus $request)
    {
        $id = $request->date;
        unset($request->date);
        if ($request->status) {
          return $this->update($request, $id);
        } else {
          return $this->delete($request, $id);
        }
    }

    /**
     * ステータス更新
     * @param UpdateStatus|$request
     * @param String|$id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStatus $request, String $id)
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

        $ids = $this->getCalendars($id)->get(['id'])->toArray();
        // リソースの更新なので
        // レスポンスコードは200(OK)を返却する
        return response($this->getMembers('asc', $ids), 200);
    }

    /**
     * ステータス削除
     * @param DeleteMember|$request
     * @param String|$id
     * @return \Illuminate\Http\Response
     */
    public function delete(UpdateStatus $request, String $id)
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

        $ids = $this->getCalendars($id)->get(['id'])->toArray();
        // リソースの更新なので
        // レスポンスコードは200(OK)を返却する
        return response($this->getMembers('asc', $ids), 200);
    }

    /**
     * メンバー取得
     * @param  String|string
     * @param  String|string
     * @return Member
     */
    private function getMembers(String $sort = 'asc', Array $ids)
    {
        return Auth::user()
                    // ->members()
                    ->categories()
                    ->with(['members' => function($query) use($ids) {
                        $query
                          ->with(['statuses' => function($query) use($ids) {
                            $query->whereIn('calendar_id', $ids);
                          }])
                          // ->with(['statuses'])
                          ->orderBy('sort', 'asc')
                          ->orderBy('name', 'asc');
                    }])
                    ->orderBy('sort', $sort)
                    ->orderBy('name', $sort)
                    ->get();
    }

    /**
     * 当月分カレンダーID取得
     * @param  String|string
     * @return Array
     */
    private function getCalendars(String $id)
    {
        $year = substr($id, 0, 4);
        $month = substr($id, 4, 2);
        $start = $year . '-' . $month . '-01';
        $end = $year . '-' . $month . '-31';

        return Calendar::whereBetween('date', [$start, $end]);
    }
}
