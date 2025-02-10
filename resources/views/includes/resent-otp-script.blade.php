
<style>
    #countdown {
        font-weight: bold;
        color: #dc3545; /* Red color for countdown */
    }

    #resend-btn {
        cursor: not-allowed;
        opacity: 0.5;
    }

    #resend-btn.btn-secondary {
        cursor: no-drop;
        opacity: 1;
    }
    #resend-btn.btn-dark {
        cursor: pointer;
        opacity: 1;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let resendButton = document.getElementById('resend-btn');
        let countdownElement = document.getElementById('countdown');
        let countdownTime = 10; // 5 minutes in seconds

        let interval = setInterval(function () {
            let minutes = Math.floor(countdownTime / 60);
            let seconds = countdownTime % 60;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            countdownElement.textContent = minutes + ':' + seconds;

            countdownTime--;

            if (countdownTime < 0) {
                clearInterval(interval);
                countdownElement.textContent = "0:00";
                resendButton.disabled = false;
                resendButton.classList.remove('btn-secondary');
                resendButton.classList.add('btn-dark');
            }
        }, 1000);

        resendButton.addEventListener('click', function () {
            if (!resendButton.disabled) {
                resendButton.disabled = true;
                resendButton.textContent = 'Resending...';
                // AJAX call to resend OTP
                fetch("{{ route('otp.resend') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    // Reset countdown
                    countdownTime = 300;
                    resendButton.disabled = true;
                    resendButton.classList.remove('btn-dark');
                    resendButton.classList.add('btn-secondary');
                    resendButton.textContent = 'Resend OTP';
                    // Restart countdown
                    interval = setInterval(function () {
                        let minutes = Math.floor(countdownTime / 60);
                        let seconds = countdownTime % 60;
                        seconds = seconds < 10 ? '0' + seconds : seconds;
                        countdownElement.textContent = minutes + ':' + seconds;

                        countdownTime--;

                        if (countdownTime < 0) {
                            clearInterval(interval);
                            countdownElement.textContent = "0:00";
                            resendButton.disabled = false;
                            resendButton.classList.remove('btn-secondary');
                            resendButton.classList.add('btn-dark');
                        }
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error resending OTP:', error);
                    resendButton.disabled = false;
                    resendButton.textContent = 'Resend OTP';
                });
            }
        });
    });
</script>