
// Общие переменные
let userAnswers = [];
let testCompleted = false;
let questions = [];
let testName = '';

// Инициализация теста
function initTest() {
    const container = document.getElementById('questions-container');
    container.innerHTML = '';
    
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
            
            if (testCompleted) {
                answerDiv.style.pointerEvents = 'none';
            }
            
            if (userAnswers[index] === i) {
                answerDiv.classList.add('selected');
            }
            
            questionDiv.querySelector('.answers').appendChild(answerDiv);
        });
        
        container.appendChild(questionDiv);
    });

    if (testCompleted) {
        showResults();
    }
}

// Выбор ответа
function selectAnswer(element, questionIndex, answerIndex) {
    if (testCompleted) return;
    
    const questionDiv = element.parentElement.parentElement;
    questionDiv.querySelectorAll('.answer').forEach(a => {
        a.classList.remove('selected');
    });
    element.classList.add('selected');
    userAnswers[questionIndex] = answerIndex;
}

// Проверка ответов
function checkAnswers() {
    let correctCount = 0;
    let wrongCount = 0;

    document.querySelectorAll('.answer').forEach(a => {
        a.style.pointerEvents = 'none';
    });

    questions.forEach((q, index) => {
        const answers = document.querySelectorAll(`#questions-container > div:nth-child(${index + 1}) .answer`);
        
        if (userAnswers[index] !== -1) {
            if (userAnswers[index] === q.correct) {
                answers[userAnswers[index]].classList.add('correct');
                correctCount++;
            } else {
                answers[userAnswers[index]].classList.add('wrong');
                answers[q.correct].classList.add('correct');
                wrongCount++;
            }
        }
    });

    testCompleted = true;
    showResults(correctCount);
    saveResults(correctCount, wrongCount);
}

// Сохранение результатов
function saveResults(correctCount, wrongCount) {
    const formData = new FormData();
    formData.append('correct_answers', correctCount);
    formData.append('wrong_answers', wrongCount);
    formData.append('test', testName);
    
    fetch('vendor/save_results.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Ошибка при сохранении результатов:', data.error);
        }
    })
    .catch(error => console.error('Ошибка:', error));
}

// Показать результаты
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

// Сброс теста
function resetTest() {
    userAnswers = new Array(questions.length).fill(-1);
    testCompleted = false;
    document.getElementById('results').innerHTML = '';
    initTest();
}

// Инициализация при загрузке
document.addEventListener('DOMContentLoaded', () => {
    userAnswers = new Array(questions.length).fill(-1);
    initTest();
});