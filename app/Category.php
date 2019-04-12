<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    protected $guarded = array('id');

    /**
     * リレーションシップ - membersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany('App\Member');
    }

    /**
     * Scope Auth User
     */
    public function scopeUser($query)
    {
        return $query->where('user_id', '=', Auth::user()->id);
    }
}
