<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тест 3: Социальная Инженерия</title>
    <link rel="stylesheet" href="assets//maintest.css">
</head>
    <div class="test-container">
        <h1>Тест 3: Социальная Инженерия</h1>
        
        <div id="questions-container"></div>
        
        <button onclick="checkAnswers()">Проверить ответы</button>
        <div id="results" class="results"></div>
    </div>

    <script>
        const questions = [
            {
                question: "1. Что такое социальная инженерия?",
                answers: [
                    "Использование технических уязвимостей",
                    "Манипуляция людьми для получения информации",
                    "Создание вредоносного ПО",
                    "Анализ сетевого трафика"
                ],
                correct: 1
            },
            {
                question: "2. Пример социальной инженерии?",
                answers: [
                    "Фишинг",
                    "Брутфорс атака",
                    "SQL-инъекция",
                    "DDoS-атака"
                ],
                correct: 0
            },
            {
                question: "3. Защита от социальной инженерии?",
                answers: [
                    "Антивирусное ПО",
                    "Обновление ПО",
                    "Обучение сотрудников",
                    "Брандмауэр"
                ],
                correct: 2
            },
            {
                question: "4. Что такое «фишинг»?",
                answers: [
                    "Взлом Wi-Fi",
                    "Поддельные письма для кражи данных",
                    "Перехват данных сниффером",
                    "Атака ботнетами"
                ],
                correct: 1
            },
            {
                question: "5. Признак фишинговой атаки?",
                answers: [
                    "Письмо с просьбой предоставить данные",
                    "Обновление ОС",
                    "Установка антивируса",
                    "Резервное копирование"
                ],
                correct: 0
            },
            {
                question: "6. Что такое «претекстинг»?",
                answers: [
                    "Отправка вредоносных SMS",
                    "Звонок для получения информации",
                    "Взлом соцсетей",
                    "Поддельные сайты"
                ],
                correct: 1
            },
            {
                question: "7. Защита от претекстинга?",
                answers: [
                    "Двухфакторная аутентификация",
                    "Проверка отправителя SMS",
                    "Антивирус на телефоне",
                    "Все перечисленное"
                ],
                correct: 3
            },
            {
                question: "8. Что такое «кви про кво»?",
                answers: [
                    "Представление сотрудником компании",
                    "SQL-инъекции",
                    "Атака ботнетами",
                    "Перехват данных"
                ],
                correct: 0
            },
            {
                question: "9. Защита от «кви про кво»?",
                answers: [
                    "Проверка личности звонящего",
                    "Контроль доступа",
                    "Обучение сотрудников",
                    "Все варианты"
                ],
                correct: 3
            },
            {
                question: "10. Что такое «бейтинг»?",
                answers: [
                    "USB-накопитель с вредоносным ПО",
                    "Взлом Wi-Fi",
                    "Спам-письма",
                    "DDoS-атаки"
                ],
                correct: 0
            }
        ];

        let userAnswers = new Array(questions.length).fill(-1);
        let testCompleted = false;

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
                    questionDiv.querySelector('.answers').appendChild(answerDiv);
                });
 
                container.appendChild(questionDiv);
            });
        }

        function selectAnswer(element, questionIndex, answerIndex) {
            if (testCompleted) return;
            const questionDiv = element.parentElement.parentElement;
            questionDiv.querySelectorAll('.answer').forEach(a => {
                a.classList.remove('selected');
            });
            element.classList.add('selected');
            userAnswers[questionIndex] = answerIndex;
        }

        function checkAnswers() {
            let correctCount = 0;
            let wrongCount = 0;
    
            // Блокируем возможность выбора ответов после завершения теста
            document.querySelectorAll('.answer').forEach(a => {
                a.style.pointerEvents = 'none';
            });

            // Подсчитываем результаты и подсвечиваем только отвеченные вопросы
            questions.forEach((q, index) => {
                const answers = document.querySelectorAll(`#questions-container > div:nth-child(${index + 1}) .answer`);
                
                // Если пользователь ответил на вопрос
                if (userAnswers[index] !== -1) {
                    // Если ответ правильный
                    if (userAnswers[index] === q.correct) {
                        answers[userAnswers[index]].classList.add('correct');
                        correctCount++;
                    } 
                    // Если ответ неправильный
                    else {
                        answers[userAnswers[index]].classList.add('wrong'); // Помечаем неправильный ответ красным
                        answers[q.correct].classList.add('correct'); // Показываем правильный ответ зеленым
                        wrongCount++;
                    }
                }
                // Если вопрос остался без ответа, ничего не подсвечиваем
            });

            testCompleted = true;
            showResults(correctCount);
            saveResults(correctCount, wrongCount);
        }

        function saveResults(correctCount, wrongCount) {
            const formData = new FormData();
            formData.append('correct_answers', correctCount);
            formData.append('wrong_answers', wrongCount);
            formData.append('test', 'test3');
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
        <button class="btn-white" onclick="resetTest()">Пройти тест заново</button>
        <a href="profile.php" class="btn-home">Домой</a>
    `;
}

        function resetTest() {
            userAnswers = new Array(questions.length).fill(-1);
            testCompleted = false;
            document.getElementById('results').innerHTML = '';
            document.getElementById('questions-container').innerHTML = '';
            initTest();
        }

        initTest();
    </script>
 
</body>
</html>