<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 9/14/18
 * Time: 9:11 PM
 */

namespace App\Services;


use App\Http\Controllers\SettingController;
use App\Mail\BonusCoinsMail;
use App\Mail\ExtraPointToContributors;
use App\Repositories\BiddingExpiryRepo;
use App\Repositories\ContextPhraseRepo;
use App\Repositories\ContextRepo;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\IllustratorRepo;
use App\Repositories\PhraseRepo;
use App\Repositories\TransactionRepo;
use App\Repositories\TranslationRepo;
use App\Repositories\UserPointRepo;
use App\Repositories\UserRepo;
use App\Repositories\VoteExpiryRepo;
use App\Repositories\VoteMeaningRepo;
use Carbon\Carbon;
use Cartalyst\Stripe\Stripe;
use Config;
use Mail;
use App\Mail\Meanings;

class CronService
{
    protected $defineMeaning;
    protected $biddingRepo;
    protected $voteService;
    protected $illustrator;
    protected $minimum_bids;
    protected $minimum_votes;
    protected $bids_expiry;
    protected $vote_expiry;
    protected $voteExpiryRepo;
    protected $voteMeaningRepo;
    protected $userPoint;
    protected $translateRepo;
    protected $setting;
    protected $contextPhrase;
    protected $context;
    protected $phrase;
    protected $transactionRepo;
    protected $userRepo;
    protected $stripe;
    protected $userService;
    protected $roleService;

    /**
     * CronService constructor.
     */
    public function __construct()
    {
        $this->biddingRepo          =   new BiddingExpiryRepo();
        $this->defineMeaning        =   new DefineMeaningRepo();
        $this->voteService          =   new VoteService();
        $this->illustrator          =   new IllustratorRepo();
        $this->voteExpiryRepo       =   new VoteExpiryRepo();
        $this->voteMeaningRepo      =   new VoteMeaningRepo();
        $this->userPoint            =   new UserPointRepo();
        $this->userService          =   new UserService();
        $this->roleService          =   new RoleService();
        $this->translateRepo        =   new TranslationRepo();
        $this->setting              =   new SettingController();
        $this->contextPhrase        =   new ContextPhraseRepo();
        $this->context              =   new ContextRepo();
        $this->phrase               =   new PhraseRepo();
        $this->transactionRepo      =   new TransactionRepo();
        $this->userRepo             =   new UserRepo();
        $this->stripe               =   Stripe::make(env('STRIPE_SECRET', 'sk_test_v1HvFX0FFpIikIB48jsUwETa'));
    }

    /**
     * cron job for checking availability of meaning for vote
     */
    public function checkMeaning(){
        $this->bidExpiry(env('MEANING'), 'defineMeaning');
    }

    /**
     * update illustrator
     */
    public function checkIllustratorBid(){
        $this->bidExpiry(env('ILLUSTRATE'), 'illustrator');
    }

    /**
     * check expired votes for define
     */
    public function checkDefineExpiredVotes(){
        $this->checkExpiredVotes(env('MEANING'), 'defineMeaning',env('MEANING_KEY'));
    }

    /**
     * update illustrator
     */
    public function illustratorVotes(){
        $this->checkExpiredVotes(env('ILLUSTRATE'), 'illustrator',env('ILLUSTRATOR_KEY'));
    }

    public function checkTranslateBid(){
        $this->bidExpiry(env('TRANSLATE'), 'translateRepo');
    }

