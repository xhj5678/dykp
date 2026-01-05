<?php
if (empty($_COOKIE['admin'])) {
    header('Location: /admin/login.php');
    exit;
}