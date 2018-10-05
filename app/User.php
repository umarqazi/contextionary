<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Profile;
use App\Feedback;
use App\Transaction;

class User extends Authenticatable
{
    use Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'first_name', 'last_name','status', 'token', 'email_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function profile(){
       return $this->hasOne(Profile::class);
    }

    public function userTransaction(){
        return $this->hasOne(Transaction::class);
    }
    /**
     * relation with define meaning
     */
    public function defineMeaning(){
        return $this->hasMany('App/DefineMeaning');
    }
    /**
     * relation with define meaning
     */
    public function illustrator(){
        return $this->hasMany('App/Illustrator');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function glossary() {
        return $this->belongsToMany('App\Glossary', 'my_collection', 'user_id', 'glossary_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function feedback() {
        return $this->hasOne('App\Feedback');
    }

    /**
     * relation with translation
     */
    public function translation(){
        return $this->hasMany('App/Translation');
    }
    /**
     * relation with transaction
     */
    public function transaction(){
        return $this->hasMany(Transaction::class);
    }
}
