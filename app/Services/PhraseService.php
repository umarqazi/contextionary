<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Services;


use App\Repositories\PhraseRepo;

class PhraseService extends BaseService implements IService
{
    /**
     * @var PhraseRepo
     */
    protected $phrase_repo;

    /**
     * PhraseService constructor.
     */
    public function __construct()
    {
        $phrase_repo = new PhraseRepo();
        $this->phrase_repo = $phrase_repo;
    }

    /**
     * @return mixed
     */
   public function countInDefinePhase(){
        return $this->phrase_repo->countInDefinePhase();
   }

    /**
     * @return mixed
     */
   public function countInIllustrationPhase(){
        return $this->phrase_repo->countInIllustrationPhase();
   }

    /**
     * @return mixed
     */
   public function countInTranslationPhase(){
        return $this->phrase_repo->countInTranslationPhase();
   }

}