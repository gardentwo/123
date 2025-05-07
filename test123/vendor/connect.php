<?php 
$connect = mysqli_connect('localhost', 'root', 'root', 'test'); 
if(!$connect){ 
    die ('Errror connect to database'); 
}