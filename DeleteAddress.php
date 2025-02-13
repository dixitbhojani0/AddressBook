<?php

include_once __DIR__ . '/Classes/Services/AddressService.php';

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['id'])) {
        throw new Exception("Invalid request.");
    }

    // Validate and sanitize input
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

    // Initialize service
    $addressService = new AddressService();

    // Delete Address
    $response = $addressService->delete($id);

} catch (Exception $e) {
    $response = [
        "success" => FALSE,
        "message" => $e->getMessage()
    ];
}

echo json_encode($response);

?>