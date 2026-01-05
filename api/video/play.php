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

/* 1️⃣ 免费视频 */
if ($video['is_vip'] == 0 && $video['price'] <= 0) {
    Response::json([
        'can_play' => true,
        'play_url' => $video['play_url']
    ]);
}

/* 未登录，只给试看 */
if (!$uid) {
    Response::json([
        'can_play' => false,
        'try_seconds' => 30,
        'need' => ['vip','pay','point','card']
    ], 403, '未登录');
}

/* 2️⃣ VIP 校验 */
$user = DB::fetch("SELECT vip_expire,point FROM user WHERE id=$uid");
if ($user && strtotime($user['vip_expire']) > time()) {
    Response::json([
        'can_play' => true,
        'play_url' => $video['play_url'],
        'via' => 'vip'
    ]);
}

/* 3️⃣ 单片购买校验 */
$buy = DB::fetch("SELECT id FROM video_order WHERE uid=$uid AND vid=$vid");
if ($buy) {
    Response::json([
        'can_play' => true,
        'play_url' => $video['play_url'],
        'via' => 'pay'
    ]);
}

/* 4️⃣ 积分解锁校验 */
$pointUsed = DB::fetch("
    SELECT id FROM point_log 
    WHERE uid=$uid AND vid=$vid AND change<0
");
if ($pointUsed) {
    Response::json([
        'can_play' => true,
        'play_url' => $video['play_url'],
        'via' => 'point'
    ]);
}

/* 5️⃣ 卡密解锁校验 */
$cardUsed = DB::fetch("
    SELECT id FROM card 
    WHERE used_by=$uid AND value=$vid
");
if ($cardUsed) {
    Response::json([
        'can_play' => true,
        'play_url' => $video['play_url'],
        'via' => 'card'
    ]);
}

/* ❌ 都没解锁 */
Response::json([
    'can_play' => false,
    'try_seconds' => 30,
    'price' => $video['price'],
    'need' => ['vip','pay','point','card']
], 403, '需要解锁');