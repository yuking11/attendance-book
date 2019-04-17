<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMember;
use App\Http\Requests\UpdateMember;
use App\Http\Requests\DeleteMember;
use App\Member;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()
                ->json([
                  'member' => $this->getMembers(),
                  'category' => $this->getCategories()
                ], 200);
    }

    /**
     * メンバーー登録
     * @param CreateMember $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateMember $request)
    {

        $user = Auth::user();
        $requests = $request->all();

        DB::beginTransaction();

        try {
            foreach ($requests as $item) {
                $data = new Member;
                $item['user_id'] = $user->id;
                $data->fill($item)->save();
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        // リソースの新規作成なので
        // レスポンスコードは201(CREATED)を返却する
        return response()
                ->json([
                  'member' => $this->getMembers(),
                  'category' => $this->getCategories()
                ], 201);
    }

    /**
     * メンバー更新
     * @param UpdateMember $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMember $request)
    {
        $requests = $request->all();

        DB::beginTransaction();

        try {
            foreach ($requests as $item) {
                unset($item['laravel_through_key']);
                $data = Auth::user()->members()->find($item['id']);
                $data->fill($item)->save();
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        // リソースの更新なので
        // レスポンスコードは200(OK)を返却する
        return response()
                ->json([
                  'member' => $this->getMembers(),
                  'category' => $this->getCategories()
                ], 200);
    }

    /**
     * メンバー削除
     * @param DeleteMember $request
     * @return \Illuminate\Http\Response
     */
    public function delete(DeleteMember $request)
    {
        DB::beginTransaction();

        try {
            $data = Auth::user()->members()->find($request->id);
            $status = Status::where('member_id', $request->id);

            if (! $data) {
                abort(404, 'メンバーが見つかりませんでした。');
            }

            $status->delete();
            $data->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        // リソースの削除なので
        // レスポンスコードは204(NO_CONTENT)を返却する
        return response('削除しました。', 204);
    }

    /**
     * メンバー取得
     * @param  String|string
     * @return Member
     */
    private function getMembers(String $sort = 'asc')
    {
        return Auth::user()
                      ->memberWithCategory()
                      ->orderBy('categories.sort', $sort)
                      ->orderBy('categories.name', $sort)
                      ->orderBy('members.sort', $sort)
                      ->orderBy('members.name', $sort)
                      ->get();
    }

    /**
     * カテゴリー取得
     * @param  String|string
     * @return Category
     */
    private function getCategories(String $sort = 'asc')
    {
        return Auth::user()
                      ->categories()
                      ->orderBy('sort', $sort)
                      ->get();
    }
}
