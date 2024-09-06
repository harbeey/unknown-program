document.addEventListener("DOMContentLoaded", function() {
    const categorySelect = document.getElementById('category');
    const difficultySelect = document.getElementById('difficulty');
    const quizAmountInput = document.getElementById('quiz-amount');
    const startQuizButton = document.getElementById('start-quiz');
    const rankButton = document.getElementById('rank-button');

    // Fetch categories and populate the category dropdown
    fetch('https://opentdb.com/api_category.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => loadCategories(data.trivia_categories))
        .catch(error => {
            console.error('Error fetching categories:', error);
            categorySelect.innerHTML = '<option value="">Failed to load categories</option>';
        });

    // Function to load categories into the dropdown
    function loadCategories(categories) {
        categorySelect.innerHTML = '';
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    }

    // Event listener for the "Start Quiz" button
    startQuizButton.addEventListener('click', function() {
        const category = categorySelect.value;
        const difficulty = difficultySelect.value.toLowerCase();
        const quizAmount = quizAmountInput.value;
        // Redirect to quiz.html with selected options as URL parameters
        window.location.href = `quiz.html?category=${category}&difficulty=${difficulty}&amount=${quizAmount}`;
    });

    // Event listener for the "View Rankings" button
    rankButton.addEventListener('click', function() {
        window.location.href = "rankings.html";
    });
});
