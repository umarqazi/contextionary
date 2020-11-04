<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCurrentContext extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'user_current_context';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'current_context_id', 'last_played_phrase_id', 'last_played_cell', 'unlocked_context',
        'top_maze_level', 'no_of_hints_used', 'crystal_ball_used', 'normal_hint_count', 'golden_revealer',
        'golden_revealer_count', 'diamond_revealer', 'golden_hints', 'diamond_hints', 'normal_revealer_usage_count'
    ];
}