    public function translationVotes(){
        $this->checkExpiredVotes(env('TRANSLATE'), 'translateRepo',env('TRANSLATOR_KEY'));
    }
    /**
     * @param $type
     * @param $model
     * function for updating bids and add record for votes
     */
    public function bidExpiry($type, $model){
        $this->minimum_bids     =   $this->setting->getKeyValue(env('MINIMUM_BIDS'))->values;
        $this->bids_expiry      =   $this->setting->getKeyValue(env('BIDS_EXPIRY'))->values;
        $today=Carbon::now();
        $getAllMeaning=$this->biddingRepo->fetchBidding($type);
        if($getAllMeaning){
            foreach($getAllMeaning as $meaning):
                $context_id=$meaning['context_id'];
                $phrase_id=$meaning['phrase_id'];
                $expiry_date=$meaning['expiry_date'];
                if($context_id!=NULL && $phrase_id!=NULL):
                    $cron_job='0';
                    $checkCount=['context_id'=>$context_id, 'phrase_id'=>$phrase_id];
                    if($meaning['bid_type']==env('TRANSLATE')):
                        $checkCount['language']=$meaning['language'];
                    endif;
                    $checkTotal=$this->$model->totalRecords($checkCount);
                    if(Carbon::parse($expiry_date) < Carbon::parse($today)):
                        if($checkTotal >= $this->minimum_bids):
                            $cron_job='1';
                        else:
                            $date=Carbon::now()->addDays($this->bids_expiry);
                            $expiry_update=['expiry_date'=>$date];
                            $this->biddingRepo->updateBiddingStatus($meaning['id'], $expiry_update);
                        endif;
                    endif;
                    if($cron_job=='1'):
                        $voteArray=['context_id'=>$context_id, 'phrase_id'=>$phrase_id];
                        if($type==env('TRANSLATE')):
                            $voteArray['language']=$meaning['language'];
                        endif;
                        $updateMeaningStatus=$this->$model->updateMeaningStatus($voteArray);
                        $voteArray['vote_type']=$type;
                        $this->voteService->addPhraseForVote($voteArray);
                        $record_update=['status'=>'1'];
                        $updateBidding=$this->biddingRepo->updateBiddingStatus($meaning['id'], $record_update);
                        if($type==env('MEANING')):
                            $check=['context_id'=>$context_id, 'phrase_id'=>$phrase_id];
                            $updateRecord=['status'=>'1'];
                            $this->contextPhrase->updateStatus($check, $updateRecord);
                        endif;
                    endif;
                endif;
            endforeach;
        }
    }

    /**
     * @param $type
     * @param $model
     * @param $columnKey
     * check expired votes
     */
    public function checkExpiredVotes($type, $model,$columnKey){
        $this->minimum_votes    =   $this->setting->getKeyValue(env('MINIMUM_VOTES'))->values;
        $this->vote_expiry      =   $this->setting->getKeyValue(env('VOTE_EXPIRY'))->values;
        $first_position         =   $this->setting->getKeyValue(env('FIRST'))->values;
        $second_position        =   $this->setting->getKeyValue(env('SECOND'))->values;
        $third_position         =   $this->setting->getKeyValue(env('THIRD'))->values;
        $getVote=$this->voteExpiryRepo->getAllMeaningVotes($type);
        if($getVote):
            foreach($getVote as $vote):
                $cron_run='0';
                $totalVotes=['context_id'=>$vote['context_id'], 'phrase_id'=>$vote['phrase_id'], 'type'=>$type, 'vote'=>'1'];
                if($type==env('TRANSLATE')):
                    $totalVotes['language']=$vote['language'];
                endif;
                $getTotalVote=$this->voteMeaningRepo->totalVotes($totalVotes);
                if($vote['expiry_date'] < Carbon::now()):
                    if($getTotalVote >= $this->minimum_votes):
                        $cron_run='1';
                    else:
                        $date=Carbon::now()->addDays($this->vote_expiry);
                        $expiry_update=['expiry_date'=>$date];
                        $this->voteExpiryRepo->updateStatus($vote['id'], $expiry_update);
                    endif;
                endif;
                if($cron_run=='1'):
                    $checkArray=['context_id'=>$vote['context_id'], 'phrase_id'=>$vote['phrase_id'], 'type'=>$type, 'columnKey'=>$columnKey];
                    if($type==env('TRANSLATE')):
                        $checkArray['language']=$vote['language'];
                    endif;
                    $getHighestVotes=$this->voteMeaningRepo->hightVotes($checkArray);
                    foreach($getHighestVotes as $key=>$getVote):
                        foreach ($getHighestVotes as $key2=>$checkVote):
                            if($getVote['id']!=$checkVote['id'] && $getVote['total']==$checkVote['total']):
                                if($checkVote[$type]['coins'] > $getVote[$type]['coins']):
                                    $getHighestVotes[$key]=$checkVote;
                                    $getHighestVotes[$key2]=$getVote;
                                endif;
                            endif;
                        endforeach;
                    endforeach;
                    if(!empty($getHighestVotes)):
                        $updateVoteStatus=['context_id'=>$vote['context_id'], 'phrase_id'=>$vote['phrase_id'], 'status'=>'1'];
                        if($type==env('TRANSLATE')):
                            $updateVoteStatus['language']=$vote['language'];
                        endif;
                        $this->$model->updateVoteStatus($updateVoteStatus);
                        foreach($getHighestVotes as $key=>$hightesVotes):
                            if($key+1==1):
                                $position='1'.'<sup>st</sup>';
                                $points=$first_position;
                            elseif($key+1==2):
                                $position='2'.'<sup>nd</sup>';
                                $points=$second_position;
                            else:
                                $position='3'.'<sup>rd</sup>';
                                $points=$third_position;
                            endif;
                            $data=['type'=>$type,'point'=>$points,'context_id'=>$vote['context_id'], 'phrase_id'=>$vote['phrase_id'], 'user_id'=>$hightesVotes[$type]['user_id'], 'position'=>$key+1];
                            $this->userPoint->create($data);
                            $hightesVotes['points']=$points;
                            $hightesVotes['position']=$position;
                            $getContextName=$this->context->getContextName($vote['context_id']);
                            $getPhraseName=$this->phrase->getPhraseName($vote['phrase_id']);
                            if($getContextName):
                                $hightesVotes['context_name']=$getContextName->context_name;
                            endif;
                            if($getPhraseName):
                                $hightesVotes['phrase_name']=$getPhraseName->phrase_text;
                            endif;
                            $hightesVotes['type']=$type;
                            /** bonus coins winner*/
                            $winnerArray=[$columnKey=>$hightesVotes[$columnKey]];
                            $getWinnerVote=$this->voteMeaningRepo->getWinnerVotes($winnerArray);
                            if($getWinnerVote){
                                foreach($getWinnerVote as $winnerVote){
                                    $bonusMail=['first_name'=>$winnerVote['user']['first_name'],'last_name'=>$winnerVote['user']['last_name'],'type'=>$type, 'phrase_name'=>$getPhraseName->phrase_text];
                                    Mail::to($winnerVote['user']['email'])->send(new BonusCoinsMail($bonusMail));
                                    $coins=['coins'=>$winnerVote['user']['coins']+1];
                                    $user = $this->userRepo->update($winnerVote['user']['id'], $coins);
                                }
                            }
                            /** bonus coins winner*/
                            Mail::to($hightesVotes[$type]['users']['email'])->send(new Meanings($hightesVotes));
                            $this->$model->update(['position'=>$key+1], $hightesVotes[$type]['id']);
                        endforeach;
                        $this->sendPointsToUser($updateVoteStatus, $model, $type);
                        $records=['status'=>'1'];
                        $this->voteExpiryRepo->updateStatus($vote['id'], $records);
                    endif;
                endif;
            endforeach;
        endif;
    }

