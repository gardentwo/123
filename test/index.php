<?php
session_start();
if($_SESSION['user']){
    header('Location: profile.php');
}
// Очищаем данные формы при переходе на главную страницу
unset($_SESSION['form_data']);

// ... остальной код index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация и регистрация</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
     <!-- Форма авторизации -->
     <form action="vendor/signin.php" method="post">
        <label>Логин</label>
        <input type="text" name="login" placeholder="Введите свой логин">
        <label>Пароль</label>
        <input type="password" name="password" placeholder="Введите свой пароль">
        <button>Войти</button>
        <p>
            У вас нет аккаунта? - <a href="/register.php">Зарегестрируйтесь</a>

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