<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class CustomNotification extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sent_to', 'subject', 'content', 'sent', 'sent_at',
    ];

    /**
     * @var string
     */
    protected $table = 'custom_notifications';
}