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
        return $this->belongsTo('App\Category');
    }

    /**
     * リレーションシップ - statusテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses()
    {
        return $this->hasMany('App\Status');
    }
}
