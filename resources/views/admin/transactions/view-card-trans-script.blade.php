<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
<script>
    let cardTransactionModal;

    function cardTransactionEvent(event) {
        event.preventDefault();
        var element = $(event.currentTarget); // Current clicked element
        var url = element.attr("href"); // URL to fetch modal content
        var title = element.data("title") || "Card Transaction Details";
        var size = element.data("size") || "m";
        var type = element.data("type") || "";

        // Add UUID for download link if provided
        var uuid = element.data("uuid");

        cardTransactiondialog(url, title, size, type, uuid);
    }


    function cardTransactiondialog(url, title = "Operation", size, type = "", uuid = null) {
        var jesus = $.confirm({
            content: function() {
                var self = this;
                return $.ajax({
                        url: url,
                        method: "get",
                    })
                    .done(function(data) {
                        self.setContent(data); // Set modal content
                        self.setTitle(title);
                    })
                    .fail(function(err) {
                        console.error(err);
                        self.setContent("Something went wrong");
                    });
            },
            buttons: {
                Close: function() {
                    // Close button logic
                },
                Download: {
                    btnClass: "btn-primary",
                    text: "Download",
                    action: function() {
                        // Redirect to the download route
                        if (uuid) {
                            window.open(`/admin/card-transactions/download/${uuid}`, 'blank');
                        } else {
                            console.error("UUID is not provided for the transaction.");
                        }
                    },
                },
            },
            columnClass: size,
            type: type,
            containerFluid: true,
            draggable: true,
            backgroundDismiss: true,
        });
    }
</script>
