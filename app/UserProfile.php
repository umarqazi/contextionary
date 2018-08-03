<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
  protected $fillable = [
      'pseudonyme', 'date_birth', 'gender', 'phone_number', 'country','native_language','user_id'];
}
