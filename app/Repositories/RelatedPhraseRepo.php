<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/29/18
 * Time: 12:19 PM
 */

namespace App\Repositories;


use App\ContextPhrase;
use App\Phrase;
use App\RelatedPhrase;
use App\Repositories\DefineMeaningRepo;
use App\Http\Controllers\SettingController;
use App\Services\MutualService;
use Auth;
use Carbon\Carbon;
use Config;

class RelatedPhraseRepo extends BaseRepo  implements IRepo
{
    protected $related_phrase;

    /**
     * ContextPhraseRepo constructor.
     */
    public function __construct()
    {
        $this->related_phrase   =   new RelatedPhrase();
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     */
    public function getRelated($context_id, $phrase_id){
        return $this->related_phrase->where(['context_id' => $context_id, 'context_phrase_id' =>  $phrase_id])->with('relatedPhrases')->get();
    }
}
