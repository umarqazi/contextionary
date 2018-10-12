<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Repositories;

use App\BiddingExpiry;

class PhraseRepo extends BaseRepo implements IRepo
{

    /**
     * @var BiddingExpiry
     */
    private $bidding_expiry;

    /**
     * PhraseRepo constructor.
     */
    public function __construct()
    {
        $bidding_expiry = new BiddingExpiry();
        $this->bidding_expiry = $bidding_expiry;
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

}