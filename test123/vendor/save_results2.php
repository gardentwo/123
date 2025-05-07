<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'error' => 'Пользователь не авторизован']);
    exit;
}

// Подключение к базе данных (замените параметры на свои)
$db = new mysqli('localhost', 'root', 'root', 'test');
if ($db->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Ошибка подключения к базе данных']);
    exit;
}

// Получаем данные из запроса
$correctAnswers = isset($_POST['correct_answers1']) ? (int)$_POST['correct_answers1'] : 0;
$wrongAnswers = isset($_POST['wrong_answers1']) ? (int)$_POST['wrong_answers1'] : 0;
$userId = $_SESSION['user']['id'];

// Проверяем существование записи для пользователя
$checkQuery = $db->prepare("SELECT id FROM test2 WHERE user_id = ?");
$checkQuery->bind_param("i", $userId);
$checkQuery->execute();
$result = $checkQuery->get_result();

if ($result->num_rows > 0) {
    // Обновляем существующую запись
    $stmt = $db->prepare("UPDATE test2 SET answer_right = ?, answer_wrong = ? WHERE user_id = ?");
    $stmt->bind_param("iii", $correctAnswers, $wrongAnswers, $userId);
} else {
    // Создаем новую запись
    $stmt = $db->prepare("INSERT INTO test2 (user_id, answer_right, answer_wrong) VALUES (?, ?, ?)");
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