<?php


namespace App\Services;
use App\TutorialList;
use Illuminate\Support\Facades\App;

class TutorialListService extends BaseService implements IService
{
    /**
     * Property: model
     *
     * @var TutorialList
     */
    private $model;

    /**
     * TutorialListService constructor.
     * @param TutorialList $model
     */
    public function __construct(TutorialList $model)
    {
        $this->model = $model;
    }

    /**
     * Method: tutorialsList
     *
     * @param $userId
     *
     * @return TutorialList[]|\Illuminate\Database\Eloquent\Collection
     */
    public function tutorialsList($userId)
    {
        $watchedTutorials = App::make(WatchedTutorialService::class)->userWatchedTutorials($userId);
        if($watchedTutorials->isEmpty()){

            $tutorialsList = $this->model->select('position')->get();
        } else {

            $tutorialsList = $this->model->select('position')->whereNotIn('position', $watchedTutorials)->get();
        }
        return $tutorialsList;
    }
}