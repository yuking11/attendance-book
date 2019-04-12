<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Member extends Model
{
    protected $guarded = array('id');

    /**
     * リレーションシップ - membersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    /**
     * リレーションシップ - statusテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function status()
    {
        return $this->hasMany('App\State');
    }

    /**
     * Scope Auth User
     */
    public function scopeUser($query)
    {
        return $query->where('user_id', '=', Auth::user()->id);
    }

    /**
     * Scope Current Category
     */
    public function scopeCurrentCategory($query, $id)
    {
        return $query->where('category_id', '=', $id);
    }
}
