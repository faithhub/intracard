<style>
    /* Notification Container */
    .notification {
        font-family: Kodchasan;
        position: fixed;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgb(251 237 235 / 92%);
        ;
        color: #d93025;
        /* border: 1px solid #d93025; */
        border-radius: 5px;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        width: auto;
        /* Allow the width to adjust dynamically */
        max-width: 90%;
        /* Prevent the box from being too wide on large screens */
        z-index: 1000;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        opacity: 0;
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .notification.hidden {
        display: none;
    }


    .notification-content {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        border-radius: 5px;
        word-wrap: break-word;
        word-break: break-word;
        box-sizing: border-box;
    }

    .notification-icon {
        margin-right: 10px;
        font-size: 18px;
        flex-shrink: 0;
    }

    .notification-message {
        font-weight: bold;
        font-size: 14px;
        line-height: 1.4;
        /* Add better line height for readability */
        white-space: normal;
    }

    /* Mobile view adjustments */
    @media (max-width: 768px) {
        .notification {
            max-width: 95%;
            /* Almost full screen for better readability */
            left: 5%;
            /* Center horizontally with respect to the smaller width */
            padding: 10px 15px;
            /* Slightly reduce padding */
            font-size: 14px;
        }

        .notification-message {
            font-size: 12px;
            line-height: 1.3;
            /* Adjust for smaller font size */
        }

        .notification-icon {
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .notification {
            max-width: 100%;
            left: 0;
            border-radius: 0;
            /* Remove border radius for a clean mobile look */
        }

        .notification-content {
            flex-direction: column;
            /* Stack the icon and message */
            align-items: flex-start;
            /* Align everything to the left */
        }

        .notification-icon {
            margin-bottom: 5px;
            /* Add space between the icon and message */
        }

        .notification-message {
            text-align: left;
            /* Ensure text remains left-aligned */
        }
    }

    
</style>

<!-- Notification Container -->
{{-- <div id="notification" class="notification hidden">
    <div class="notification-content">
        <span class="notification-icon">❌</span>
        <span class="notification-message"></span>
    </div>
</div> --}}
<script>
    function showNotification2(message, type = 'error') {
        const notification = document.getElementById('notification');
        const icon = notification.querySelector('.notification-icon');
        const messageSpan = notification.querySelector('.notification-message');

        // Set message
        messageSpan.textContent = message;

        // Set styles based on type
        if (type === 'success') {
            notification.style.backgroundColor = '#e6f7e9';
            notification.style.color = '#28a745';
            notification.style.borderColor = '#28a745';
            icon.textContent = '✔️';
        } else if (type === 'error') {
            notification.style.backgroundColor = '#fdecea';
            notification.style.color = '#d93025';
            notification.style.borderColor = '#d93025';
            icon.textContent = '❌';
        }

        // Show notification
        notification.classList.remove('hidden');
        notification.style.opacity = 1;
        notification.style.transform = 'translateX(-50%)';

        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.style.opacity = 0;
            notification.style.transform = 'translateX(-50%) translateY(-20px)';
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 500);
        }, 3000);
    }

    function showNotification(message, type = 'error') {
        // Define icons and styles based on type
        let icon, backgroundColor, color, borderColor;

        if (type === 'success') {
            icon = '✔️';
            backgroundColor = '#e6f7e9';
            color = '#28a745';
            // borderColor = '#28a745';
        } else if (type === 'error') {
            icon = '❌';
            backgroundColor = '#fdecea';
            color = '#d93025';
            // borderColor = '#d93025';
        } else if (type === 'info') {
            icon = 'ℹ️';
            backgroundColor = '#e3f2fd';
            color = '#007bff';
            // borderColor = '#007bff';
        } else if (type === 'warning') {
            icon = '⚠️';
            backgroundColor = '#fff4e5';
            color = '#ffc107';
            // borderColor = '#ffc107';
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.innerHTML = `
      <div class="notification-content" style="background-color: ${backgroundColor}; color: ${color}; border: 1px solid ${borderColor};">
        <span class="notification-icon">${icon}</span>
        <span class="notification-message">${message}</span>
      </div>
    `;

        // Append notification to body
        document.body.appendChild(notification);

        // Show notification with animation
        setTimeout(() => {
            notification.style.opacity = 1;
            notification.style.transform = 'translate';
        }, 10);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.opacity = 0;
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 300000);
    }


    // Example usage:
    document.addEventListener('DOMContentLoaded', () => {
        // Show the notification on page load (for demonstration)
        // showNotification('Failed to verify code', 'error');
    });
</script>
