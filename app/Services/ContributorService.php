<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/15/18
 * Time: 4:17 PM
 */

namespace App\Services;
use App\Repositories\FamiliarContextRepo;
use App\Repositories\ContextRepo;
use App\Repositories\RoleRepo;
use App\Repositories\UserRepo;

class ContributorService
{
    protected $contextRepo;
    protected $roleRepo;
    protected $familiarContext;
    protected $userRepo;
    protected $userService;
    public function __construct(UserService $userService, UserRepo $userRepo, FamiliarContextRepo $familiarContext,ContextRepo $context, RoleRepo $role)
    {
        $this->contextRepo=$context;
        $this->roleRepo=$role;
        $this->familiarContext=$familiarContext;
        $this->userRepo=$userRepo;
        $this->userService=$userService;
    }
    public function getParentContextList(){
        return $this->contextRepo->getLimitedRecords();
    }
    public function updateContributorRecord($data){
        $assignRole=$this->roleRepo->assignMultiRole($data['user_id'], $data['role']);
        $familiarContext=[];
        foreach($data['context'] as $key=>$context){
            $familiarContext[$key]=['user_id'=>$data['user_id'], 'context_id'=>$context];
        }
        $this->familiarContext->create($familiarContext);
        $this->userRepo->update($data['user_id'], ['language_proficiency'=>$data['language']]);
        $this->userService->verificationEmail($data['user_id']);
        return true;
    }
}