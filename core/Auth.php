<?php
class Auth {

    public static function userId() {
        if (empty($_COOKIE['uid'])) {
            return 0;
        }
        return intval($_COOKIE['uid']);
    }

    public static function check() {
        $uid = self::userId();
        if (!$uid) {
            Response::json([], 401, '未登录');
        }
        return $uid;
    }
}