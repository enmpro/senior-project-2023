<?php
session_start();

// clear the session
$_SESSION = array();

// destroy the cookie
if (isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time() - 86400, '/');
}

// destroy the session
session_destroy();

header('Location: landing.html');
exit;
?>