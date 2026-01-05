<?php
class DB {
    private static $db;

    public static function connect() {
        if (!self::$db) {
            $cfg = require __DIR__ . '/../config/db.php';
            self::$db = new mysqli(
                $cfg['host'],
                $cfg['user'],
                $cfg['pass'],
                $cfg['name']
            );
            self::$db->set_charset($cfg['charset']);
        }
        return self::$db;
    }

    public static function query($sql) {
        return self::connect()->query($sql);
    }

    public static function fetch($sql) {
        return self::query($sql)->fetch_assoc();
    }

    public static function fetchAll($sql) {
        $res = self::query($sql);
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}