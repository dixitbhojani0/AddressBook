<?php
include_once __DIR__ . '/Classes/Services/AddressService.php';

try {
    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        throw new Exception("Invalid request.");
    }

    if (!isset($_GET['format']) || !in_array($_GET['format'], ['json', 'xml'])) {
        throw new Exception("Invalid format. Use 'json' or 'xml'.");
    }

    $format = $_GET['format'];

    // Initialize service
    $addressService = new AddressService();

    // Fetch total records count
    $response = $addressService->fetchCount();
    $totalRecords = isset($response['success']) && isset($response['data']) && $response['success'] ? $response['data'] : 0;

    // Fetch All addresses
    $response = $addressService->getAll("", "id", "ASC", 0, $totalRecords);
    $addresses = isset($response['data']) ? $response['data'] : [];

    if (empty($addresses)) {
        throw new Exception("No addresses found.");
    }

    if ($format === "json") {
        // Send JSON response
        echo json_encode($addresses, JSON_PRETTY_PRINT);
    } else {
        // XML Export
        $xml = new SimpleXMLElement('<addresses/>');
        foreach ($addresses as $address) {
            $addressNode = $xml->addChild('address');
            $addressNode->addChild('id', $address['id']);
            $addressNode->addChild('name', htmlspecialchars($address['name'] ?? '', ENT_QUOTES, 'UTF-8'));
            $addressNode->addChild('first_name', htmlspecialchars($address['first_name'] ?? '', ENT_QUOTES, 'UTF-8'));
            $addressNode->addChild('email', htmlspecialchars($address['email'] ?? '', ENT_QUOTES, 'UTF-8'));
            $addressNode->addChild('street', htmlspecialchars($address['street'] ?? '', ENT_QUOTES, 'UTF-8'));
            $addressNode->addChild('zip_code', $address['zip_code'] ?? '');
            $addressNode->addChild('city_name', htmlspecialchars($address['city_name'] ?? '', ENT_QUOTES, 'UTF-8'));    
        }

        // Convert to DOMDocument for pretty print
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        // Output XML with proper headers
        echo $dom->saveXML();
    }
    exit;
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
