<?php

namespace App\Http\Controllers\Api;

use App\CoffeeBreak;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class CoffeeController extends Controller
{
    /**
     *
    @SWG\Post(
     *     path="/coffee_break",
     *     description="Coffee Break Quotes",
     *
    @SWG\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *
     * )
     */
    public function coffee_quotes(){

        $coffee_quotes = CoffeeBreak::all();

        $batch = [];
        foreach ($coffee_quotes as $key => $data){

            $batch['coffee_quotes'][] = [
                'quote' => $data->quote,
                'author' => $data->author,
            ];
        }

        if($batch){

            $batch['coffee_quotes'] = array_values($batch['coffee_quotes']);
            return json('Coffee break quotes are:', 200, $batch);
        }else{

            return json('Data not found:', 400);
        }
    }
}
