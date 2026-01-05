<?php
require '../../core/DB.php';
require '../../core/Auth.php';
require '../../core/Response.php';

$uid  = Auth::check();
$code = trim($_POST['code']);

$card = DB::fetch("
    SELECT * FROM card
    WHERE code='$code' AND used=0
");

if (!$card) {
    Response::json([], 400, '卡密无效或已使用');
}

/* 标记使用 */
DB::query("
    UPDATE card SET 
        used=1,
        used_by=$uid,
        used_time=NOW()
    WHERE id={$card['id']}
");

Response::json([
    'type'  => $card['type'],
    'value' => $card['value']
], 0, '卡密使用成功');