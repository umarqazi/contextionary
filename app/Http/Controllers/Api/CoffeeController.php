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

        $quotes = CoffeeBreak::inRandomOrder()->get();
        $length = $this->percentage($quotes->count());
        $coffee_quotes = new Paginator($quotes, $length);

        $batch = [];
        foreach ($coffee_quotes as $key => $data){

            ($key==0) ? $batch['has_more'] = $coffee_quotes->hasMorePages() : false;
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

    private function percentage($length) {
        if($length > 100) {
            $length *= PERCENTAGE;
            $length /= 100;
        } else {
            $length = 20;
        }
        return $length;
    }
}
