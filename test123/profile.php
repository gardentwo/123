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
$test2Results = [];
if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
    $stmt = $db->prepare("SELECT answer_right, answer_wrong FROM test2 WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $test2Results = $result->fetch_assoc();
    $stmt->close();
}
$test3Results = [];
if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
    $stmt = $db->prepare("SELECT answer_right, answer_wrong FROM test3 WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $test3Results = $result->fetch_assoc();
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
    <link rel="stylesheet" href="assets//css//profile.css">

   
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

        <!-- Тест 1: DNS и Сетевая Безопасность -->
        <div class="test-container">
            <div class="test-header">
                <h3>Результаты теста "DNS и Сетевая Безопасность"</h3>
            </div>
            
            <?php if (!empty($test1Results)): ?>
                <?php 
                $totalQuestions = 12;
                $correctAnswers = $test1Results['answer_right'] ?? 0;
                $wrongAnswers = $test1Results['answer_wrong'] ?? 0;
                $answeredQuestions = $correctAnswers + $wrongAnswers;
                $totalPercentage = round(($correctAnswers / $totalQuestions) * 100);
                $total1Percentage = round(($wrongAnswers / $totalQuestions) * 100);
                ?>
                
                <div class="result-grid">
                    <div class="result-item">
                        <div>Правильных ответов</div>
                        <div class="result-value"><?= $correctAnswers ?>/<?= $totalQuestions ?></div>
                    </div>
                    <div class="result-item">
                        <div>Неправильных ответов</div>
                        <div class="result-value"><?= $wrongAnswers ?>/<?= $totalQuestions ?></div>
                    </div>
                    <div class="result-item">
                        <div>Отвечено вопросов</div>
                        <div class="result-value"><?= $answeredQuestions ?>/<?= $totalQuestions ?></div>
                    </div>
                </div>
                
                <div class="progress-container">
                    <div>Общий прогресс:</div>
                    <div class="progress-bar" style="width: <?= $totalPercentage ?>%">
                        <?= $totalPercentage ?>%
                    </div>
                </div>
                
                <div class="progress-container">
                    <div>Детализация ответов:</div>
                    <div style="display: flex;">
                        <div class="progress-bar" style="width: <?= $totalPercentage ?>%">
                            <?= $correctAnswers ?>
                        </div>
                        <?php if ($wrongAnswers > 0): ?>
                            <div class="progress-bar wrong-answers" style="width: <?= $total1Percentage ?>%">
                                <?= $wrongAnswers ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($answeredQuestions < $totalQuestions): ?>
                    <p class="test-advice">
                        <strong>Вы ответили не на все вопросы.</strong> 
                        Пройдите тест повторно для улучшения результата.
                    </p>
                <?php endif; ?>
                
                <a href="test1.php" class="btn-test">Пройти тест снова</a>
            <?php else: ?>
                <p>Вы еще не проходили этот тест.</p>
                <a href="test1.php" class="btn-test">Пройти тест</a>
            <?php endif; ?>
        </div>

        <!-- Тест 2: Основы Информационной Безопасности -->
        <div class="test-container">
            <div class="test-header">
                <h3>Результаты теста "Основы Информационной Безопасности"</h3>
            </div>
            
            <?php if (!empty($test2Results)): ?>
                <?php 
                $totalQuestions = 10;
                $correctAnswers = $test2Results['answer_right'] ?? 0;
                $wrongAnswers = $test2Results['answer_wrong'] ?? 0;
                $answeredQuestions = $correctAnswers + $wrongAnswers;
                $totalPercentage = round(($correctAnswers / $totalQuestions) * 100);
                $total1Percentage = round(($wrongAnswers / $totalQuestions) * 100);
                ?>
                
                <div class="result-grid">
                    <div class="result-item">
                        <div>Правильных ответов</div>
                        <div class="result-value"><?= $correctAnswers ?>/<?= $totalQuestions ?></div>
                    </div>
                    <div class="result-item">
                        <div>Неправильных ответов</div>
                        <div class="result-value"><?= $wrongAnswers ?>/<?= $totalQuestions ?></div>
                    </div>
                    <div class="result-item">
                        <div>Отвечено вопросов</div>
                        <div class="result-value"><?= $answeredQuestions ?>/<?= $totalQuestions ?></div>
                    </div>
                </div>
                
                <div class="progress-container">
                    <div>Общий прогресс:</div>
                    <div class="progress-bar" style="width: <?= $totalPercentage ?>%">
                        <?= $totalPercentage ?>%
                    </div>
                </div>
                
                <div class="progress-container">
                    <div>Детализация ответов:</div>
                    <div style="display: flex;">
                        <div class="progress-bar" style="width: <?= $totalPercentage ?>%">
                            <?= $correctAnswers ?>
                        </div>
                        <?php if ($wrongAnswers > 0): ?>
                            <div class="progress-bar wrong-answers" style="width: <?= $total1Percentage ?>%">
                                <?= $wrongAnswers ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($answeredQuestions < $totalQuestions): ?>
                    <p class="test-advice">
                        <strong>Вы ответили не на все вопросы.</strong> 
                        Пройдите тест повторно для улучшения результата.
                    </p>
                <?php endif; ?>
                
                <a href="test2.php" class="btn-test">Пройти тест снова</a>
            <?php else: ?>
                <p>Вы еще не проходили этот тест.</p>
                <a href="test2.php" class="btn-test">Пройти тест</a>
            <?php endif; ?>
        </div>

        <!-- Тест 3: Социальная Инженерия -->
        <div class="test-container">
            <div class="test-header">
                <h3>Результаты теста "Социальная Инженерия"</h3>
            </div>
            
            <?php if (!empty($test3Results)): ?>
                <?php 
                $totalQuestions = 10;
                $correctAnswers = $test3Results['answer_right'] ?? 0;
                $wrongAnswers = $test3Results['answer_wrong'] ?? 0;
                $answeredQuestions = $correctAnswers + $wrongAnswers;
                $totalPercentage = round(($correctAnswers / $totalQuestions) * 100);
                $total1Percentage = round(($wrongAnswers / $totalQuestions) * 100);
                ?>
                
                <div class="result-grid">
                    <div class="result-item">
                        <div>Правильных ответов</div>
                        <div class="result-value"><?= $correctAnswers ?>/<?= $totalQuestions ?></div>
                    </div>
                    <div class="result-item">
                        <div>Неправильных ответов</div>
                        <div class="result-value"><?= $wrongAnswers ?>/<?= $totalQuestions ?></div>
                    </div>
                    <div class="result-item">
                        <div>Отвечено вопросов</div>
                        <div class="result-value"><?= $answeredQuestions ?>/<?= $totalQuestions ?></div>
                    </div>
                </div>
                
                <div class="progress-container">
                    <div>Общий прогресс:</div>
                    <div class="progress-bar" style="width: <?= $totalPercentage ?>%">
                        <?= $totalPercentage ?>%
                    </div>
                </div>
                
                <div class="progress-container">
                    <div>Детализация ответов:</div>
                    <div style="display: flex;">
                        <div class="progress-bar" style="width: <?= $totalPercentage ?>%">
                            <?= $correctAnswers ?>
                        </div>
                        <?php if ($wrongAnswers > 0): ?>
                            <div class="progress-bar wrong-answers" style="width: <?= $total1Percentage ?>%">
                                <?= $wrongAnswers ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($answeredQuestions < $totalQuestions): ?>
                    <p class="test-advice">
                        <strong>Вы ответили не на все вопросы.</strong> 
                        Пройдите тест повторно для улучшения результата.
                    </p>
                <?php endif; ?>
                
                <a href="test3.php" class="btn-test">Пройти тест снова</a>
            <?php else: ?>
                <p>Вы еще не проходили этот тест.</p>
                <a href="test3.php" class="btn-test">Пройти тест</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>