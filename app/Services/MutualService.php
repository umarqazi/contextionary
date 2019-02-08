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
use Auth;

class MutualService
{
    protected $familiarContext;
    protected $contextRepo;
    protected $contextArray=array('1','2','3');

    /**
     * MutualService constructor.
     */
    public function __construct()
    {
        $this->familiarContext     = new FamiliarContextRepo();
        $this->contextRepo         = new ContextRepo();
    }

    /**
     * @param $user_id
     * @return mixed
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
     * @param $array
     * @param int $parentId
     * @return array
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
     * @param $totalContext
     * @param $url
     * @return LengthAwarePaginator
     * convert array to pagination
     */
    public function paginatedRecord($totalContext, $url, $pagination=9){
        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = new Collection($totalContext);

        //Define how many items we want to be visible in each page
        $per_page = $pagination;

        //Slice the collection to get the items to display in current page
        $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

        //Create our paginator and add it to the data array
        $data = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        //Set base url for pagination links to follow e.g custom/url?page=6
        $data->setPath(lang_url($url));
        return $data;
    }

    /**
     * @param $expiry_date
     * @return string
     */
    public function displayHumanTimeLeft($expiry_date)
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $expiry_date);
        if (Auth::check()) {
            if(Auth::user()->timezone!=NULL){
                $date->setTimezone(new \DateTimeZone(Auth::user()->timezone));
            }
        }
        return $formatted_date = "Card expires on: <br>".$date->format('d M Y h:i A');
    }

    /**
     * @param $context
     * @return string
     */
    public function explanatoryText($contributor){
        foreach($contributor as $key=>$context){
            $text='';
            switch (strtolower($context['context_name'])){
                case "pseudoscience":
                    $text   = "(alternative medicine, astrology, pseudoscientic theories)";
                    break;
                case "traditional belief":
                    $text   =  "(folklore, mythology)";
                    break;
                case "art":
                    $text   =  "(performing art, visual art, decorative art)";
                    break;
                case "science":
                    $text   =  "(social science, natural science, mathematics, medicine, Engineering)";
                    break;
                case "technology":
                    $text   =  "(industry, invention, equipment, machinery, instrument)";
                    break;

            }
            $contributor[$key]['context_name'] =$contributor[$key]['context_name'].' <span class="small-text">'.$text.'</span>';
        }

        return $contributor;
    }
}