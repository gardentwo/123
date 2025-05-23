<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тест 2: Основы Информационной Безопасности</title>
    <link rel="stylesheet" href="assets//main_test.css">
 
</head>


    <div class="test-container">
        <h1>Тест 2: Основы Информационной Безопасности</h1>
        
        <div id="questions-container"></div>
        
        <button onclick="checkAnswers()">Проверить ответы</button>
        <div id="results" class="results"></div>
    </div>

    <script>
        const questions = [
            {
                question: "1. Что такое двухфакторная аутентификация?",
                answers: [
                    "Использование двух разных паролей",
                    "Аутентификация с помощью отпечатка пальца",
                    "Процесс проверки личности с использованием двух различных методов",
                    "Использование двух различных учетных записей"
                ],
                correct: 2
            },
            {
                question: "2. Что представляет собой атака Man-in-the-Middle (MitM)?",
                answers: [
                    "Атака с внедрением вредоносного кода",
                    "Перехват и изменение данных между сторонами",
                    "Наводнение системы ложными запросами",
                    "Атака через SQL-запросы"
                ],
                correct: 1
            },
            {
                question: "3. Что такое «фишинг»?",
                answers: [
                    "Подслушивание сетевого трафика",
                    "Защита от вредоносных программ",
                    "Обман людей для получения данных",
                    "Уничтожение компьютерных систем"
                ],
                correct: 2
            },
            {
                question: "4. Что такое межсетевой экран (firewall)?",
                answers: [
                    "Программа для удаленного управления",
                    "Система обнаружения вторжений",
                    "Анализатор сетевого трафика",
                    "Устройство для фильтрации трафика"
                ],
                correct: 3
            },
            {
                question: "5. Что НЕ является подходом к доверию?",
                answers: [
                    "Доверяйте только уполномоченным лицам",
                    "Постоянно доверяйте всем",
                    "Временно доверяйте некоторым",
                    "Постоянно доверяйте всем людям"
                ],
                correct: 1
            },
            {
                question: "6. Отношение персонала поддержки к безопасности?",
                answers: [
                    "Хотят работать без строгого контроля",
                    "Беспокоятся о простоте управления",
                    "Переживают о стоимости защиты",
                    "Хотят управлять реакцией пользователей"
                ],
                correct: 0
            },
            {
                question: "7. Набор предложений для реализации?",
                answers: [
                    "Стандартный",
                    "Код",
                    "Политика",
                    "Руководящие принципы"
                ],
                correct: 2
            },
            {
                question: "8. Уровни социальной инженерии?",
                answers: [
                    "Макро, мезо, микро",
                    "Высокий, средний, низкий",
                    "Организованный, смешанный",
                    "Формальный, неформальный"
                ],
                correct: 0
            },
            {
                question: "9. Что такое социальная инженерия?",
                answers: [
                    "Прикладная социология",
                    "Практическая психология",
                    "Междисциплинарные подходы",
                    "Принципиально новые подходы"
                ],
                correct: 2
            },
            {
                question: "10. Что НЕ должна делать политика безопасности?",
                answers: [
                    "Указывать причины необходимости",
                    "Внедрять и обеспечивать соблюдение",
                    "Быть краткой и понятной",
                    "Балансировать защиту и производительность"
                ],
                correct: 3
            }
        ];

        let userAnswers = new Array(questions.length).fill(-1);

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
            document.querySelectorAll('.answer').forEach(a => {
                a.classList.remove('correct', 'wrong');
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
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = `
                <strong>Результат: ${correctCount} из ${questions.length}</strong>
                <p>Правильные ответы выделены зеленым, ваши неправильные выборы - красным</p>
            `;
            saveResults(correctCount, wrongCount);
        }

        function saveResults(correctCount, wrongCount) {
    const formData = new FormData();
    formData.append('correct_answers1', correctCount);
    formData.append('wrong_answers1', wrongCount);
    fetch('vendor/save_results2.php', {
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

        initTest();
    </script>
</body>
</html>