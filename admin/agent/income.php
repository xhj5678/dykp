<?php
require '../common/auth.php';
require '../../core/DB.php';

$list = DB::fetchAll("
    SELECT * FROM agent_income ORDER BY time DESC
");

foreach ($list as $r) {
    echo "UID{$r['uid']} +{$r['amount']} 元<br>";
}