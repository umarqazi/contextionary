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

class ContextPhraseService extends BaseService implements IService
{
    /**
     * @var ContextPhraseRepo
     */
    protected $context_phrase_repo;

    /**
     * PhraseService constructor.
     */
    public function __construct()
    {
        $context_phrase_repo        = new ContextPhraseRepo();
        $this->context_phrase_repo  = $context_phrase_repo;
    }

    /**
     * @return mixed
     */
    public function getRandContextPhrase(){
        return $this->context_phrase_repo->getRandContextPhrase();
    }

    /**
     * @return mixed
     */
    public function getContextPhrase($context){
        return $this->context_phrase_repo->getContextPhrase($context);
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