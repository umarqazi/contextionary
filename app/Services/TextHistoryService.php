<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 */


/**
 * Created by PhpStorm.
 * User: haris
 * Date: 14/09/18
 * Time: 13:19
 */

namespace App\Services;


use App\Repositories\FeedbackRepo;
use App\Repositories\TextHistoryRepo;

class TextHistoryService extends BaseService implements IService
{
    /**
     * @var TextHistoryRepo
     */
    protected $text_history_repo;

    /**
     * FeedbackService constructor.
     */
    public function __construct()
    {
        $this->text_history_repo = new TextHistoryRepo();
    }

    public function listingForUser($user_id){
        return $this->text_history_repo->listingForUser($user_id);
    }
}