document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const searchResults = document.getElementById('searchResults');

    function performSearch() {
        const searchTerm = searchInput.value.trim();
        if (searchTerm === '') return;

        // Redirect to search results page with the search term
        window.location.href = `search-results.html?q=${encodeURIComponent(searchTerm)}`;
    }

    function displayResults(data) {
        let html = '<div class="search-results-container">';
        
        if (data.heritage_places.length > 0) {
            html += '<section class="heritage-results">';
            html += '<h3>Heritage Places</h3>';
            html += '<div class="results-grid">';
            data.heritage_places.forEach(place => {
                html += `
                    <div class="result-card">
                         <h4>${place.place_name}</h4>
                        <p>${place.description}</p>
                    </div>
                `;
            });
            html += '</div></section>';
        }

        if (data.culture.length > 0) {
            html += '<section class="culture-results">';
            html += '<h3>Cultural Aspects</h3>';
            html += '<div class="results-grid">';
            data.culture.forEach(item => {
                html += `
                    <div class="result-card">
                        <h4>${item.title}</h4>
                        <p>${item.description}</p>
                        <span class="type">Type: ${item.type}</span>
                    </div>
                `;
            });
            html += '</div></section>';
        }

        if (data.traditions.length > 0) {
            html += '<section class="traditions-results">';
            html += '<h3>Traditions</h3>';
            html += '<div class="results-grid">';
            data.traditions.forEach(tradition => {
                html += `
                    <div class="result-card">
                        <h4>${tradition.title}</h4>
                        <p>${tradition.description}</p>
                        <span class="category">Category: ${tradition.category}</span>
                    </div>
                `;
            });
            html += '</div></section>';
        }

        if (data.heritage_places.length === 0 && data.culture.length === 0 && data.traditions.length === 0) {
            html = '<p class="no-results">No results found for your search.</p>';
        }

        html += '</div>';
        searchResults.innerHTML = html;
    }

    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
}); 