/*shi CRUD scripts*/

function editConfirm(event) {
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

    editAutoReply(url, title, size, type);
}
function createConfirm(event) {
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

    createAutoReply(url, title, size, type);
}

function shiNew(event) {
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

    dialog(url, title, size, type);
}

function shiSubAdmin(event) {
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

    createAutoReply(url, title, size, type);
}

function shiEdit(event) {
    event.preventDefault();

    /*var element = $(event.target).is('a') ? $(event.target) : $(event.target).parents('a');*/
    var element = $(event.currentTarget);
    var url = element.attr("href");
    var title = element.data("title");
    var type = element.data("type");
    var size = element.data("size");

    title = title ? title : "Edit entity";
    size = size ? size : "m";
    type = type ? type : "";
    // console.log(size);

    dialog(url, title, size, type);
}
function shiSub(event) {
    event.preventDefault();

    /*var element = $(event.target).is('a') ? $(event.target) : $(event.target).parents('a');*/
    var element = $(event.currentTarget);
    var url = element.attr("href");
    var title = element.data("title");
    var type = element.data("type");
    var size = element.data("size");

    title = title ? title : "Edit entity";
    size = size ? size : "m";
    type = type ? type : "";
    // console.log(size);

    dialog(url, title, size, type);
}

function createAutoReply(url, title = "Operation", size, type = "") {
    var jesus = $.confirm({
        content: function () {
            var self = this;
            return $.ajax({
                url: url,
                method: "get",
            })
                .done(function (data) {
                    self.setContent(data);
                    self.setTitle(title);
                })
                .fail(function (err) {
                    console.log(err);
                    self.setContent("Something went wrong");
                });
        },
        buttons: {
            save: {
                text: "Proceed",
                btnClass: "btn-primary",
                action: function () {
                    const form = this.$content.find("#createAutoReplyForm");

                    // Validate form inputs
                    if (form[0].checkValidity()) {
                        // Perform AJAX submission
                        const formData = form.serialize(); // Serialize form data
                        $.ajax({
                            url: form.attr("action"), // Use the form action URL
                            type: form.attr("method"), // Use the form method (POST)
                            data: formData,
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"), // Include CSRF token
                            },
                            success: function (response) {
                                $.alert({
                                    title: "Success",
                                    content: "Auto-reply created successfully!",
                                });
                                jesus.close(); // Close the dialog
                            },
                            error: function (xhr, status, error) {
                                console.log("Error:", error);
                                $.alert({
                                    title: "Error",
                                    content:
                                        "Failed to create auto-reply. Please try again.",
                                });
                            },
                        });

                        return false; // Prevent default dialog closing
                    } else {
                        // Trigger built-in browser validation for invalid fields
                        form[0].reportValidity();
                        return false; // Prevent dialog closing on invalid form
                    }
                },
            },
            Close: function () {
                // Close button logic
            },
        },
        columnClass: size,
        type: type,
        containerFluid: true,
        draggable: true,
        backgroundDismiss: false,
        // defaultButtons: {
        //     ok: {
        //         action: function () {},
        //     },
        //     close: {
        //         action: function () {},
        //     },
        // },
        //type:type
    });
}

function editAutoReply(url, title = "Operation", size, type = "") {
    var jesus = $.confirm({
        content: function () {
            var self = this;
            return $.ajax({
                url: url,
                method: "get",
            })
                .done(function (data) {
                    self.setContent(data);
                    self.setTitle(title);
                })
                .fail(function (err) {
                    console.log(err);
                    self.setContent("Something went wrong");
                });
        },
        buttons: {
            save: {
                text: "Proceed",
                btnClass: "btn-primary",
                action: function () {
                    // Select the form inside the modal
                    const form = this.$content.find("#editAutoReplyForm");

                    // Check if the form exists
                    if (form.length === 0) {
                        console.error("Form not found in the modal content.");
                        return false;
                    }

                    // Validate the form using the browser's checkValidity
                    if (form[0].checkValidity()) {
                        // If valid, submit the form using AJAX
                        const formData = form.serialize(); // Serialize form data
                        $.ajax({
                            url: form.attr("action"), // Use the form action URL
                            type: form.attr("method"), // Use the form method
                            data: formData,
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // CSRF token
                            },
                            success: function (response) {
                                $.alert({
                                    title: "Success",
                                    content: "Auto-reply updated successfully!",
                                });
                                jesus.close(); // Close the dialog
                            },
                            error: function (xhr, status, error) {
                                console.error("Error:", error);
                                $.alert({
                                    title: "Error",
                                    content: "Failed to update auto-reply. Please try again.",
                                });
                            },
                        });

                        return false; // Prevent the modal from closing
                    } else {
                        // Trigger browser validation for invalid fields
                        form[0].reportValidity();
                        return false; // Prevent modal from closing
                    }
                },
            },
            Close: function () {
                // Close button logic
            },
        },
        columnClass: size,
        type: type,
        containerFluid: true,
        draggable: true,
        backgroundDismiss: false,
        // defaultButtons: {
        //     ok: {
        //         action: function () {},
        //     },
        //     close: {
        //         action: function () {},
        //     },
        // },
        //type:type
    });
}

function dialog(url, title = "Operation", size, type = "") {
    var jesus = $.confirm({
        content: function () {
            var self = this;
            return $.ajax({
                url: url,
                method: "get",
            })
                .done(function (data) {
                    self.setContent(data);
                    self.setTitle(title);
                })
                .fail(function (err) {
                    console.log(err);
                    self.setContent("Something went wrong");
                });
        },
        buttons: {
            Close: function (helloButton) {
                // shorthand method to define a button
                // the button key will be used as button name
            },
        },
        columnClass: size,
        type: type,
        containerFluid: true,
        draggable: true,
        backgroundDismiss: true,
        // defaultButtons: {
        //     ok: {
        //         action: function () {},
        //     },
        //     close: {
        //         action: function () {},
        //     },
        // },
        //type:type
    });
}
