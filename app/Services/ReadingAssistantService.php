<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 11/5/18
 * Time: 3:11 PM
 */

namespace App\Services;


use App\Repositories\TextHistoryRepo;

class ReadingAssistantService
{

    protected $text_history;

    public function __construct()
    {
        $this->text_history = new TextHistoryRepo();
    }

    public function saveHistory($data){
        return $this->text_history->create($data);
    }
}