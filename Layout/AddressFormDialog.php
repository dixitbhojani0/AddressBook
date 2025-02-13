<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalTitle">Add/Edit Address</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addAddressForm" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="inputName" class="form-label required">Name</label>
                            <input type="text" class="form-control" id="inputName" name="name" required>
                            <div class="invalid-feedback">
                                Please provide a valid username.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputFirstName" class="form-label required">First Name</label>
                            <input type="text" class="form-control" id="inputFirstName" name="first_name" required>
                            <div class="invalid-feedback">
                                Please provide a valid first name.
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="inputEmail" class="form-label required">Email</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" required>
                            <div class="invalid-feedback">
                                Please provide a valid email.
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="inputAddress" class="form-label required">Street</label>
                            <input type="text" class="form-control" id="inputAddress" name="street" required>
                            <div class="invalid-feedback">
                                Please provide a valid street.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputCity" class="form-label required">City</label>
                            <select class="form-control" id="inputCity" name="city" required>
                                <option value="">Select City</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a valid city.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputZip" class="form-label required">Zip Code</label>
                            <input type="text" class="form-control" id="inputZip" name="zip_code" required>
                            <div class="invalid-feedback">
                                Please provide a valid zip code.
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="addressId" name="id">

                    <!-- Loader -->
                    <div id="formLoader" class="text-center mt-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p>Processing...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa-solid fa-plus"></i>
                        <span class="pl-2" id="submitBtnText">Save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>