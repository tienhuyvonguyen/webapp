<?php
require 'rb-mysql.php';
session_start();
$host = $_SERVER['DB_HOST'];
$user = $_SERVER['DB_USER'];
$pass = $_SERVER['DB_PASS'];
$db = $_SERVER['DB_NAME'];

R::setup("mysql:host=$host;dbname=$db", $user, $pass); 
if (!R::testConnection()) {
    exit ('No DB connection!');
}
?>