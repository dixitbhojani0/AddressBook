<?php

include_once __DIR__ . '/Classes/Services/AddressService.php';

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid request.");
    }

    // Get form data & Sanitize inputs
    $name = trim($_POST['name']);
    $firstName = trim($_POST['first_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $street = trim($_POST['street']);
    $cityId = ctype_digit($_POST['city']) ? $_POST['city'] : NULL;
    $zipCode = trim($_POST['zip_code']);
    
    // Check required fields
    if (empty($name) || empty($firstName) || empty($email) || empty($street) || empty($zipCode) || empty($cityId)) {
        throw new Exception("All fields are required and must be valid.");
    }

    // Initialize service
    $addressService = new AddressService();

    // Insert address
    $response = $addressService->add($name, $firstName, $email, $street, $cityId, $zipCode);

} catch (Exception $e) {
    $response = [
        "success" => FALSE,
        "message" => $e->getMessage()
    ];
}

echo json_encode($response);

?>
