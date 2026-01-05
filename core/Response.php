<?php
class Response {
    public static function json($data = [], $code = 0, $msg = 'ok') {
        echo json_encode([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}