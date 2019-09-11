<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SprintStatistic extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'sprint_statistics';
    protected $fillable = ['user_id', 'game_id', 'topic_id', 'no_of_correct_answers', 'points', 'best_time', 'completed', 'has_cup'];
}
