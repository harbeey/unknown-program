document.addEventListener("DOMContentLoaded", function() {
    const quizContainer = document.getElementById('quiz');
    const resultsContainer = document.getElementById('results');
    const questionReviewContainer = document.getElementById('question-review');
    const submitButton = document.getElementById('submit');
    const nextButton = document.getElementById('next');
    const tryAgainButton = document.getElementById('try-again');
    const backToMenuButton = document.getElementById('back-to-menu');

    let currentSlide = 0;
    let quizQuestions = [];

    function fetchQuestions() {
        const urlParams = new URLSearchParams(window.location.search);
        const category = urlParams.get('category');
        const difficulty = urlParams.get('difficulty');
        const amount = urlParams.get('amount');

        fetch(`https://opentdb.com/api.php?amount=${amount}&category=${category}&difficulty=${difficulty}&type=multiple`)
            .then(response => response.json())
            .then(data => setupQuiz(data.results))
            .catch(error => console.error('Error fetching questions:', error));
    }

    function setupQuiz(questions) {
        quizContainer.innerHTML = '';
        quizQuestions = questions;

        questions.forEach((question, questionNumber) => {
            const answers = [];
            const correct = question.correct_answer;
            const incorrect = question.incorrect_answers;
            const allAnswers = incorrect.concat(correct).sort(() => Math.random() - 0.5);

            allAnswers.forEach(answer => {
                answers.push(
                    `<label class="answer">
                        <input type="radio" name="question${questionNumber}" value="${answer}">
                        <span class="custom-radio"></span>
                        ${answer}
                    </label>`
                );
            });

            quizContainer.innerHTML += `
                <div class="slide">
                    <div class="question">${question.question}</div>
                    <div class="answers">${answers.join('')}</div>
                </div>
            `;
        });

        showSlide(0);
        nextButton.style.display = 'inline-block';
    }

    function showSlide(n) {
        const slides = document.querySelectorAll(".slide");
        slides.forEach(slide => slide.style.display = 'none');
        slides[n].style.display = 'block';
        currentSlide = n;

        nextButton.style.display = currentSlide < slides.length - 1 ? 'inline-block' : 'none';
        submitButton.style.display = currentSlide === slides.length - 1 ? 'inline-block' : 'none';
    }

    function showNextSlide() {
        showSlide(currentSlide + 1);
    }

    function showResults() {
        const answerContainers = quizContainer.querySelectorAll('.answers');
        let numCorrect = 0;

        quizQuestions.forEach((question, questionNumber) => {
            const answerContainer = answerContainers[questionNumber];
            const selector = `input[name=question${questionNumber}]:checked`;
            const userAnswer = (answerContainer.querySelector(selector) || {}).value;

            if (userAnswer === question.correct_answer) {
                numCorrect++;
                answerContainers[questionNumber].style.color = 'green';
            } else {
                answerContainers[questionNumber].style.color = 'red';
            }
        });

        resultsContainer.innerHTML = `You got ${numCorrect} out of ${quizQuestions.length} correct.`;
        resultsContainer.style.display = 'block';

        displayQuestionReview();

        submitButton.style.display = 'none';
        nextButton.style.display = 'none';
        tryAgainButton.style.display = 'inline-block';
        backToMenuButton.style.display = 'inline-block';
    }

    function displayQuestionReview() {
        let reviewHTML = '<h2>Question Review</h2>';
        quizQuestions.forEach((question, questionNumber) => {
            reviewHTML += `
                <div class="review-question">
                    <h3>Question ${questionNumber + 1}</h3>
                    <p>${question.question}</p>
                    <p><strong>Correct Answer:</strong> ${question.correct_answer}</p>
                </div>
            `;
        });
        questionReviewContainer.innerHTML = reviewHTML;
        questionReviewContainer.style.display = 'block';
    }

    function tryAgain() {
        resultsContainer.style.display = 'none';
        questionReviewContainer.style.display = 'none';
        tryAgainButton.style.display = 'none';
        backToMenuButton.style.display = 'none';
        setupQuiz(quizQuestions);
    }

    function backToMenu() {
        window.location.href = 'menu.html';
    }

    submitButton.addEventListener('click', showResults);
    nextButton.addEventListener('click', showNextSlide);
    tryAgainButton.addEventListener('click', tryAgain);
    backToMenuButton.addEventListener('click', backToMenu);

    fetchQuestions();
});
