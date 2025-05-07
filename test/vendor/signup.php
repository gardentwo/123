<?php
session_start();
require_once 'connect.php';

$_SESSION['form_data'] = [
    'full_name' => $_POST['full_name'],
    'login' => $_POST['login']
];

$full_name = $_POST['full_name'];
$login = $_POST['login'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

// Проверка на существующий логин
$stmt = mysqli_prepare($connect, "SELECT id FROM users WHERE login = ?");
mysqli_stmt_bind_param($stmt, "s", $login);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    $_SESSION['message'] = 'Пользователь с таким логином уже существует';
    header('Location: ../register.php');
    exit();
}



if (empty($full_name) || empty($login) || empty($password) || empty($password_confirm)) {
    $_SESSION['message'] = 'Все поля должны быть заполнены';
    header('Location: ../register.php');
    exit();
}

if (strlen($login) < 4 || strlen($login) > 20) {
    $_SESSION['message'] = 'Логин должен быть от 4 до 20 символов';
    header('Location: ../register.php');
    exit();
}

// Проверка на допустимые символы в логине (только буквы и цифры)
if (!preg_match('/^[a-zA-Z0-9]+$/', $login)) {
    $_SESSION['message'] = 'Логин может содержать только латинские буквы и цифры';
    header('Location: ../register.php');
    exit();
}

// Проверка длины пароля (например, минимум 6 символов)
if (strlen($password) < 6) {
    $_SESSION['message'] = 'Пароль должен содержать минимум 6 символов';
    header('Location: ../register.php');
    exit();
}

if ($password === $password_confirm) {
    // Используем подготовленные запросы для безопасности
    $password=md5($password);
    $stmt = mysqli_prepare($connect, "INSERT INTO `users` (`full_name`, `login`, `password`) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $full_name, $login, $password);
    mysqli_stmt_execute($stmt);
    $_SESSION['message'] = 'Регистрация прошла успешно';
    header('Location: ../index.php');
    exit(); // Обязательно завершаем выполнение скрипта
} else {
    $_SESSION['message'] = 'Пароли не совпадают';
    header('Location: ../register.php');
    exit();
}
?>