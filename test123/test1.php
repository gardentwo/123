<?php 
session_start();
if(!isset($_SESSION['user'])){
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тест: DNS и Сетевая Безопасность</title>
    <link rel="stylesheet" href="assets//main_test.css">
</head>
<body>
    <div class="test-container">
        <h1>Тест: DNS и Сетевая Безопасность</h1>
        
        <div id="questions-container"></div>
        
        <button onclick="checkAnswers()">Проверить ответы</button>
        <div id="results" class="results"></div>
    </div>

    <script>
        const questions = [
            {
                question: "1. Как злоумышленники могут использовать DNS-спуфинг для кражи данных?",
                answers: [
                    "Перенаправление трафика на поддельный сайт",
                    "Обнаружение уязвимостей в веб-браузере",
                    "Установка вредоносного ПО",
                    "Перехват звонков через VoIP"
                ],
                correct: 0
            },
            {
                question: "2. Какая атака позволяет злоумышленникам считывать данные из кэшированных файлов на устройстве пользователя?",
                answers: [
                    "Атака по сторонним каналам (Side-Channel Attack)",
                    "MITM (Man-in-the-Middle) атака",
                    "SQL-инъекция",
                    "Атака 'Фишинг'"
                ],
                correct: 0
            },
            {
                question: "3. Что из перечисленного наиболее подходит для защиты от утечек через технические каналы связи?",
                answers: [
                    "Запрет использования мобильных устройств на работе",
                    "Имплементация Zero Trust архитектуры",
                    "Использование только локальных сетей",
                    "Установка антивируса"
                ],
                correct: 1
            },
            {
                question: "4. Как злоумышленники могут использовать протокол SMB (Server Message Block) для утечки данных?",
                answers: [
                    "Распространение вредоносного кода через уязвимости в протоколе",
                    "Создание защищенного соединения",
                    "Уменьшение скорости передачи данных",
                    "Установка временных файлов"
                ],
                correct: 0
            },
            {
                question: "5. Какие методы защиты особенно важны при использовании облачных сервисов?",
                answers: [
                    "Только сложные пароли",
                    "Управление доступом и мониторинг аномальной активности",
                    "Отключение всех удаленных подключений",
                    "Ежедневное обновление учетных записей"
                ],
                correct: 1
            },
            {
                question: "6. Как может осуществляться утечка данных через метаданные, содержащиеся в файлах?",
                answers: [
                    "Из-за отсутствия резервного копирования",
                    "Через несанкционированный доступ к серверу хранения",
                    "Путем анализа скрытой информации о владельце и создании файла",
                    "Через установку обновлений ПО"
                ],
                correct: 2
            },
            {
                question: "7. Какой из перечисленных инструментов может быть использован для сниффинга трафика?",
                answers: [
                    "Wireshark",
                    "Zoom",
                    "Google Drive",
                    "Microsoft Teams"
                ],
                correct: 0
            },
            {
                question: "8. Как злоумышленники могут использовать IoT-устройства в цепочке утечки данных?",
                answers: [
                    "Упрощение доступов к корпоративным сетям через IoT уязвимости",
                    "Повышение производительности сети",
                    "Сокрытие информации",
                    "Оптимизация ресурсов сети"
                ],
                correct: 0
            },
            {
                question: "9. Что такое 'exfiltration over alternative protocol'?",
                answers: [
                    "Использование нестандартных методов для извлечения данных",
                    "Автоматизация передачи данных",
                    "Шифрование данных для предотвращения утечек",
                    "Установка пароля на файл"
                ],
                correct: 0
            },
            {
                question: "10. В каких случаях атака на радиочастотное излучение может быть использована для утечки данных?",
                answers: [
                    "Если устройство находится в защищенном помещении",
                    "Если устройство имеет возможность излучать информацию через электромагнитные сигналы",
                    "Только при подключении к публичному Wi-Fi",
                    "Никогда"
                ],
                correct: 1
            },
            {
                question: "11. Какая уязвимость Bluetooth-соединений особенно опасна?",
                answers: [
                    "Brute Force атаки",
                    "BlueBorne уязвимость, позволяющая получить доступ без авторизации",
                    "Использование только одного устройства",
                    "Отсутствие видимости устройств"
                ],
                correct: 1
            },
            {
                question: "12. Как злоумышленники могут использовать скрытые каналы связи (covert channels) для утечки данных?",
                answers: [
                    "Через стандартные интернет-каналы",
                    "Через неиспользуемые части протоколов или нестандартные пути передачи данных",
                    "Через локальное отключение сети",
                    "Путем удаления файлов"
                ],
                correct: 1
            }
        ];

        // Загружаем сохраненные ответы из localStorage или создаем новый массив
        let userAnswers = JSON.parse(localStorage.getItem('userAnswers')) || new Array(questions.length).fill(-1);
        let testCompleted = localStorage.getItem('testCompleted') === 'true';

        function initTest() {
            const container = document.getElementById('questions-container');
            
            questions.forEach((q, index) => {
                const questionDiv = document.createElement('div');
                questionDiv.className = 'question';
                questionDiv.innerHTML = `
                    <h3>${q.question}</h3>
                    <div class="answers"></div>
                `;
                
                q.answers.forEach((answer, i) => {
                    const answerDiv = document.createElement('div');
                    answerDiv.className = 'answer';
                    answerDiv.textContent = answer;
                    answerDiv.onclick = () => selectAnswer(answerDiv, index, i);
                    
                    // Если тест завершен, блокируем выбор ответов
                    if (testCompleted) {
                        answerDiv.style.pointerEvents = 'none';
                    }
                    
                    // Если ответ был выбран ранее, выделяем его
                    if (userAnswers[index] === i) {
                        answerDiv.classList.add('selected');
                    }
                    
                    questionDiv.querySelector('.answers').appendChild(answerDiv);
                });
                
                container.appendChild(questionDiv);
            });

            // Если тест был завершен, показываем результаты
            if (testCompleted) {
                showResults();
            }
        }

        function selectAnswer(element, questionIndex, answerIndex) {
            if (testCompleted) return;
            
            const questionDiv = element.parentElement.parentElement;
            questionDiv.querySelectorAll('.answer').forEach(a => {
                a.classList.remove('selected');
            });
            element.classList.add('selected');
            userAnswers[questionIndex] = answerIndex;
            
            // Сохраняем выбранные ответы
            localStorage.setItem('userAnswers', JSON.stringify(userAnswers));
        }

        function checkAnswers() {
    let correctCount = 0;
    let wrongCount = 0;
    
    document.querySelectorAll('.answer').forEach(a => {
        a.classList.remove('correct', 'wrong');
        a.style.pointerEvents = 'none'; // Блокируем выбор ответов после проверки
    });

    questions.forEach((q, index) => {
        const answers = document.querySelectorAll(`#questions-container > div:nth-child(${index + 1}) .answer`);
        if (userAnswers[index] === q.correct) {
            answers[userAnswers[index]].classList.add('correct');
            correctCount++;
        } else {
            if (userAnswers[index] !== -1) {
                answers[userAnswers[index]].classList.add('wrong');
                wrongCount++;
            }
            answers[q.correct].classList.add('correct');
        }
    });

    // Помечаем тест как завершенный и сохраняем
    testCompleted = true;
    localStorage.setItem('testCompleted', 'true');
    
    showResults(correctCount);
    
    // Отправляем результаты на сервер
    saveResults(correctCount, wrongCount);
}
//vendor/logout.php
function saveResults(correctCount, wrongCount) {
    const formData = new FormData();
    formData.append('correct_answers1', correctCount);
    formData.append('wrong_answers1', wrongCount);
    fetch('vendor/save_results.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Результаты успешно сохранены');
        } else {
            console.error('Ошибка при сохранении результатов:', data.error);
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
    });
}
        function showResults(correctCount = null) {
            if (correctCount === null) {
                correctCount = questions.reduce((count, q, index) => {
                    return count + (userAnswers[index] === q.correct ? 1 : 0);
                }, 0);
            }
            
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = `
                <strong>Результат: ${correctCount} из ${questions.length}</strong>
                <p>Правильные ответы выделены зеленым, ваши неправильные выборы - красным</p>
                <button onclick="resetTest()">Пройти тест заново</button>
            `;
        }
        function resetTest() {
            localStorage.removeItem('userAnswers');
            localStorage.removeItem('testCompleted');
            userAnswers = new Array(questions.length).fill(-1);
            testCompleted = false;
            location.reload();
        }
        initTest();
    </script>
</body>
</html>