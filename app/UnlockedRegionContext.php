<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnlockedRegionContext extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'user_unlocked_region_contexts';
    protected $fillable = ['user_id', 'unlocked_context', 'region_id'];
}
