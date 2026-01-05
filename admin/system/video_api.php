<?php
require '../common/auth.php';
require '../../core/DB.php';

if ($_POST) {
    DB::query("
        REPLACE INTO site_config(`key`,`value`)
        VALUES ('video_api','{$_POST['api']}')
    ");
    echo '保存成功';
}

$cfg = DB::fetch("SELECT value FROM site_config WHERE `key`='video_api'");
$api = $cfg['value'] ?? '';
?>

<form method="post">
    视频采集接口：<br>
    <input type="text" name="api" value="<?=htmlspecialchars($api)?>" style="width:500px">
    <br><br>
    <button>保存</button>
</form>