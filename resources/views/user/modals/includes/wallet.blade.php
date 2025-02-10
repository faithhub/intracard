  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
  <script>
    let walletModal;
      function walletEvent(event) {
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

          walletdialog(url, title, size, type);
      }

      function walletdialog(url, title = "Operation", size, type = "") {
        walletModal = $.confirm({
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
                  const form = this.$content.find('#walletForm'); // Locate the form in the loaded content

                  if (form.length === 0) {
                      console.error("Form not found in dialog content."); // Debugging
                      return;
                  }

                  // Initialize validation
                  form.validate({
                      rules: {
                          service: {
                              required: true, // Ensures the user selects a service
                          },
                          amount: {
                              required: true,
                              number: true, // Must be a number
                              min: 1, // Must be greater than 0
                          },
                          payment_card: {
                              required: true, // Ensure the user selects a payment card
                          },
                      },
                      messages: {
                          service: "Please select a service.",
                          amount: {
                              required: "Please enter the amount.",
                              number: "The amount must be a valid number.",
                              min: "The amount must be greater than 0.",
                          },
                          payment_card: "Please select a payment card.",
                      },
                      errorPlacement: function(error, element) {
                          if (element.attr("name") === "payment_card") {
                              // Append error for the payment_card group
                              error.addClass('text-danger mt-1');
                              element.closest('.mb-0').append(error);
                          } else if (element.attr("name") === "service") {
                              // Append error for the service dropdown
                              error.addClass('text-danger mt-1');
                              element.closest('.mb-5').append(error);
                          } else if (element.attr("name") === "amount") {
                              // Append error for the amount field
                              error.addClass('text-danger mt-1');
                              element.closest('.mb-5').append(error);
                          } else {
                              error.addClass('text-danger mt-1');
                              element.closest('.border').append(error);
                          }
                      },
                      submitHandler: function(form) {
                          // Handle form submission logic here
                      },
                  });

              },
              buttons: {
                  save: {
                      text: 'Proceed',
                      btnClass: 'btn-primary',
                      action: function() {
                        const form = this.$content.find('#walletForm');
                        if (form.valid()) {
                              showWalletConfirmationModal(form); // Show transaction confirmation modal
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
        $(document).on('click', '#closeWalletModalButton', function() {
          if (walletModal) {
              walletModal.close(); // Close the modal
              console.log('Parent modal closed via Close button.');
          } else {
              console.error('No modal instance found.');
          }
      });


      function showWalletConfirmationModal(form) {
    // Fetching and calculating values
    const serviceType = $('#service option:selected').text() || 'N/A'; // Get the selected text for service
    const rawAmount = parseFloat($('#amount').val() || 0); // Get entered amount
    const paymentCard = $('input[name="payment_card"]:checked').closest('label').find('.cc-last-4').text() || 'None selected'; // Get card type and last four digits
    const vat = (rawAmount * 0.05).toFixed(2); // Calculate 5% VAT
    const totalAmount = (rawAmount + parseFloat(vat)).toFixed(2); // Total amount

    // Format the amounts
    const formattedAmount = formatCurrency(rawAmount);
    const formattedVAT = formatCurrency(vat);
    const formattedTotal = formatCurrency(totalAmount);

    // Modal content
    const modalContent = `
        <div style="font-size: 16px; line-height: 1.5;">
            <p style="margin-bottom: 1rem;">Please confirm your wallet transaction details:</p>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 1rem;">
                <tr style="background-color: #f8f9fa;">
                    <td style="padding: 8px; font-weight: bold;">Service Type</td>
                    <td style="padding: 8px; text-align: right;">${serviceType}</td>
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
                    <td style="padding: 8px; font-weight: bold;">Payment Card</td>
                    <td style="padding: 8px; text-align: right;">${paymentCard}</td>
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
        title: "Wallet Transaction Confirmation",
        content: modalContent,
        buttons: {
            confirm: {
                text: "Submit",
                btnClass: "btn btn-primary m-2",
                action: function () {
                    console.log("Submitting wallet transaction via AJAX...");

                    // Handle form submission via AJAX
                    const formData = $(form).serialize();
                    $.ajax({
                        url: $(form).attr('action'), // Form action URL
                        type: $(form).attr('method') || 'POST', // Form method
                        data: formData,
                        success: function (response) {
                            console.log('Transaction successful:', response);
                            $.alert({
                                title: "Success!",
                                content: "Your transaction has been processed successfully.",
                                type: "green",
                            });
                        },
                        error: function (error) {
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
                action: function () {
                    console.log("Transaction cancelled.");
                },
            },
        },
        type: "dark",
        backgroundDismiss: false,
        closeIcon: true,
        draggable: true,
    });
}

// Helper function to format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', { style: 'decimal', minimumFractionDigits: 2 }).format(amount);
}


      // Helper function to format currency
      function formatCurrency(amount) {
          return new Intl.NumberFormat('en-US', {
              style: 'decimal',
              minimumFractionDigits: 2
          }).format(amount);
      }
  </script>
