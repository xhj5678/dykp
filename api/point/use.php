<?php
require '../../core/DB.php';
require '../../core/Auth.php';
require '../../core/Response.php';

$uid = Auth::check();
$vid = intval($_POST['vid']);

$cfg = require '../../config/config.php';
$cost = $cfg['point']['video_cost'];

// 是否已解锁
$used = DB::fetch("
    SELECT id FROM point_log
    WHERE uid=$uid AND vid=$vid AND change<0
");
if ($used) {
    Response::json([], 0, '已解锁');
}

// 当前积分
$user = DB::fetch("SELECT point FROM user WHERE id=$uid");
if ($user['point'] < $cost) {
    Response::json([], 403, '积分不足');
}

// 扣积分
DB::query("
    UPDATE user SET point = point - $cost
    WHERE id=$uid
");

DB::query("
    INSERT INTO point_log(uid,vid,change,reason,time)
    VALUES ($uid,$vid,-$cost,'积分解锁视频',NOW())
");

Response::json([], 0, '解锁成功');