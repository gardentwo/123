<?php 
$db = new mysqli('localhost', 'root', 'root', 'test');
if ($db->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Ошибка подключения к базе данных']);
    exit;
}


$connect = mysqli_connect('localhost', 'root', 'root', 'test'); 
if(!$connect){ 
    die ('Errror connect to database'); 
}

?>