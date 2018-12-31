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

   /**
     * @return mixed
     */
   public function countInDefineVotePhase(){
        return $this->phrase_repo->countInDefinePhase();
   }

    /**
     * @return mixed
     */
   public function countInIllustrationVotePhase(){
        return $this->phrase_repo->countInIllustrationPhase();
   }

    /**
     * @return mixed
     */
   public function countInTranslationVotePhase(){
        return $this->phrase_repo->countInTranslationPhase();
   }

    /**
     * @param $phrase_id
     * @return mixed
     */
   public function findById($phrase_id){
        return $this->phrase_repo->getPhraseName($phrase_id);
   }

    /**
     * @param $key
     * @return mixed
     */
    public function findAllLike($key){
        return $this->phrase_repo->findAllLike($key);
    }

    /**
     * @return PhraseRepo
     */
    public function getSharedWords($data){
        return $this->phrase_repo->getSharedWords($data);
    }

}