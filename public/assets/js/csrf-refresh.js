// csrf-refresh.js
document.addEventListener('DOMContentLoaded', function() {
    // Set up CSRF token refresh every 45 minutes
    setInterval(function() {
        fetch('/refresh-csrf')
            .then(response => response.json())
            .then(data => {
                // Update all CSRF tokens in forms
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.token;
                });
            })
            .catch(console.error);
    }, 45 * 60 * 1000); // 45 minutes
});