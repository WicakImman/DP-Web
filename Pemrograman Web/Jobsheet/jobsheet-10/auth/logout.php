<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/remember.php';

hapus_cookie_ingat($_SESSION['user_id'] ?? null);

$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;