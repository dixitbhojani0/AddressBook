$(document).ready(function () {

    // Method to fetch & load cities
    function loadCities(selectedCityId = null) {
        $.ajax({
            url: "FetchCities.php",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    let cityDropdown = $("#inputCity");
                    cityDropdown.empty().append('<option value="">Select City</option>');

                    $.each(response.data, function (index, city) {
                        let selected = city.id == selectedCityId ? "selected" : "";
                        cityDropdown.append(`<option value="${city.id}" ${selected}>${city.city_name}</option>`);
                    });
                } else {
                    console.error("Failed to load cities:", response.message);
                }
            },
            error: function () {
                console.error("Error fetching cities.");
            }
        });
    }

    // Method to export data JSON/XML
    function downloadFile(format) {
        $.ajax({
            url: "exportAddresses.php?format=" + format,
            type: "GET",
            dataType: format === "json" ? "json" : "text", // JSON or XML
            success: function (data) {
                // Create blob
                let blobType = format === "json" ? "application/json" : "application/xml";
                let blob = new Blob([format === "json" ? JSON.stringify(data, null, 2) : data], { type: blobType });

                // Use FileSaver.js to save JSON/XML file
                saveAs(blob, `addresses.${format}`);
            },
            error: function () {
                alert("Error exporting data.");
            }
        });
    }

    // Fetch records and load data in table via AJAX
    let table = $('#myTable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        order: [[0, "desc"]], // Default sorting on the first column in descending order
        ajax: {
            url: "FetchData.php",
            type: "POST",
            dataSrc: function (response) {
                if (response.success) {
                    return response.data;
                } else {
                    alert("Error: " + response.message);
                }
            }
        },
        columns: [
            { data: null },
            { data: "name" },
            { data: "first_name" },
            { data: "email" },
            { data: "street" },
            { data: "zip_code" },
            { data: "city_name" },
            { data: "actions", orderable: false, searchable: false }
        ],
        columnDefs: [
            {
                "targets": 0, // First column (index column)
                "render": function (data, type, row, meta) {
                    return meta.row + 1; // Auto-increment index
                }
            },
            {
                targets: [1, 2, 3, 4, 5, 6], // Apply to all columns
                className: "text-wrap",
                render: function (data, type, row) {
                    return data && data.length > 30 ? data.substr(0, 30) + "..." : data;
                }
            }
        ]
    });

    if ($(".no-records").length) {
        table.clear().draw(); // Clears table if only "No records found" exists
    }

    // Submit Form via AJAX (Add/Update Address)
    $("#submitBtn").click(function (e) {
        e.preventDefault();

        let form = $("#addAddressForm")[0]; // Get form element

        if (!form.checkValidity()) {
            form.classList.add("was-validated"); // Apply Bootstrap validation
            return; // Stop submission if invalid
        }

        let formData = $("#addAddressForm").serialize(); // Serialize form fields
        let id = $("#addressId").val();
        let url = id ? "UpdateAddress.php" : "InsertAddress.php"; // Decide API

        $("#formLoader").show();  // Show Loader
        $("#submitBtn").prop("disabled", true); // Disable Button

        $.ajax({
            url: url, // Target PHP script
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                $("#formLoader").hide();  // Hide Loader
                $("#submitBtn").prop("disabled", false); // Enable Button

                if (response.success) {
                    $("#addAddressForm").removeClass("was-validated"); // Remove validation class
                    $("#staticBackdrop").modal("hide"); // Close modal
                    $("#addAddressForm")[0].reset(); // Reset form
                    $("#addressId").val(""); // Clear hidden ID field
                    table.ajax.reload(); // Refresh DataTable
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function () {
                $("#formLoader").hide();
                $("#submitBtn").prop("disabled", false);
                alert("Error occurred while processing request.");
            }
        });
    });

    $("#addAddressBtn").click(function (e) {
        e.preventDefault();

        loadCities(); // Load cities
        $("#modalTitle").text("Add New Address");
        $("#submitBtnText").text("Add");
        $("#addAddressForm")[0].reset();
        $("#addressId").val(""); // Ensure ID is cleared
        $("#addAddressForm").removeClass("was-validated"); // Ensure validations is cleared
        $("#staticBackdrop").modal("show");
    });

    // Load edit dialog via AJAX
    $(document).on("click", ".edit-btn", function (e) {
        e.preventDefault();
        $("#modalTitle").text("Edit Address"); // Change modal title
        $("#submitBtnText").text("Update"); // Change button text

        let id = $(this).data("id");
        let form = $("#addAddressForm")[0]; // Get form element
        form.classList.add("was-validated"); // Apply Bootstrap validation

        $.ajax({
            url: "FetchSingleAddress.php",
            type: "GET",
            data: { id: id },
            dataType: "json",
            success: function (response) {
                if (response.success) {

                    loadCities(response.data.city_id); // Load cities
                    $("#addressId").val(response.data.id);
                    $("#inputName").val(response.data.name);
                    $("#inputFirstName").val(response.data.first_name);
                    $("#inputEmail").val(response.data.email);
                    $("#inputAddress").val(response.data.street);
                    $("#inputCity").val(response.data.city_id);
                    $("#inputZip").val(response.data.zip_code);

                    $("#staticBackdrop").modal("show"); // Open modal
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function () {
                alert("Error while fetching data.");
            }
        });
    });

    // Delete Record via AJAX
    $(document).on("click", ".delete-btn", function (e) {
        e.preventDefault();

        let id = $(this).data("id");
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: "DeleteAddress.php", // Target PHP script
                type: "POST",
                data: { id: id },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $("#row_" + id).fadeOut();
                        table.ajax.reload(); // Refresh DataTable
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function () {
                    alert("Error occurred while processing request.");
                }
            });
        }
    });

    $('#staticBackdrop').on('hidden.bs.modal', function () {
        $("#addAddressForm")[0].reset();  // Reset form fields
        $("#addressId").val(""); // Clear hidden ID field
        $("#addAddressForm").removeClass("was-validated"); // Remove validation class
    });

    // Export in JSON file
    $("#downloadJsonBtn").click(function () {
        downloadFile("json");
    });

    // Export in XML file
    $("#downloadXmlBtn").click(function () {
        downloadFile("xml");
    });
});