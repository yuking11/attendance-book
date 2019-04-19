<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUser;
use App\User;
use App\Category;
use App\Member;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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
    }

    /**
     * ユーザー更新
     * @param UpdateMember $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request)
    {
        $user = Auth::user();
        $users = User::where('id', '<>', $user->id)
                    ->where('name', $request->name)
                    ->count();

        if ($users) {
            abort(409, 'ユーザー名は既に存在しています。');
        }
        if ($user->id !== $request->id) {
            abort(409, '不正なリクエストです。');
        }

        DB::beginTransaction();
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        $response = $request;
        // リソースの更新なので
        // レスポンスコードは200(OK)を返却する
        return response($response, 200);
    }

    /**
     * ユーザー削除
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $user = Auth::user();
        if ($user->id !== $request->id) {
            return response($request, 200);
            abort(409, '不正なリクエストです。');
        }
        if (! $user) {
            abort(404, 'ユーザーが見つかりませんでした。');;
        }

        DB::beginTransaction();
        try {
            $members = $user->members();
            $memberIds = $members->get(['id'])->toArray();
            $categories = $user->categories();
            $status = Status::whereIn('member_id', $memberIds);

            $status->delete();
            $members->delete();
            $categories->delete();
            $user->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        // リソースの削除なので
        // レスポンスコードは204(NO_CONTENT)を返却する
        return response('削除しました。', 204);
    }
}
