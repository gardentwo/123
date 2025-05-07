<?php
session_start();
if($_SESSION['user']){
    header('Location: profile.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация и регистрация</title>
    <link rel="stylesheet" href="assets//css//main.css">
</head>
<body>
     <!-- Форма авторизации -->
     <form action="vendor/signup.php" method="post">
     <label>ФИО</label>
     <input type="text" name="full_name" placeholder="Введите свое полное имя" value="<?= isset($_SESSION['form_data']['full_name']) ? htmlspecialchars($_SESSION['form_data']['full_name']) : '' ?>">
        <label>Логин</label>
        <input type="text" name="login" placeholder="Введите свой логин" value="<?= isset($_SESSION['form_data']['login']) ? htmlspecialchars($_SESSION['form_data']['login']) : '' ?>">
        <label>Пароль</label>
        <input type="password" name="password" placeholder="Введите свой пароль">
        <label>Подтверждение пароля</label>
        <input type="password" name="password_confirm" placeholder="Подтвердите пароль">
        <button>Зарегистрироваться</button>
        <p>
            У вас уже есть аккаунт? - <a href="/index.php">Авторизация</a>
        </p>

        <?php
        if(isset($_SESSION['message'])) {
            echo '<p class="msg">' . $_SESSION['message'] . '</p>';
            unset($_SESSION['message']);
        }
        if(isset($_SESSION['form_data'])) {
            unset($_SESSION['form_data']);
        }
        ?>
    </form>
</body>
</html>