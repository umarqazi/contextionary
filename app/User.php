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
use App\DefineMeaning;

class User extends Authenticatable
{
    use Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'first_name', 'last_name','status', 'token', 'email_token','timezone','cus_id',
        'coins', 'provider', 'provider_id', 'signup_from', 'api_token', 'game_coins','aladdin_lamp','butterfly_effect',
        'stopwatch', 'time_traveller', 'learning_center', 'game_session', 'honour_badge', 'coins_earned',
        'coins_purchased', 'coins_used', 'crystal_ball', 'sound', 'cheering_voice', 'lamp_genie', 'my_gender',
        'no_of_best_times', 'last_shown_medal', 'tutorial', 'coffee_break_count', 'finish_all_hot_context', 'need_to_show_again',
        'previous_target_word', 'top_maze_level'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFullNameAttribute(){
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

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
        return $this->hasMany(DefineMeaning::class)->orderBy('created_at', 'desc');
    }
    /**
     * relation with define meaning
     */
    public function illustrator(){
        return $this->hasMany(Illustrator::class)->orderBy('created_at', 'desc');
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
        return $this->hasMany(Translation::class)->orderBy('created_at', 'desc');
    }
    /**
     * relation with transaction
     */
    public function transaction(){
        return $this->hasMany(Transaction::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function redeemPoints(){
        return $this->hasMany(RedeemPoint::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userPoints(){
        return $this->hasMany(UserPoint::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCards(){
        return $this->hasMany(UserCard::class);
    }

    public function voteMeaning(){
        return $this->hasMany(VoteMeaning::class);
    }
}
