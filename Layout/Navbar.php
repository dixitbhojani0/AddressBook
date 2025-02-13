<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top bg-body-tertiary">
    <div class="container-fluid navbar-container">
        <div class="navbar-brand">
            <i class="fa-solid fa-address-book"></i>
            <span class="pl-2">Address Book</span>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="me-auto"></div>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-outline-primary add-address-btn" id="addAddressBtn">
                <i class="fa-solid fa-user-plus"></i>
                <span class="pl-2">Add Address</span>
            </button>
            <button id="downloadJsonBtn" class="btn btn-outline-primary download-json-btn ml-2">
                <i class="fa-solid fa-download"></i> 
                <span class="pl-2">Download JSON</span>
            </button>
            <button id="downloadXmlBtn" class="btn btn-outline-secondary download-xml-btn ml-2">
                <i class="fa-solid fa-download"></i> 
                <span class="pl-2">Download XML</span>
            </button>
        </div>
    </div>
</nav>