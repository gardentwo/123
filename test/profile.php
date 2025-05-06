<?php
session_start();
if(!isset($_SESSION['user'])){
    header('Location: index.php');
    exit();
}
date_default_timezone_set('Europe/Moscow'); // UTC+3
unset($_SESSION['form_data']);

// Подключение к базе данных
$db = new mysqli('localhost', 'root', 'root', 'test');
if ($db->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Ошибка подключения к базе данных']);
    exit;
}

// Получаем результаты теста для текущего пользователя
$test1Results = [];
if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
    $stmt = $db->prepare("SELECT answer_right, answer_wrong FROM test1 WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $test1Results = $result->fetch_assoc();
    $stmt->close();
}
$db->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="assets/css/profile1.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>Личный кабинет</h1>
            <a href="vendor/logout.php" class="btn btn-logout">Выход</a>
        </div>

        <div class="profile-info">
            <h2>Добро пожаловать, <?= htmlspecialchars($_SESSION['user']['full_name']) ?>!</h2>
            <p>Дата последнего входа: <?=date("Y-m-d H:i:s"); ?></p>
        </div>

        <div class="profile-section">
            <h3>Личная информация</h3>
            <div class="info-row">
                <div class="info-label">ФИО:</div>
                <div class="info-value"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Логин:</div>
                <div class="info-value"><?= htmlspecialchars($_SESSION['user']['login']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Дата регистрации:</div>
                <div class="info-value"><?= date('d.m.Y -- H:i', strtotime($_SESSION['user']['created_at'] ?? 'now')); ?></div>
            </div>
        </div>

        <div class="profile-section">
            <h3>Безопасность</h3>
            <div class="info-row">
                <div class="info-label">Статус аккаунта:</div>
                <div class="info-value">Активен</div>
            </div>
        </div>

        <!-- Блок с результатами теста -->
<div class="profile-section test-results">
    <h3>Результаты теста "DNS и Сетевая Безопасность"</h3>
    
    <?php if (!empty($test1Results)): ?>
        <?php 
        $totalQuestions = 12; // Фиксированное количество вопросов в тесте
        $correctAnswers = $test1Results['answer_right'] ?? 0;
        $wrongAnswers = $test1Results['answer_wrong'] ?? 0;
        $answeredQuestions = $correctAnswers + $wrongAnswers;
        $correctPercentage = $answeredQuestions > 0 ? round(($correctAnswers / $answeredQuestions) * 100) : 0;
        $totalPercentage = round(($correctAnswers / $totalQuestions) * 100);
        $total1Percentage = round(($wrongAnswers / $totalQuestions) * 100);
        ?>
        
        <div class="result-row">
            <div class="result-label">Правильных ответов:</div>
            <div><?= $correctAnswers ?> из <?= $totalQuestions ?></div>
        </div>
        <div class="result-row">
            <div class="result-label">Неправильных ответов:</div>
            <div><?= $wrongAnswers ?> из <?= $totalQuestions ?></div>
        </div>
        <div class="result-row">
            <div class="result-label">Отвечено вопросов:</div>
            <div><?= $answeredQuestions ?> из <?= $totalQuestions ?></div>
        </div>
        
        <div class="progress-container">
            <div class="progress-bar" style="width: <?= $totalPercentage ?>%">
               <?= $totalPercentage ?>%
            </div>
        </div>
        
        <div class="progress-container">
            <div class="progress-bar" style="width: <?= $totalPercentage ?>%">
                (<?= $correctAnswers ?>)
            </div>
            <?php if ($wrongAnswers > 0): ?>
                <div class="progress-bar wrong-answers" style="width: <?= $total1Percentage ?>%">
                    (<?= $wrongAnswers ?>)
                </div>
            <?php endif; ?>
        </div>
        
        <p class="test-advice">
            <?php if ($answeredQuestions < $totalQuestions): ?>
                <strong>Вы ответили не на все вопросы.</strong> 
                <a href="test1.php">Попробуйте пройти тест еще раз</a>, чтобы улучшить результат.
            <?php endif; ?>
        </p>
    <?php else: ?>
        <p>Вы еще не проходили тест. <a href="test1.php">Пройти тест</a></p>
    <?php endif; ?>
</div>
</body>
</html>