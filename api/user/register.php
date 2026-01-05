<?php
require '../../core/DB.php';
require '../../core/Response.php';

$username = trim($_POST['username']);
$password = trim($_POST['password']);
$inviteCode = trim($_POST['invite_code'] ?? '');

if (!$username || !$password) {
    Response::json([], 400, '参数不完整');
}

// 是否已存在
$exists = DB::fetch("SELECT id FROM user WHERE username='$username'");
if ($exists) {
    Response::json([], 400, '账号已存在');
}

// 创建用户
$pwd = md5($password);
DB::query("
    INSERT INTO user(username,password,created_at)
    VALUES ('$username','$pwd',NOW())
");

$uid = DB::connect()->insert_id;

// 生成自己的邀请码
$myCode = $uid . strtoupper(substr(md5($uid.time()),0,4));
DB::query("UPDATE user SET invite_code='$myCode' WHERE id=$uid");

// 👉 绑定邀请关系（只能一次）
if ($inviteCode) {

    $parent = DB::fetch("SELECT id FROM user WHERE invite_code='$inviteCode'");

    // 防止绑自己
    if ($parent && $parent['id'] != $uid) {

        // 防止重复绑定
        $bind = DB::fetch("SELECT uid FROM invite WHERE uid=$uid");
        if (!$bind) {
            DB::query("
                INSERT INTO invite(uid,parent_uid)
                VALUES ($uid,{$parent['id']})
            ");
        }
    }
}

// 登录态
setcookie('uid', $uid, time()+86400*30, '/');

Response::json([
    'uid' => $uid,
    'invite_code' => $myCode
]);

// ===== 邀请奖励积分 =====
if ($inviteCode && $parent) {
    $cfg = require '../../config/config.php';
    $reward = $cfg['point']['invite_reward'];

    DB::query("
        UPDATE user SET point = point + $reward
        WHERE id = {$parent['id']}
    ");

    DB::query("
        INSERT INTO point_log(uid,change,reason,time)
        VALUES ({$parent['id']},$reward,'邀请奖励',NOW())
    ");
}