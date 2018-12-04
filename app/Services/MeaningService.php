<?php
/**
 * Created by PhpStorm.
 * User: haris
 */

namespace App\Services;


use App\Repositories\DefineMeaningRepo;

class MeaningService extends BaseService implements IService
{
    /**
     * @var PhraseRepo
     */
    protected $meaning_repo;

    /**
     * PhraseService constructor.
     */
    public function __construct()
    {
        $meaning_repo       = new DefineMeaningRepo();
        $this->meaning_repo = $meaning_repo;
    }

    /**
     * @param $context_id
     * @param $phrase_id
     * @return mixed
     */
    public function meaning($context_id, $phrase_id){
        $data = [
            'context_id' => $context_id,
            'phrase_id' => $phrase_id,
        ];
        return $this->meaning_repo->getAllContributedMeaning($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function meaning_data($data){
        return $this->meaning_repo->getAllContributedMeaning($data);
    }
}