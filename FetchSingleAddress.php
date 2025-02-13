<?php

include_once __DIR__ . '/Classes/Services/AddressService.php';

try {
    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        throw new Exception("Invalid request.");
    }

    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    
    if (!empty($id)) {
        // Initialize service
        $addressService = new AddressService();

        // Fetch single address
        $response = $addressService->fetchById($_GET['id']);
    }

} catch (Exception $e) {
    $response = [
        "success" => FALSE,
        "message" => $e->getMessage()
    ];
}

echo json_encode($response);
?>
