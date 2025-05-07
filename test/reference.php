<?php
session_start();
if(!$_SESSION['user']){
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Социальная инженерия | Система тестирования</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/reference.css">
</head>
<body>
    <div class="top-nav-fixed">
        <div class="nav-container">
            <a href="profile.php" class="nav-link">Профиль</a>
            <a href="vendor/logout.php" class="nav-link">Выход</a>
        </div>
    </div>
    <div class="reference-center">
        <div class="reference-header">
            <h1>Социальная инженерия</h1>
            <p>Методы защиты от манипуляций и психологического воздействия в цифровой среде.</p>
        </div>
        <div class="reference-grid">
            <div class="reference-card">
                <div class="reference-icon">🎭</div>
                <h4>Основы социальной инженерии</h4>
                <p>Понятия, принципы, методы воздействия и психологические основы.</p>
                <a href="#" class="btn-reference">Подробнее</a>
            </div>
            <div class="reference-card">
                <div class="reference-icon">📧</div>
                <h4>Фишинг и его разновидности</h4>
                <p>Email-фишинг, spear phishing, smishing, vishing и методы защиты.</p>
                <a href="#" class="btn-reference">Подробнее</a>
            </div>
            <div class="reference-card">
                <div class="reference-icon">👥</div>
                <h4>Претекстинг</h4>
                <p>Создание ложных сценариев для получения информации, примеры и защита.</p>
                <a href="#" class="btn-reference">Подробнее</a>
            </div>
            <div class="reference-card">
                <div class="reference-icon">🏢</div>
                <h4>Инсайдерские угрозы</h4>
                <p>Методы вербовки инсайдеров, профилактика и выявление угроз.</p>
                <a href="#" class="btn-reference">Подробнее</a>
            </div>
            <div class="reference-card">
                <div class="reference-icon">💻</div>
                <h4>Техники защиты</h4>
                <p>Обучение сотрудников, политики безопасности и технические меры.</p>
                <a href="#" class="btn-reference">Подробнее</a>
            </div>
            <div class="reference-card">
                <div class="reference-icon">🔍</div>
                <h4>Реальные кейсы</h4>
                <p>Разбор известных атак, методы их реализации и уроки.</p>
                <a href="#" class="btn-reference">Подробнее</a>
            </div>
        </div>
    </div>
</body>
</html>