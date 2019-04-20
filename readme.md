# Web出席簿

<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## 概要

Web上で出席簿を作成／管理できます（そのまんま

## インストール

Laravelの環境構築はググってください。

1. `compsoer install`
1. `php artisan migrate`
1. `php artisan db:seeder`

## 使い方

1. ユーザー登録する  
  Eメール認証は使ってないです
1. カテゴリを先に登録する  
  カテゴリがないとメンバー登録できません
1. メンバーを登録する
1. トップで出席日をクリック！

## 集計

集計ページでは、集計期間を範囲指定することで、ユーザー毎の合計出席数を確認できます。

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
