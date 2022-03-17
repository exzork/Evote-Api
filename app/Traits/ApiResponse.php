<?php

namespace App\Traits;

trait ApiResponse
{
    public function success($data = null, $code = 200, $message="")
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message'=> $message
        ], $code);
    }

    public function error($data = null, $code = 400, $message = '')
    {
        return response()->json([
            'success' => false,
            'data' => $data,
            'message' => $message
        ], $code);
    }
}
