<?php

include_once __DIR__ . '/Classes/Services/AddressService.php';

try {
    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        throw new Exception("Invalid request.");
    }

    // Initialize service
    $addressService = new AddressService();

    // Fetch single address
    $response = $addressService->fetchCities();

} catch (Exception $e) {
    $response = [
        "success" => FALSE,
        "message" => $e->getMessage()
    ];
}

echo json_encode($response);
?>
