document.addEventListener('DOMContentLoaded', function() {
    console.log('search.js loaded');

    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const resultsContainer = document.getElementById('resultsContainer');
    const loadingIndicator = document.getElementById('loading');

    // Log if elements are found
    console.log('Elements found:', {
        searchInput: !!searchInput,
        searchButton: !!searchButton,
        resultsContainer: !!resultsContainer,
        loadingIndicator: !!loadingIndicator
    });

    function performSearch() {
        const searchTerm = searchInput.value.trim();
        console.log('Performing search for:', searchTerm);
        
        if (!searchTerm) {
            showMessage('Please enter a search term', 'error');
            return;
        }

        // Show loading state
        loadingIndicator.style.display = 'block';
        clearResults();

        // Make AJAX request
        const xhr = new XMLHttpRequest();
        const url = `search.php?search=${encodeURIComponent(searchTerm)}`;
        console.log('Request URL:', url);
        
        xhr.open('GET', url, true);
        xhr.onload = function() {
            console.log('Response status:', xhr.status);
            console.log('Response text:', xhr.responseText);
            
            loadingIndicator.style.display = 'none';
            
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    console.log('Parsed response:', response);
                    
                    if (response.error) {
                        showMessage(response.error, 'error');
                        return;
                    }

                    // Check if any results were found
                    const totalResults = response.heritage_places.length + 
                                       response.culture.length + 
                                       response.traditions.length;
                    console.log('Total results found:', totalResults);

                    if (totalResults === 0) {
                        showMessage('No results found', 'info');
                        return;
                    }

                    // Update search term display
                    document.getElementById('search-term').textContent = `Search Results for "${searchTerm}"`;

                    // Display results
                    if (response.heritage_places.length > 0) {
                        console.log('Rendering heritage results:', response.heritage_places);
                        renderHeritageResults(response.heritage_places);
                    }
                    if (response.culture.length > 0) {
                        console.log('Rendering culture results:', response.culture);
                        renderCultureResults(response.culture);
                    }
                    if (response.traditions.length > 0) {
                        console.log('Rendering tradition results:', response.traditions);
                        renderTraditionResults(response.traditions);
                    }

                    // Update search stats
                    updateSearchStats(response);

                } catch (e) {
                    console.error('Error parsing response:', e);
                    showMessage('Error parsing search results', 'error');
                }
            } else {
                console.error('Request failed with status:', xhr.status);
                showMessage('Error performing search', 'error');
            }
        };
        xhr.onerror = function(e) {
            console.error('Network error occurred:', e);
            loadingIndicator.style.display = 'none';
            showMessage('Network error occurred', 'error');
        };
        xhr.send();
    }

    function showMessage(message, type = 'info') {
        console.log('Showing message:', message, 'type:', type);
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}`;
        messageDiv.textContent = message;
        clearResults();
        resultsContainer.appendChild(messageDiv);
    }

    function clearResults() {
        // Clear previous results
        document.getElementById('heritage-grid').innerHTML = '';
        document.getElementById('culture-grid').innerHTML = '';
        document.getElementById('traditions-grid').innerHTML = '';
        document.getElementById('search-stats').innerHTML = '';
    }

    function updateSearchStats(data) {
        const totalResults = data.heritage_places.length + data.culture.length + data.traditions.length;
        const statsDiv = document.getElementById('search-stats');
        statsDiv.textContent = `Found ${totalResults} results (${data.heritage_places.length} heritage places, ${data.culture.length} cultural aspects, ${data.traditions.length} traditions)`;
    }

    function renderHeritageResults(places) {
        const grid = document.getElementById('heritage-grid');
        document.getElementById('heritage-results').style.display = 'block';
        
        places.forEach(place => {
            const card = document.createElement('div');
            card.className = 'result-card';
            const imageUrl = place.image_url && place.image_url.trim() !== '' ? place.image_url : 'images/default-place.jpg';
            card.innerHTML = `
                <img src="${imageUrl}" alt="${place.place_name}" class="result-image" onerror="this.src='images/default-place.jpg'">
                <div class="result-content">
                    <h3>${place.place_name}</h3>
                    <p class="state">${place.state_name}</p>
                    <p>${place.description}</p>
                    <a href="heritage-details.php?id=${place.place_id}" class="btn">View Details</a>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    function renderCultureResults(items) {
        const grid = document.getElementById('culture-grid');
        document.getElementById('culture-results').style.display = 'block';
        
        items.forEach(item => {
            const card = document.createElement('div');
            card.className = 'result-card';
            const imageUrl = item.image_url && item.image_url.trim() !== '' ? item.image_url : 'images/default-culture.jpg';
            card.innerHTML = `
                <img src="${imageUrl}" alt="${item.title}" class="result-image" onerror="this.src='images/default-culture.jpg'">
                <div class="result-content">
                    <h3>${item.title}</h3>
                    <p class="state">${item.state_name}</p>
                    <p>${item.description}</p>
                    <a href="culture-details.php?id=${item.culture_id}" class="btn">View Details</a>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    function renderTraditionResults(traditions) {
        const grid = document.getElementById('traditions-grid');
        document.getElementById('traditions-results').style.display = 'block';
        
        traditions.forEach(tradition => {
            const card = document.createElement('div');
            card.className = 'result-card';
            const imageUrl = tradition.image_url && tradition.image_url.trim() !== '' ? tradition.image_url : 'images/default-tradition.jpg';
            card.innerHTML = `
                <img src="${imageUrl}" alt="${tradition.title}" class="result-image" onerror="this.src='images/default-tradition.jpg'">
                <div class="result-content">
                    <h3>${tradition.title}</h3>
                    <p class="state">${tradition.state_name}</p>
                    <p>${tradition.description}</p>
                    <a href="tradition-details.php?id=${tradition.tradition_id}" class="btn">View Details</a>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    // Add event listeners
    console.log('Adding event listeners');
    searchButton.addEventListener('click', () => {
        console.log('Search button clicked');
        performSearch();
    });
    
    searchInput.addEventListener('keypress', (e) => {
        console.log('Key pressed:', e.key);
        if (e.key === 'Enter') {
            console.log('Enter key pressed, performing search');
            performSearch();
        }
    });

    // Initialize filter buttons
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.dataset.filter;
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            if (filter === 'all') {
                document.getElementById('heritage-results').style.display = 'block';
                document.getElementById('culture-results').style.display = 'block';
                document.getElementById('traditions-results').style.display = 'block';
            } else {
                document.getElementById('heritage-results').style.display = filter === 'heritage' ? 'block' : 'none';
                document.getElementById('culture-results').style.display = filter === 'culture' ? 'block' : 'none';
                document.getElementById('traditions-results').style.display = filter === 'traditions' ? 'block' : 'none';
            }
        });
    });

    console.log('Initialization complete');
}); 