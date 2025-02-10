<script>
 document.querySelectorAll('.logoutBtn').forEach((button) => {
    button.addEventListener('click', function (e) {
        e.preventDefault();

        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Confirm logout action using SweetAlert
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out of your account!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, log me out!",
            cancelButtonText: "Cancel",
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-secondary"
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to log out
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "{{ route('admin.logout-admin') }}", true);
                xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);

                xhr.onload = function () {
                    var response = JSON.parse(xhr.responseText);

                    if (xhr.status === 200 && response.success) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            buttonsStyling: false,
                            timer: 2000, // Set SweetAlert to automatically close after 2 seconds
                            showConfirmButton: false
                        });

                        setTimeout(function () {
                            window.location.href = response.redirect_url;
                        }, 2000);

                    } else {
                        Swal.fire({
                            text: "An error occurred during logout: " + response.message,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-danger"
                            }
                        });
                    }
                };

                xhr.send();
            }
        });
    });
});
</script>