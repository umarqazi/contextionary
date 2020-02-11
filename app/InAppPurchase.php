<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InAppPurchase extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'in_app_purchases';
    protected $fillable = ['items_on_sale', 'type', 'pack', 'coins_per_unit', 'coins_per_pack', 'price'];
}
