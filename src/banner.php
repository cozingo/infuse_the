<?php
require 'DB.php';

$dsn = "mysql:host=mysql:3306;dbname=infuse";
$db = new DB($dsn, 'infuse', 'infuse');
$db->logVisit();


$img="hello.jpg";
header ('Content-type: image/jpeg');
readfile($img);