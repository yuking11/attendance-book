<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategory;
use App\Http\Requests\UpdateCategory;
use App\Http\Requests\DeleteCategory;
use App\Category;
use App\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
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
        return response($this->getCategories(), 200);
    }

    /**
     * カテゴリー登録
     * @param CreateCategory $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCategory $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        try {
            foreach ($request->data as $item) {
                $data = new Category;
                $item['user_id'] = $user->id;
                $data->fill($item)->save();
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        $response = $this->getCategories();
        // リソースの新規作成なので
        // レスポンスコードは201(CREATED)を返却する
        return response($response, 201);
    }

    /**
     * カテゴリ更新
     * @param UpdateCategory $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategory $request)
    {
        DB::beginTransaction();

        try {
            foreach ($request->data as $item) {
                $data = Auth::user()->categories()->find($item['id']);
                $data->fill($item)->save();
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        $response = $this->getCategories();
        // リソースの更新なので
        // レスポンスコードは200(OK)を返却する
        return response($response, 200);
    }

    /**
     * カテゴリー削除
     * @param DeleteCategory $request
     * @return \Illuminate\Http\Response
     */
    public function delete(DeleteCategory $request)
    {
        $count = Category::find($request->id)->members()->count();
        if ($count) {
          abort(409, 'メンバーが'.$count.'名存在します。該当メンバーを別のカテゴリに移動してください。');
        }

        DB::beginTransaction();

        try {
            $data = Auth::user()->categories()->find($request->id);

            if (! $data) {
                abort(404, 'カテゴリが見つかりませんでした。');
            }

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
