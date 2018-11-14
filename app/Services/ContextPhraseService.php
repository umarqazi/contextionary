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
        $context_phrase_repo        = new ContextPhraseRepo();
        $related_phrase_repo        = new RelatedPhraseRepo();
        $this->context_phrase_repo  = $context_phrase_repo;
        $this->related_phrase_repo  = $related_phrase_repo;
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
     * @return mixed
     */
    public function getRelatedPhrase($context_id, $phrase_id){
        return $this->related_phrase_repo->getRelated($context_id, $phrase_id);
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

}