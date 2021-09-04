<?php 

use productsDB\DB as DB;

require_once './config/db.php';

$db = new DB();
$conn = $db->connect();

$sql = file_get_contents('database.sql');

$conn->exec($sql);

