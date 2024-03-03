<?php
$servername = "db4free.net";
$username = "gabrielsamv2";
$password = "damiao12";
$dbname = "lua_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}
?>
