<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// トップ
// Route::get('/index', 'TopController@index')->name('top.index');
Route::get('/index/{id}', 'TopController@index')->name('top.index')->where('id', '[0-9]+');

// ステータス更新
Route::post('/status', 'TopController@status')->name('top.status');

// プライバシー
Route::get('/privacy', 'TopController@privacy')->name('top.privacy');

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');
// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');
// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
// ログインユーザー
Route::get('/user', function () {
    return Auth::user();
})->name('user');

// メンバー表示
Route::get('/member', 'MemberController@index')->name('member.index');
// メンバー作成
Route::post('/member/create', 'MemberController@create')->name('member.create');
// メンバー編集
Route::put('/member/update', 'MemberController@update')->name('member.update');
// メンバー削除
Route::delete('/member/delete', 'MemberController@delete')->name('member.delete');

// ステータス集積
Route::get('/aggregate', 'AggregateController@index')->name('aggregate.index');
Route::post('/aggregate', 'AggregateController@count')->name('aggregate.count');

// カテゴリ表示
Route::get('/category', 'CategoryController@index')->name('category.index');
// カテゴリ作成
Route::post('/category/create', 'CategoryController@create')->name('category.create');
// カテゴリ編集
Route::put('/category/update', 'CategoryController@update')->name('category.update');
// カテゴリ削除
Route::delete('/category/delete', 'CategoryController@delete')->name('category.delete');

// トークンリフレッシュ
Route::get('/reflesh-token', function (Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();
    return response()->json();
});