    public function subscriptionCheck(){
        $transactions = $this->transactionRepo->getRecord(['status'=> 1]);
        foreach ($transactions as $transaction){
            $user = $this->userRepo->findById($transaction->user_id);
            if($transaction->sub == 1){
                if($transaction->expiry_date != '' && $transaction->expiry_date < carbon::now()) {
                    $subscription = $this->stripe->subscriptions()->find($user->cus_id, $transaction->transaction_id);
                    if ($subscription['status'] == 'trialing' || $subscription['status'] == 'active') {
                        $this->transactionRepo->update(['id' => $transaction->id], ['expiry_date' => date("Y-m-d H:i:s", $subscription['current_period_end'])]);
                    }
                    else {
                        if(!$user->hasRole(Config::get('constant.contributorRole'))){
                            $this->roleService->assign($user->id, 7);
                            $this->userService->updateRecord($transaction->user_id, ['user_roles' => 7]);
                        }
                        $this->transactionRepo->update($transaction->id, ['status' => 0]);
                    }
                }
            }
            else{
                if($transaction->expiry_date != '' && $transaction->expiry_date < carbon::now()) {
                    $this->roleService->assign($user->id, 7);
                    $this->userService->updateRecord($transaction->user_id, ['user_roles' => 7]);
                }
            }
        }
    }

    public function sendPointsToUser($data, $model, $type){
        $data['status'] = 3;
        $data['position'] = NULL;
        $get_users = $this->$model->getContributors($data);
        if($get_users){
            foreach($get_users as $key=>$hightesVotes):
                $data=['type'=>$type,'point'=>env('POINT'),'context_id'=>$data['context_id'], 'phrase_id'=>$data['phrase_id'], 'user_id'=>$hightesVotes['user_id'], 'position'=>NULL];
                $this->userPoint->create($data);
                $hightesVotes['points']=env('POINT');
                $getContextName=$this->context->getContextName($data['context_id']);
                $getPhraseName=$this->phrase->getPhraseName($data['phrase_id']);
                if($getContextName):
                    $hightesVotes['context_name']=$getContextName->context_name;
                endif;
                if($getPhraseName):
                    $hightesVotes['phrase_name']=$getPhraseName->phrase_text;
                endif;
                $hightesVotes['type']=$type;
                Mail::to($hightesVotes['users']['email'])->send(new ExtraPointToContributors($hightesVotes));
            endforeach;
        }
        return true;
    }
}