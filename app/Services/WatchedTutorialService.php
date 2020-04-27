<?php


namespace App\Services;
use App\WatchedTutorial;

class WatchedTutorialService extends BaseService implements IService
{
    /**
     * Property: model
     *
     * @var WatchedTutorial
     */
    private $model;

    /**
     * WatchedTutorialService constructor.
     * @param WatchedTutorial $model
     */
    public function __construct(WatchedTutorial $model)
    {
        $this->model = $model;
    }

    /**
     * Method: watchedTutorials
     *
     * @param $data
     * @param $userId
     *
     * @return bool
     */
    public function watchedTutorials($data, $userId)
    {
        foreach ($data['tutorials'] as $tutorial){

            $findWatched = $this->model->where(['user_id' => $userId, 'watched' => $tutorial])->first();
            if(empty($findWatched)){

                $this->model->create([
                    'user_id' => $userId,
                    'watched' => $tutorial
                ]);
            }
        }
        return true;
    }

    /**
     * Method: userWatchedTutorials
     *
     * @param $userId
     *
     * @return mixed
     */
    public function userWatchedTutorials($userId)
    {
        return $this->model->select('watched')->where('user_id', $userId)->get();
    }
}