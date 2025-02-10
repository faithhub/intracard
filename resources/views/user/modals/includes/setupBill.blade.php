<div id="modalLoader"
    style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1051;">
    <div
        style="border: 6px solid #f3f3f3; border-radius: 50%; border-top: 6px solid #3498db; width: 40px; height: 40px; animation: spin 1s linear infinite;">
    </div>
</div>
<div id="modalLoader"
    style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1051;">
    <div
        style="border: 6px solid #f3f3f3; border-radius: 50%; border-top: 6px solid #3498db; width: 40px; height: 40px; animation: spin 1s linear infinite;">
    </div>
</div>

<style>
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
<script>
    let parentModal;

    function setupBill(event) {
        event.preventDefault();
        /*var element = $(event.target).is('a') ? $(event.target) : $(event.target).parents('a');*/
        var element = $(event.currentTarget);
        //console.log(element);
        var url = element.attr("href");
        //console.log(url)
        var title = element.data("title");
        var type = element.data("type");
        var size = element.data("size");

        title = title ? title : "Add new";
        size = size ? size : "m";
        type = type ? type : "";

        setupBilldialog(url, title, size, type);
    }

    function setupBilldialog(url, title = "Operation", size, type = "") {
        parentModal = $.confirm({
            content: function() {
                var self = this;
                return $.ajax({
                        url: url,
                        method: "get",
                    })
                    .done(function(data) {
                        self.setContent(data); // Inject the HTML into the dialog
                        self.setTitle(title); // Set the title
                    })
                    .fail(function(err) {
                        console.error("Failed to load content:", err);
                        self.setContent("Something went wrong");
                    });
            },
            onContentReady: function() {
                const form = this.$content.find('#billForm'); // Locate the form in the loaded content

                if (form.length === 0) {
                    console.error("Form not found in dialog content."); // Debugging
                    return;
                }

                // Initialize validation
                form.validate({
                    rules: {
                        // Bill Type Dropdown
                        bill_type: {
                            required: true, // Ensures the user selects a bill type
                        },

                        // Shared Fields
                        amount: {
                            required: function() {
                                const billType = $('#billTypeSelect').val();
                                return billType !== "carBill" && billType !==
                                ""; // Required for non-car bills
                            },
                            number: true, // Must be a number
                            min: 50, // Must be greater than 0
                        },
                        due_date: {
                            required: function() {
                                const billType = $('#billTypeSelect').val();
                                return billType !== "carBill" && billType !==
                                ""; // Required for non-car bills
                            },
                            date: true, // Must be a valid date
                        },
                        provider: {
                            required: function() {
                                const billType = $('#billTypeSelect').val();
                                return billType !== "carBill" && billType !==
                                ""; // Required for non-car bills
                            },
                        },
                        account_number: {
                            required: function() {
                                const billType = $('#billTypeSelect').val();
                                return billType !== "carBill" && billType !==
                                ""; // Required for non-car bills
                            },
                            minlength: 6, // Minimum 6 characters
                        },

                        // Car-Specific Fields
                        frequency: {
                            required: function() {
                                return $('#billTypeSelect').val() ===
                                "carBill"; // Only required for car bills
                            },
                        },
                        car_due_date: {
                            required: function() {
                                return $('#billTypeSelect').val() ===
                                "carBill"; // Only required for car bills
                            },
                        },
                        car_model: {
                            required: function() {
                                return $('#billTypeSelect').val() === "carBill";
                            },
                            minlength: 2, // Minimum 2 characters
                        },
                        car_year: {
                            required: function() {
                                return $('#billTypeSelect').val() === "carBill";
                            },
                            number: true, // Must be a number
                            minlength: 4, // Year should be in YYYY format
                            maxlength: 4, // Prevent invalid year input
                        },
                        car_vin: {
                            required: function() {
                                return $('#billTypeSelect').val() === "carBill";
                            },
                            minlength: 10, // Minimum VIN length
                        },
                        car_amount: {
                            required: function() {
                                return $('#billTypeSelect').val() === "carBill";
                            },
                            min: 50, // Minimum VIN length
                        },

                        // Payment Card
                        payment_card: {
                            required: true, // Ensure the user selects a payment card
                        },
                    },

                    messages: {
                        // Bill Type Dropdown
                        bill_type: "Please select a bill type.",

                        // Shared Fields
                        amount: {
                            required: "Please enter the amount.",
                            number: "The amount must be a valid number.",
                            min: "The amount must be greater than 50.",
                        },
                        car_amount: {
                            required: "Please enter the amount.",
                            number: "The amount must be a valid number.",
                            min: "The amount must be greater than 50.",
                        },
                        due_date: {
                            required: "Please select the due date.",
                            date: "Please enter a valid date.",
                        },
                        provider: "Please select a provider.",
                        account_number: {
                            required: "Please enter the account number.",
                            minlength: "The account number must be at least 6 characters long.",
                        },

                        // Car-Specific Fields
                        frequency: "Please select the frequency.",
                        car_model: {
                            required: "Please enter the car model.",
                            minlength: "The car model must be at least 2 characters long.",
                        },
                        car_due_date: "Please select the due date.",
                        car_year: {
                            required: "Please enter the car year.",
                            number: "The year must be a valid number.",
                            minlength: "The year must have 4 digits (e.g., 2022).",
                            maxlength: "The year must have 4 digits (e.g., 2022).",
                        },
                        car_vin: {
                            required: "Please enter the car VIN.",
                            minlength: "The VIN must be at least 10 characters long.",
                        },

                        // Payment Card
                        payment_card: "Please select a payment card.",
                    },

                    errorPlacement: function(error, element) {
                        if (element.attr("name") === "payment_card") {
                            // Append error for the payment_card group
                            error.addClass('text-danger mt-1'); // Add custom error styling
                            element.closest('.mb-0').append(
                                error); // Place the error after the radio button group
                        } else {
                            error.addClass(
                                'text-danger mt-1'
                            ); // Add custom error styling for other fields
                            element.closest('.border').append(
                                error); // Append error below the input field
                        }
                    },
                    submitHandler: function(form) {
                        // Show confirmation modal when the form is valid
                        //   showTransactionConfirmationModal(form); // Show transaction confirmation modal
                        //   this.close(); // Close the parent modal
                    },
                    // errorPlacement: function(error, element) {
                    //     error.addClass('text-danger mt-1');
                    //     element.closest('.border').append(error); // Append the error to the correct place
                    // },
                });
            },
            buttons: {
                save: {
                    text: 'Proceed',
                    btnClass: 'btn-primary',
                    action: function() {
                        const form = this.$content.find('#billForm');
                        if (form.valid()) {
                            showTransactionConfirmationModal(
                                form); // Show transaction confirmation modal
                            console.log('Form is valid and submitted.');
                            // Handle form submission logic here
                            return false; // Close the dialog
                        } else {
                            console.log('Form validation failed.');
                            return false; // Prevent closing the dialog
                        }
                    },
                },
                close: {
                    text: 'Close',
                    action: function() {
                        console.log('Dialog closed.');
                    },
                },
            },
            columnClass: size,
            type: type,
            containerFluid: true,
            draggable: true,
            backgroundDismiss: false,
            closeIcon: false,
        });

    }
    // Close Button Handler
    $(document).on('click', '#closeParentModalButton', function() {
        if (parentModal) {
            parentModal.close(); // Close the modal
            console.log('Parent modal closed via Close button.');
        } else {
            console.error('No modal instance found.');
        }
    });


    function showTransactionConfirmationModal(form) {
        // Fetching and calculating values
        const billType = $('#billTypeSelect option:selected').text() || 'N/A'; // Get the selected text
        const rawAmount = parseFloat(
            $('#carAmount').val() || $('#utilityAmount').val() || $('#phoneAmount').val() || $('#internetAmount')
            .val() || 0
        );
        const paymentCard = $('input[name="payment_card"]:checked').closest('label').find('.cc-last-4').text() ||
            'None selected'; // Get card type and last four digits
        const vat = (rawAmount * 0.05).toFixed(2); // 5% VAT
        const totalAmount = (rawAmount + parseFloat(vat)).toFixed(2);

        // Format the amounts
        const formattedAmount = formatCurrency(rawAmount);
        const formattedVAT = formatCurrency(vat);
        const formattedTotal = formatCurrency(totalAmount);

        // Modal content
        const modalContent = `
        <div style="font-size: 16px; line-height: 1.5;">
            <p style="margin-bottom: 1rem;">Please confirm the transaction details:</p>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 1rem;">
                <tr style="background-color: #f8f9fa;">
                    <td style="padding: 8px; font-weight: bold;">Bill Type</td>
                    <td style="padding: 8px; text-align: right;">${billType}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Amount</td>
                    <td style="padding: 8px; text-align: right;">$${formattedAmount}</td>
                </tr>
                <tr style="background-color: #f8f9fa;">
                    <td style="padding: 8px; font-weight: bold;">VAT (5%)</td>
                    <td style="padding: 8px; text-align: right;">$${formattedVAT}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold;">Payment Card:</td>
                    <td style="padding: 8px; text-align: right; text-transform: capitalize;"> ${paymentCard}</td>
                </tr>
                <tr style="background-color: #f8f9fa;">
                    <td style="padding: 8px; font-weight: bold;">Total Amount</td>
                    <td style="padding: 8px; text-align: right; font-weight: bold;">$${formattedTotal}</td>
                </tr>
            </table>
            <p>Do you want to proceed?</p>
        </div>
    `;

        // Displaying the confirmation modal
        $.confirm({
            title: "Transaction Confirmation",
            content: modalContent,
            buttons: {
                confirm: {
                    text: "Submit",
                    btnClass: "btn btn-primary m-2",
                    action: function() {
                        console.log("Submitting wallet transaction via AJAX...");
                        $('#modalLoader').fadeIn(); // Show modalLoader

                        // Handle form submission via AJAX
                        const formData = $(form).serialize();
                        $.ajax({
                            url: $(form).attr('action'), // Form action URL
                            type: $(form).attr('method') || 'POST', // Form method
                            data: formData,
                            success: function(response) {
                                console.log('Transaction successful:', response);
                                $('#modalLoader').fadeOut(); // Hide modalLoader
                                $.alert({
                                    title: "Success!",
                                    content: "Your transaction has been processed successfully.",
                                    type: "green",
                                });
                            },
                            error: function(error) {
                                $('#modalLoader').fadeOut(); // Hide modalLoader
                                console.error('Transaction failed:', error);
                                $.alert({
                                    title: "Error!",
                                    content: "An error occurred during the transaction. Please try again.",
                                    type: "red",
                                });
                            },
                        });

                        return false; // Prevent modal from closing immediately
                    },
                },
                cancel: {
                    text: "Cancel",
                    btnClass: "btn btn-secondary m-2",
                    action: function() {
                        console.log("Transaction cancelled.");
                    },
                },
            },
            type: "dark",
            backgroundDismiss: false,
            closeIcon: false,
            draggable: true,
        });
    }

    // Helper function to format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2
        }).format(amount);
    }
</script>
