<?php
require '../../core/DB.php';
require '../../core/Auth.php';
require '../../core/Response.php';

$vid = intval($_POST['vid']);
$uid = Auth::userId();

$video = DB::fetch("SELECT * FROM video WHERE id=$vid");

if (!$video) {
    Response::json([], 404, '视频不存在');
}

if ($video['is_vip'] == 0) {
    Response::json([
        'can_play' => true,
        'play_url' => $video['play_url']
    ]);
}

// VIP 视频
if ($uid) {
    $user = DB::fetch("SELECT vip_expire FROM user WHERE id=$uid");
    if ($user && strtotime($user['vip_expire']) > time()) {
        Response::json([
            'can_play' => true,
            'play_url' => $video['play_url']
        ]);
    }
}

Response::json([
    'can_play' => false,
    'try_seconds' => 30
], 403, '需要解锁');