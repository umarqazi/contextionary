<?php

if(!defined('PERCENTAGE'))
    define('PERCENTAGE', 20);

function json($msg, $code = 200, $data = null) {
    return response()->json(['message' => $msg, 'code' => $code, 'data' => $data], 200);
}