<?php
session_start();
if($_SESSION['user']){
    header('Location: profile.php');
}
unset($_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация | Система тестирования</title>
    <link rel="stylesheet" href="assets//css//main.css">
</head>
<body>
    <form action="vendor/signin.php" method="post">
        <h2 style="text-align: center; margin-bottom: 2rem; color: #333;">Вход в систему</h2>
        <?php
        if(isset($_SESSION['message'])) {
            echo '<p class="msg">' . $_SESSION['message'] . '</p>';
            unset($_SESSION['message']);
        }
        if(isset($_SESSION['form_data'])) {
            unset($_SESSION['form_data']);
        }
        ?>
        <label for="login">Логин</label>
        <input type="text" id="login" name="login" placeholder="Введите свой логин" required>
        
        <label for="password">Пароль</label>
        <input type="password" id="password" name="password" placeholder="Введите свой пароль" required>
        
        <button type="submit">Войти</button>
        
        <p>
            У вас нет аккаунта? <a href="register.php">Зарегистрироваться</a>
        </p>
    </form>
</body>
</html>