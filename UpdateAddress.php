<?php

include_once __DIR__ . '/Classes/Services/AddressService.php';

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['id'])) {
        throw new Exception("Invalid request.");
    }

    // Validate and sanitize input
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $name = trim($_POST['name']);
    $firstName = trim($_POST['first_name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $street = trim($_POST['street']);
    $zipCode = trim($_POST['zip_code']);
    $cityId = ctype_digit($_POST['city']) ? $_POST['city'] : NULL;

    // Check required fields
    if (empty($id) || empty($name) || empty($firstName) || empty($email) || empty($street) || empty($zipCode) || empty($cityId)) {
        throw new Exception("All fields are required and must be valid.");
    }
    
    // Initialize service
    $addressService = new AddressService();

    // Update address
    $response = $addressService->update($id, $name, $firstName, $email, $street, $cityId, $zipCode);

} catch (Exception $e) {
    $response = [
        "success" => FALSE,
        "message" => $e->getMessage()
    ];
}

echo json_encode($response);
?>
