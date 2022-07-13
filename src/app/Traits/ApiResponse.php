<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{
    public function respondData($data = null, $status = Response::HTTP_OK, $headers = [])
    {
        return response()->json($data, $status, $headers);
    }

    public function responseError()
    {
        //
    }
}
