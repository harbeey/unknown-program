document.addEventListener("DOMContentLoaded", function() {
    const rankingsTableBody = document.querySelector('#rankings-table tbody');
    const backToMenuButton = document.getElementById('back-to-menu');

    fetch('get_rankings.php')
        .then(response => response.json())
        .then(data => {
            rankingsTableBody.innerHTML = '';
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><i class="fa-regular fa-circle-user"></i> ${row.username}</td>
                `;
                rankingsTableBody.appendChild(tr);
            });
        })
        .catch(error => console.error('Error fetching rankings:', error));

    backToMenuButton.addEventListener('click', function() {
        window.location.href = 'menu.html';
    });
});


