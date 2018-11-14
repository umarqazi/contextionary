<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 03/09/18
 * Time: 16:06
 */

namespace App\Http\Controllers;

use App\Services\ReadingAssistantService;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Request;
use Carbon\Carbon;
use Auth;
use GuzzleHttp\Client;

class ReadingAssistantController extends Controller
{

    /**
     * @var ReadingAssistantService
     */
    protected $read_assistant_service;

    /**
     * ReadingAssistantController constructor.
     */
    public function __construct()
    {
        $this->read_assistant_service = new ReadingAssistantService();
    }

    /**
     * @return mixed
     */
    public function contextFinder(){
        $context='';
        return view::make('user.user_plan.reading_assistant.context_finder')->with('context', $context);
    }

    /**
     * @param Request $request
     */
    public function pContextFinder(Request $request){
        $data           =   [
                                'text'=>$request->context,
                                'user_id'=>Auth::user()->id,
                                'date'=>Carbon::now()
                            ];
        $history        =   $this->read_assistant_service->saveHistory($data);
        $final_string   =   str_replace(' ', '_', $request->context);
        $client         =   new Client();
        $res            =   $client->get(env('API_URL').$final_string);
        $response       =   $res->getStatusCode(); // 200
        if( $response   ==  '200'){
            $body       =   json_decode($res->getBody());
            foreach($body as $content){
                $context_body=serialize($content);
                foreach($context_body as $context){
                    echo $context;
                    echo "<br>";
                }
            }
        }
    }
}