// Theme Toggle Script
// Initialize theme on page load
document.addEventListener('DOMContentLoaded', function() {
    // Get saved theme from localStorage or check system preference
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initialTheme = savedTheme || (prefersDark ? 'dark' : 'light');
    
    // Set initial theme
    setTheme(initialTheme);
    
    // Add theme toggle button listener
    const themeToggle = document.querySelector('.theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
});

// Function to set theme
function setTheme(theme) {
    const htmlElement = document.documentElement;
    const themeToggle = document.querySelector('.theme-toggle');
    
    // Set data-theme attribute
    if (theme === 'dark') {
        htmlElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    } else {
        htmlElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
    }
    
    // Update toggle button display
    if (themeToggle) {
        if (theme === 'dark') {
            themeToggle.setAttribute('title', 'Switch to Light Mode');
        } else {
            themeToggle.setAttribute('title', 'Switch to Dark Mode');
        }
    }
}

// Function to toggle theme
function toggleTheme() {
    const htmlElement = document.documentElement;
    const currentTheme = htmlElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    setTheme(newTheme);
}
