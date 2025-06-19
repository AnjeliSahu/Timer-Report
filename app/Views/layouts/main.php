<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timer Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
            margin: 0;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        /* Search Panel Styles */
        .search-panel {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .search-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        
        .search-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .search-field {
            display: flex;
            flex-direction: column;
        }
        
        .search-field label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .search-field input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .search-field input:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.2);
        }
        
        .search-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
            font-weight: 500;
        }
        
        .btn-primary {
            background-color: #1976d2;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #1565c0;
        }
        
        .btn-secondary {
            background-color: #757575;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #616161;
        }
        
        /* Results Counter */
        .results-info {
            background-color: white;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            color: #666;
            font-size: 14px;
        }
        
        /* Table Styles */
        .table-container {
            overflow-x: auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            min-width: 800px;
        }
        
        thead th {
            background-color: #1976d2;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 14px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
            font-size: 13px;
        }
        
        .zebra:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .zebra:nth-child(odd) {
            background-color: white;
        }
        
        .user-group {
            border-top: 2px solid #1976d2;
        }
        
        .loading {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #666;
            background-color: white;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .loading::after {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #1976d2;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #d32f2f;
            background-color: #ffebee;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #ffcdd2;
        }
        
        .no-results {
            text-align: center;
            padding: 30px;
            color: #666;
            font-style: italic;
            background-color: white;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        /* Highlight matching text */
        .highlight {
            background-color: #ffeb3b;
            padding: 1px 2px;
            border-radius: 2px;
            font-weight: bold;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .search-grid {
                grid-template-columns: 1fr;
            }
            
            .search-actions {
                justify-content: center;
            }
            
            h1 {
                font-size: 24px;
            }
        }
        
        /* Export Button */
        .export-btn {
            background-color: #4caf50;
            color: white;
            margin-left: 10px;
        }
        
        .export-btn:hover {
            background-color: #45a049;
        }
        
        /* Status indicators */
        .status-online {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #4caf50;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .status-offline {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #f44336;
            border-radius: 50%;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<h1>Timer Report Dashboard</h1>

<!-- Search Panel -->
<div class="search-panel">
    <div class="search-title">Search Filters</div>
    <div class="search-grid">
        <div class="search-field">
            <label for="searchUser">User Name</label>
            <input type="text" id="searchUser" placeholder="Search by user name...">
        </div>
        <div class="search-field">
            <label for="searchProject">Project Name</label>
            <input type="text" id="searchProject" placeholder="Search by project name...">
        </div>
        <div class="search-field">
            <label for="searchActivity">Activity</label>
            <input type="text" id="searchActivity" placeholder="Search by activity...">
        </div>
        <div class="search-field">
            <label for="searchTask">Task</label>
            <input type="text" id="searchTask" placeholder="Search by task...">
        </div>
        <div class="search-field">
            <label for="searchStartDate">Start Date</label>
            <input type="date" id="searchStartDate">
        </div>
        <div class="search-field">
            <label for="searchEndDate">End Date</label>
            <input type="date" id="searchEndDate">
        </div>
    </div>
    <div class="search-actions">
        <button class="btn btn-secondary" onclick="clearFilters()">Clear All</button>
        <button class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
        <button class="btn export-btn" onclick="exportToCSV()">Export CSV</button>
        <button class="btn btn-primary" onclick="refreshData()">Refresh</button>
    </div>
</div>

<div id="loadingMessage" class="loading">Loading data...</div>
<div id="errorMessage" class="error" style="display: none;"></div>

<div id="resultsInfo" class="results-info" style="display: none;"></div>

<div class="table-container" id="tableContainer" style="display: none;">
    <table id="reportTable">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Project Name</th>
                <th>Activity</th>
                <th>Task</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody id="reportBody"></tbody>
    </table>
</div>

<div id="noResults" class="no-results" style="display: none;">
    No records match your search criteria. Try adjusting your filters.
</div>

<script>
// Global variables
let allTimers = []; // Store all data for filtering
let filteredTimers = []; // Store filtered results
let isLoading = false;

// Configuration - Update this with your actual API endpoint
const API_CONFIG = {
    // For CodeIgniter implementation
    endpoint: 'api/timers',
    // For direct PHP implementation (uncomment if using original PHP files)
    // endpoint: 'config.php'
};

// Utility Functions
function formatDateTime(dateTimeString) {
    if (!dateTimeString) return '';
    if (dateTimeString.includes('/')) return dateTimeString;
    
    try {
        const date = new Date(dateTimeString);
        if (isNaN(date.getTime())) return dateTimeString;
        
        return date.toLocaleString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    } catch (e) {
        return dateTimeString;
    }
}

function extractDate(dateTimeString) {
    if (!dateTimeString) return '';
    
    try {
        const date = new Date(dateTimeString);
        if (isNaN(date.getTime())) return '';
        return date.toISOString().split('T')[0]; // Return YYYY-MM-DD format
    } catch (e) {
        return '';
    }
}

function highlightText(text, searchTerm) {
    if (!searchTerm || !text) return text;
    
    const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
    return text.replace(regex, '<span class="highlight">$1</span>');
}

function escapeHtml(unsafe) {
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

// Display Functions
function displayData(timers, searchTerms = {}) {
    const tbody = document.getElementById('reportBody');
    const loadingMessage = document.getElementById('loadingMessage');
    const tableContainer = document.getElementById('tableContainer');
    const resultsInfo = document.getElementById('resultsInfo');
    const noResults = document.getElementById('noResults');
    
    // Clear existing content
    tbody.innerHTML = '';
    
    // Update results info
    resultsInfo.innerHTML = `
        <span class="status-online"></span>
        Showing ${timers.length} of ${allTimers.length} records
        ${timers.length !== allTimers.length ? '(filtered)' : ''}
    `;
    resultsInfo.style.display = 'block';
    
    if (timers.length === 0) {
        tableContainer.style.display = 'none';
        noResults.style.display = 'block';
        return;
    }
    
    noResults.style.display = 'none';
    
    let currentUser = '';
    
    timers.forEach((rec, idx) => {
        const row = document.createElement('tr');
        row.className = 'zebra';
        
        // Add user group separator
        if (rec.userName !== currentUser) {
            if (currentUser !== '') {
                row.classList.add('user-group');
            }
            currentUser = rec.userName;
        }
        
        // Apply highlighting to matching terms - escape HTML first, then highlight
        const userName = highlightText(escapeHtml(rec.userName || ''), searchTerms.user);
        const project = highlightText(escapeHtml(rec.project || ''), searchTerms.project);
        const activity = highlightText(escapeHtml(rec.activity || ''), searchTerms.activity);
        const task = highlightText(escapeHtml(rec.task || ''), searchTerms.task);
        
        row.innerHTML = `
            <td>${userName}</td>
            <td>${project}</td>
            <td>${activity}</td>
            <td>${task}</td>
            <td>${escapeHtml(formatDateTime(rec.startTime))}</td>
            <td>${escapeHtml(formatDateTime(rec.endTime))}</td>
            <td>${escapeHtml(rec.duration || '')}</td>
        `;
        
        tbody.appendChild(row);
    });
    
    loadingMessage.style.display = 'none';
    tableContainer.style.display = 'block';
}

function showError(message) {
    const errorMessage = document.getElementById('errorMessage');
    const loadingMessage = document.getElementById('loadingMessage');
    const resultsInfo = document.getElementById('resultsInfo');
    
    errorMessage.innerHTML = `
        <span class="status-offline"></span>
        ${message}
    `;
    errorMessage.style.display = 'block';
    loadingMessage.style.display = 'none';
    
    resultsInfo.innerHTML = `
        <span class="status-offline"></span>
        Connection failed - showing cached data if available
    `;
    resultsInfo.style.display = 'block';
}

function showLoading() {
    const loadingMessage = document.getElementById('loadingMessage');
    const errorMessage = document.getElementById('errorMessage');
    const tableContainer = document.getElementById('tableContainer');
    const noResults = document.getElementById('noResults');
    
    loadingMessage.style.display = 'block';
    errorMessage.style.display = 'none';
    tableContainer.style.display = 'none';
    noResults.style.display = 'none';
    
    isLoading = true;
}

// Filter Functions
function applyFilters() {
    const searchTerms = {
        user: document.getElementById('searchUser').value.toLowerCase().trim(),
        project: document.getElementById('searchProject').value.toLowerCase().trim(),
        activity: document.getElementById('searchActivity').value.toLowerCase().trim(),
        task: document.getElementById('searchTask').value.toLowerCase().trim(),
        startDate: document.getElementById('searchStartDate').value,
        endDate: document.getElementById('searchEndDate').value
    };
    
    filteredTimers = allTimers.filter(timer => {
        // Text-based filters
        if (searchTerms.user && !timer.userName.toLowerCase().includes(searchTerms.user)) {
            return false;
        }
        if (searchTerms.project && !timer.project.toLowerCase().includes(searchTerms.project)) {
            return false;
        }
        if (searchTerms.activity && !timer.activity.toLowerCase().includes(searchTerms.activity)) {
            return false;
        }
        if (searchTerms.task && !timer.task.toLowerCase().includes(searchTerms.task)) {
            return false;
        }
        
        // Date-based filters
        if (searchTerms.startDate || searchTerms.endDate) {
            const recordStartDate = extractDate(timer.startTime);
            const recordEndDate = extractDate(timer.endTime);
            
            if (searchTerms.startDate && recordStartDate && recordStartDate < searchTerms.startDate) {
                return false;
            }
            if (searchTerms.endDate && recordEndDate && recordEndDate > searchTerms.endDate) {
                return false;
            }
        }
        
        return true;
    });
    
    displayData(filteredTimers, searchTerms);
}

function clearFilters() {
    // Clear all input fields
    document.getElementById('searchUser').value = '';
    document.getElementById('searchProject').value = '';
    document.getElementById('searchActivity').value = '';
    document.getElementById('searchTask').value = '';
    document.getElementById('searchStartDate').value = '';
    document.getElementById('searchEndDate').value = '';
    
    // Show all data
    filteredTimers = [...allTimers];
    displayData(filteredTimers);
}

// Export Function
function exportToCSV() {
    if (filteredTimers.length === 0) {
        alert('No data to export');
        return;
    }
    
    const headers = ['User Name', 'Project Name', 'Activity', 'Task', 'Start Time', 'End Time', 'Duration'];
    const csvContent = [
        headers.join(','),
        ...filteredTimers.map(timer => [
            `"${timer.userName}"`,
            `"${timer.project}"`,
            `"${timer.activity}"`,
            `"${timer.task}"`,
            `"${formatDateTime(timer.startTime)}"`,
            `"${formatDateTime(timer.endTime)}"`,
            `"${timer.duration}"`
        ].join(','))
    ].join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `timer_report_${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Data Loading Function
function loadData() {
    if (isLoading) return;
    
    showLoading();
    
    fetch(API_CONFIG.endpoint)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);
            
            if (!data.success) {
                showError("Error fetching data: " + (data.error || 'Unknown error'));
                return;
            }
            
            if (!data.timers || !Array.isArray(data.timers)) {
                showError("Invalid data format received from server.");
                return;
            }
            
            allTimers = data.timers;
            filteredTimers = [...allTimers];
            displayData(filteredTimers);
            
            // Cache data locally
            try {
                sessionStorage.setItem('timerData', JSON.stringify(data));
                sessionStorage.setItem('timerDataTimestamp', Date.now().toString());
            } catch (e) {
                console.warn('Could not cache data:', e);
            }
            
        })
        .catch(error => {
            console.error('Fetch error:', error);
            
            // Try to load cached data
            try {
                const cachedData = sessionStorage.getItem('timerData');
                const cacheTimestamp = sessionStorage.getItem('timerDataTimestamp');
                const fiveMinutesAgo = Date.now() - (5 * 60 * 1000);
                
                if (cachedData && cacheTimestamp && parseInt(cacheTimestamp) > fiveMinutesAgo) {
                    const data = JSON.parse(cachedData);
                    if (data.success && data.timers) {
                        allTimers = data.timers;
                        filteredTimers = [...allTimers];
                        displayData(filteredTimers);
                        showError("Using cached data (last updated: " + new Date(parseInt(cacheTimestamp)).toLocaleTimeString() + ")");
                        return;
                    }
                }
            } catch (e) {
                console.warn('Could not load cached data:', e);
            }
            
            showError("Could not load data. Please check your connection and try again.");
        })
        .finally(() => {
            isLoading = false;
        });
}

function refreshData() {
    // Clear cache and reload
    try {
        sessionStorage.removeItem('timerData');
        sessionStorage.removeItem('timerDataTimestamp');
    } catch (e) {
        console.warn('Could not clear cache:', e);
    }
    
    loadData();
}

// Event Listeners Setup
document.addEventListener('DOMContentLoaded', function() {
    // Real-time search functionality
    const searchInputs = ['searchUser', 'searchProject', 'searchActivity', 'searchTask'];
    
    searchInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        input.addEventListener('input', function() {
            // Add a small delay to avoid too many filter calls
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                applyFilters();
            }, 300);
        });
    });
    
    // Date inputs trigger immediate filtering
    document.getElementById('searchStartDate').addEventListener('change', applyFilters);
    document.getElementById('searchEndDate').addEventListener('change', applyFilters);
    
    // Enter key triggers search
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
    
    // Auto-refresh every 5 minutes
    setInterval(() => {
        if (!isLoading) {
            console.log('Auto-refreshing data...');
            loadData();
        }
    }, 5 * 60 * 1000);
    
    // Load initial data
    loadData();
});

// Error handling for uncaught errors
window.addEventListener('error', function(e) {
    console.error('Uncaught error:', e.error);
    showError('An unexpected error occurred. Please refresh the page.');
});

// Handle page visibility changes (pause/resume auto-refresh)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && !isLoading) {
        // Page became visible, refresh data
        loadData();
    }
});
</script>

</body>
</html>