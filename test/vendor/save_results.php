<?php
session_start();
// Подключение к базе данных
require_once 'connect.php';
// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'error' => 'Пользователь не авторизован']);
    exit;
}
// Получаем данные из запроса
$correctAnswers = isset($_POST['correct_answers']) ? (int)$_POST['correct_answers'] : 0;
$wrongAnswers = isset($_POST['wrong_answers']) ? (int)$_POST['wrong_answers'] : 0;
$userId = $_SESSION['user']['id'];
$test = isset($_POST['test']) ? $_POST['test'] : 'test1';

$allowedTables = ['test1', 'test2', 'test3'];
if (!in_array($test, $allowedTables)) {
    echo json_encode(['success' => false, 'error' => 'Некорректный тест']);
    exit;
}

// Проверяем существование записи для пользователя
$checkQuery = $db->prepare("SELECT id FROM $test WHERE user_id = ?");
$checkQuery->bind_param("i", $userId);
$checkQuery->execute();
$result = $checkQuery->get_result();

if ($result->num_rows > 0) {
    // Обновляем существующую запись
    $stmt = $db->prepare("UPDATE $test SET answer_right = ?, answer_wrong = ? WHERE user_id = ?");
    $stmt->bind_param("iii", $correctAnswers, $wrongAnswers, $userId);
} else {
    // Создаем новую запись
    $stmt = $db->prepare("INSERT INTO $test (user_id, answer_right, answer_wrong) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userId, $correctAnswers, $wrongAnswers);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $db->error]);
}

$checkQuery->close();
$stmt->close();
$db->close();
?>