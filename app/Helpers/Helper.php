<?php

namespace App\Helpers;

class Helper {

    public static function responseJson(int $code, string $message, $data = null){
        $metadata = [
            'message' => $message,
            'code' => $code,
        ];

        $response = [
            'metadata' => $metadata,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }
}


