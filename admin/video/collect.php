<?php
require '../common/auth.php';
require '../../core/DB.php';

// 第三方接口地址
$cfg = DB::fetch("SELECT value FROM site_config WHERE `key`='video_api'");
if (!$cfg || !$cfg['value']) {
    exit('未配置视频接口');
}

$api = $cfg['value'];

$json = file_get_contents($api);
$data = json_decode($json, true);

if (!$data || empty($data['list'])) {
    exit('接口无数据');
}

foreach ($data['list'] as $v) {

    $title = addslashes($v['name']);
    $play  = addslashes($v['url']);

    // 去重：同播放地址只进一次
    $exists = DB::fetch("SELECT id FROM video WHERE play_url='$play'");
    if ($exists) {
        continue;
    }

    DB::query("
        INSERT INTO video
        (title,play_url,cover,duration,category,tags,is_vip,price,status)
        VALUES (
            '$title',
            '$play',
            '{$v['pic']}',
            {$v['time']},
            '{$v['type']}',
            '{$v['type']}',
            0,
            0,
            1
        )
    ");
}

echo '采集完成';