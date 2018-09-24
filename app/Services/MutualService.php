<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/20/18
 * Time: 4:42 PM
 */

namespace App\Services;


use App\FamiliarContext;
use App\Repositories\ContextRepo;
use App\Repositories\FamiliarContextRepo;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class MutualService
{
    protected $familiarContext;
    protected $contextRepo;
    protected $contextArray=array();

    public function __construct()
    {
        $familiarContext=new FamiliarContextRepo();
        $contextRepo=new ContextRepo();
        $this->contextRepo=$contextRepo;
        $this->familiarContext=$familiarContext;
    }
    /**
     * get all context of user
     */
    public function getUserContext($user_id){
        return $getFamiliarContext=$this->familiarContext->getContext($user_id);
    }
    /**
     * @return mixed
     */
    public function getFamiliarContext($user_id){
        $getFamiliarContext=$this->getUserContext($user_id);
        $getAllContext=$this->contextRepo->getContext();
        $contexts=[];
        foreach ($getFamiliarContext as $key=> $context):
            array_push($this->contextArray, $context['context_id']);
            $this->contextArray=$this->contextChildList($getAllContext, $context['context_id']);
        endforeach;
        return $this->contextArray;
    }
    /**
     * get context and child of all context
     */
    public function contextChildList($array, $parentId = 0){
        $branch = array();
        foreach ($array as $key=>$element) {
            if ($element['context_immediate_parent_id'] == $parentId) {
                array_push($this->contextArray, $element['context_id']);
                $children = $this->contextChildList($array, $element['context_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$key] = $element;
            }
        }
        return $this->contextArray=array_unique($this->contextArray);
    }
    /**
     * convert array to pagination
     */
    public function paginatedRecord($totalContext, $url){
        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = new Collection($totalContext);

        //Define how many items we want to be visible in each page
        $per_page = 9;

        //Slice the collection to get the items to display in current page
        $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

        //Create our paginator and add it to the data array
        $data = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        //Set base url for pagination links to follow e.g custom/url?page=6
        $data->setPath(lang_url($url));
        return $data;
    }
    /**
     * @return string
     */
    public function displayHumanTimeLeft($expiry_date)
    {
        $counter='';
        $now = Carbon::now();
        $days = $now->diffInDays($expiry_date);
        $hours = $now->diffInHours($expiry_date);
        $minutes = $now->diffInMinutes($expiry_date);
        if ($now->diffInDays($expiry_date) > 0)
        {
            $counter= $now->diffInDays($expiry_date) . str_plural(' day', $now->diffInDays($expiry_date)).     ' left';
        }elseif($hours > 0){
            $counter= $hours . str_plural(' Hour', $hours).' Left';
        }elseif($minutes > 0){
            $counter= $minutes . str_plural(' Minute', $minutes).' Left';
        }
        return $counter;
    }
}