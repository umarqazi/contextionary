<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 11/5/18
 * Time: 3:12 PM
 */

namespace App\Repositories;


use App\TextHistory;

class TextHistoryRepo extends BaseRepo implements IRepo
{

    protected $text_history;

    public function __construct()
    {
        $this->text_history =   new TextHistory();
    }

    public function create($data){
        return $this->text_history->create($data);
    }

    public function findById($id){
        return $this->text_history->where($id)->first();
    }

    public function listingForUser($user_id){
        return $this->text_history->where(['user_id' => $user_id])->orderBy('created_at', 'desc')->paginate(50);
    }
}