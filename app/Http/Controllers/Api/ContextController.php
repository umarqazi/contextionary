<?php

namespace App\Http\Controllers\Api;

use App\Context;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContextController extends Controller
{
    /**
     *
    @SWG\Post(
     *     path="/contexts",
     *     description="Context",
     *
    @SWG\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *
     * )
     */
    public function contexts(){

        $contexts = Context::all();
        return json('Context are:','200', $contexts );
    }
}
