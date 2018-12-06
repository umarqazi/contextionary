<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 10/1/18
 * Time: 2:36 PM
 */

namespace App\Repositories;


use App\Phrase;
use App\BiddingExpiry;
use App\SharedWord;

class PhraseRepo extends BaseRepo implements IRepo
{

    /**
     * @var BiddingExpiry
     */
    private $bidding_expiry;

    /**
     * @var Phrase
     */
    protected $phrase;

    /**
     * @var SharedWord
     */
    protected $shared_word;

    /**
     * PhraseRepo constructor.
     */
    public function __construct()
    {
        $bidding_expiry = new BiddingExpiry();
        $this->bidding_expiry = $bidding_expiry;
        $phrase         = new Phrase();
        $this->phrase   = $phrase;
        $this->shared_word    = new SharedWord();
    }

    /**
     * @return mixed
     */
    public function countInDefinePhase(){
        return $this->bidding_expiry->countPharseInDefinePhase();
    }

    /**
     * @return mixed
     */
    public function countInIllustrationPhase(){
        return $this->bidding_expiry->countPharseInIllustrationPhase();
    }

    /**
     * @return mixed
     */
    public function countInTranslationPhase(){
        return $this->bidding_expiry->countPharseInTranslationPhase();
    }

    /**
     * @param $phrase_id
     * @return mixed
     */
    public function getPhraseName($phrase_id){
        return $this->phrase->where('phrase_id', $phrase_id)->first();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function findAllLike($key){
        return $this->phrase->where( function ( $query ) use ( $key ) {
            $query->whereRaw( 'LOWER("phrase_text") like ?',  '%'.strtolower($key).'%' );
        })->get();
    }

    public function getLexicalSets($phrase_id){
       return $this->shared_word->where('long_phrase_id', $phrase_id)->get();
    }
}