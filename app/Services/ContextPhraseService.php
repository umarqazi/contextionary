<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 03/09/18
 * Time: 16:08
 */

namespace App\Services;

use App\Repositories\ContextPhraseRepo;
use App\Repositories\RelatedPhraseRepo;

class ContextPhraseService extends BaseService implements IService
{
    /**
     * @var ContextPhraseRepo
     */
    protected $context_phrase_repo;

    protected $related_phrase_repo;

    /**
     * PhraseService constructor.
     */
    public function __construct()
    {
        $this->context_phrase_repo  = new ContextPhraseRepo();
        $this->related_phrase_repo  = new RelatedPhraseRepo();
    }

    /**
     * @return mixed
     */
    public function getRandContextPhrase($context_id){
        return $this->context_phrase_repo->getRandContextPhrase($context_id);
    }

    /**
     * @return mixed
     */
    public function getContextPhrase($context){
        return $this->context_phrase_repo->getContextPhrase($context);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function searchContextPhrase($key){
        return $this->context_phrase_repo->searchContextPhrase($key);
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     */
    public function getRelatedPhrase($context_id, $phrase_id){
        return $this->related_phrase_repo->getRelated($context_id, $phrase_id);
    }

    /**
     * @return mixed
     */
    public function getRelatedPhraseByPhraseId($phrase_id){
        return $this->related_phrase_repo->getRelatedById($phrase_id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPhrase($id){
        return $this->context_phrase_repo->getPhrase($id);
    }

    /**
     * @param $length
     * @return mixed
     */
    public function getLengthedPhrase($length){
        return $this->context_phrase_repo->getLengthed($length);
    }

    /**
     * @param null $search
     * @return mixed
     */
    public function listing($search = null){
        return $this->context_phrase_repo->listing($search);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPhraseContext($id){
        return $this->context_phrase_repo->getPhraseContext($id);
    }
}