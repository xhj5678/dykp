<?php
require '../common/auth.php';
require '../../core/DB.php';

if ($_POST) {
    DB::query("UPDATE ad SET content='{$_POST['html']}' WHERE type='popup'");
}