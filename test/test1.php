<?php 
session_start();
if(!isset($_SESSION['user'])){
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тест: DNS и Сетевая Безопасность</title>
    
    <link rel="stylesheet" href="assets//maintest.css">
</head>
<body>
    <div class="test-container">
        <h1>Тест: DNS и Сетевая Безопасность</h1>
        
        <div id="questions-container"></div>
        
        <button onclick="checkAnswers()">Проверить ответы</button>
        <div id="results" class="results"></div>
    </div>

   <script src ='vendor/test.js'>
   
   </script>

</body>
</html>