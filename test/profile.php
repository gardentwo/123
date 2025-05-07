<?php
session_start();
// Подключение к базе данных
require_once 'vendor//connect.php';
if(!$_SESSION['user']){
    header('Location: index.php');
}
date_default_timezone_set('Europe/Moscow'); // UTC+3
unset($_SESSION['form_data']);

// Получаем результаты теста для текущего пользователя
$test1Results = [];
$test2Results = [];
$test3Results = [];
if (isset($_SESSION['user']['id'])) {
    $userId = $_SESSION['user']['id'];
    // test1
    $stmt = $db->prepare("SELECT answer_right, answer_wrong FROM test1 WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $test1Results = $result->fetch_assoc();
    $stmt->close();
    // test2
    $stmt = $db->prepare("SELECT answer_right, answer_wrong FROM test2 WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $test2Results = $result->fetch_assoc();
    $stmt->close();
    // test3
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
    <title>Профиль | Система тестирования</title>
    <link rel="stylesheet" href="assets//css//main.css">
    <link rel="stylesheet" href="assets//css//profile.css">
    
</head>
<body>
    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-avatar">
                <span><?php echo mb_substr($_SESSION['user']['full_name'], 0, 1); ?></span>
            </div>
            <h2 class="profile-name"><?php echo $_SESSION['user']['full_name']; ?></h2>
            <p class="profile-role"><?php echo $_SESSION['user']['role']; ?></p>
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-value"><?php echo $_SESSION['user']['full_name']; ?></span>
                    <span class="stat-label">ФИО</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo date('d.m.Y', strtotime($_SESSION['user']['created_at'] ?? 'now')); ?></span>
                    <span class="stat-label">Дата регистрации</span>
                </div>
            </div>
            <div class="profile-actions">
                <a href="reference.php" class="btn-reference">Справочные материалы</a>
                <a href="crossword.php" class="btn-reference">Кроссворд</a>
                <a href="vendor/logout.php" class="btn-logout">Выйти</a>
            </div>
        </div>

        <div class="profile-content">
        <div class="profile-section">
    <h3>Результаты теста "DNS и Сетевая Безопасность"</h3>
    <?php if (!empty($test1Results)): ?>
        <?php 
                    $totalQuestions = 12;
        $correctAnswers = $test1Results['answer_right'] ?? 0;
        $wrongAnswers = $test1Results['answer_wrong'] ?? 0;
        $answeredQuestions = $correctAnswers + $wrongAnswers;
        $correctPercentage = $answeredQuestions > 0 ? round(($correctAnswers / $answeredQuestions) * 100) : 0;
        $totalPercentage = round(($correctAnswers / $totalQuestions) * 100);
                    ?>
                    
                    <div class="test-results">
                        <div class="result-stats">
                            <div class="result-item">
                                <span class="result-value"><?php echo $correctAnswers; ?></span>
                                <span class="result-label">Правильных</span>
        </div>
                            <div class="result-item">
                                <span class="result-value"><?php echo $wrongAnswers; ?></span>
                                <span class="result-label">Неправильных</span>
        </div>
                            <div class="result-item">
                                <span class="result-value"><?php echo $answeredQuestions; ?>/<?php echo $totalQuestions; ?></span>
                                <span class="result-label">Всего ответов</span>
            </div>
        </div>
        
        <div class="progress-container">
                            <div class="progress-bar correct" style="width: <?php echo $totalPercentage; ?>%">
                                <span><?php echo $totalPercentage; ?>%</span>
            </div>
            <?php if ($wrongAnswers > 0): ?>
                                <div class="progress-bar wrong" style="width: <?php echo round(($wrongAnswers / $totalQuestions) * 100); ?>%">
                                    <span><?php echo round(($wrongAnswers / $totalQuestions) * 100); ?>%</span>
                </div>
            <?php endif; ?>
        </div>
        
                        <div class="test-advice">
                            <a href="test1.php" class="btn-white">Пройти тест заново</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-test">
                        <p>Вы еще не проходили тест</p>
                        <a href="test1.php" class="btn-white">Начать тест</a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="profile-section">
                <h3>Результаты теста "Основы Информационной Безопасности"</h3>
                <?php if (!empty($test2Results)): ?>
                    <?php 
                    $totalQuestions = 10;
                    $correctAnswers = $test2Results['answer_right'] ?? 0;
                    $wrongAnswers = $test2Results['answer_wrong'] ?? 0;
                    $answeredQuestions = $correctAnswers + $wrongAnswers;
                    $correctPercentage = $answeredQuestions > 0 ? round(($correctAnswers / $answeredQuestions) * 100) : 0;
                    $totalPercentage = round(($correctAnswers / $totalQuestions) * 100);
                    ?>
                    <div class="test-results">
                        <div class="result-stats">
                            <div class="result-item">
                                <span class="result-value"><?php echo $correctAnswers; ?></span>
                                <span class="result-label">Правильных</span>
                            </div>
                            <div class="result-item">
                                <span class="result-value"><?php echo $wrongAnswers; ?></span>
                                <span class="result-label">Неправильных</span>
                            </div>
                            <div class="result-item">
                                <span class="result-value"><?php echo $answeredQuestions; ?>/<?php echo $totalQuestions; ?></span>
                                <span class="result-label">Всего ответов</span>
                            </div>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar correct" style="width: <?php echo $totalPercentage; ?>%">
                                <span><?php echo $totalPercentage; ?>%</span>
                            </div>
                            <?php if ($wrongAnswers > 0): ?>
                                <div class="progress-bar wrong" style="width: <?php echo round(($wrongAnswers / $totalQuestions) * 100); ?>%">
                                    <span><?php echo round(($wrongAnswers / $totalQuestions) * 100); ?>%</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="test-advice">
                            <a href="test2.php" class="btn-white">Пройти тест заново</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-test">
                        <p>Вы еще не проходили тест</p>
                        <a href="test2.php" class="btn-white">Начать тест</a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="profile-section">
                <h3>Результаты теста "Социальная Инженерия"</h3>
                <?php if (!empty($test3Results)): ?>
                    <?php 
                    $totalQuestions = 10;
                    $correctAnswers = $test3Results['answer_right'] ?? 0;
                    $wrongAnswers = $test3Results['answer_wrong'] ?? 0;
                    $answeredQuestions = $correctAnswers + $wrongAnswers;
                    $correctPercentage = $answeredQuestions > 0 ? round(($correctAnswers / $answeredQuestions) * 100) : 0;
                    $totalPercentage = round(($correctAnswers / $totalQuestions) * 100);
                    ?>
                    <div class="test-results">
                        <div class="result-stats">
                            <div class="result-item">
                                <span class="result-value"><?php echo $correctAnswers; ?></span>
                                <span class="result-label">Правильных</span>
                            </div>
                            <div class="result-item">
                                <span class="result-value"><?php echo $wrongAnswers; ?></span>
                                <span class="result-label">Неправильных</span>
                            </div>
                            <div class="result-item">
                                <span class="result-value"><?php echo $answeredQuestions; ?>/<?php echo $totalQuestions; ?></span>
                                <span class="result-label">Всего ответов</span>
                            </div>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar correct" style="width: <?php echo $totalPercentage; ?>%">
                                <span><?php echo $totalPercentage; ?>%</span>
                            </div>
                            <?php if ($wrongAnswers > 0): ?>
                                <div class="progress-bar wrong" style="width: <?php echo round(($wrongAnswers / $totalQuestions) * 100); ?>%">
                                    <span><?php echo round(($wrongAnswers / $totalQuestions) * 100); ?>%</span>
                                </div>
            <?php endif; ?>
                        </div>
                        <div class="test-advice">
                            <a href="test3.php" class="btn-white">Пройти тест заново</a>
                        </div>
                    </div>
    <?php else: ?>
                    <div class="no-test">
                        <p>Вы еще не проходили тест</p>
                        <a href="test3.php" class="btn-white">Начать тест</a>
                    </div>
    <?php endif; ?>
            </div>
        </div>
</div>
</body>
</html>