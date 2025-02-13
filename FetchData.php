<?php

include_once __DIR__ . '/Classes/Services/AddressService.php';

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid request.");
    }

    $request = $_REQUEST;

    // Column index mapping for sorting
    $columns = ['id', 'name', 'first_name', 'email', 'street', 'zip_code', 'city_name'];

    // Initialize service
    $addressService = new AddressService();

    // Fetch total records count
    $response = $addressService->fetchCount();
    $totalRecords = isset($response['success']) && isset($response['data']) && $response['success'] ? $response['data'] : 0;
    $totalFiltered = $totalRecords;

    // Fetch filtered records count
    $seachParam = "";
    if (isset($request['search']) && isset($request['search']['value']) && !empty($request['search']['value'])) {
        $seachParam = $request['search']['value'];

        $response = $addressService->fetchCount($seachParam);
        $totalFiltered = isset($response['success']) && isset($response['data']) && $response['success'] ? $response['data'] : 0;
    }

    // Sorting
    $orderByColumn = 'id';
    $orderBy = 'DESC';
    if (isset($request['order']) && isset($request['order'][0]) && isset($request['order'][0]['column'])) {
        $orderByColumn = $columns[$request['order'][0]['column']];
        $orderBy = $request['order'][0]['dir'];
    }

    // Pagination
    $start = isset($request['start']) ? $request['start'] : 0;
    $length = isset($request['length']) ? $request['length'] : 50;

    // Fetch all entries
    $response = $addressService->getAll($seachParam, $orderByColumn, $orderBy, $start, $length);
    $result = isset($response['success']) && isset($response['data']) && $response['success'] ? $response['data'] : 0;

    $data = [];
    foreach ($result as $address) {
        $address['DT_RowId'] = "row_" . $address['id'];  // Assign unique row ID
        $address['actions'] = '
            <button class="btn btn-warning btn-sm edit-btn" data-id="' . $address['id'] . '">Edit</button>
            <button class="btn btn-danger btn-sm delete-btn" data-id="' . $address['id'] . '">Delete</button>
        ';
        $data[] = $address;
    }

    // Return JSON response
    $response = [
        "success" => TRUE,
        "draw" => intval($request['draw']),
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $totalFiltered,
        "data" => $data
    ];

} catch (Exception $e) {
    $response = [
        "success" => FALSE,
        "message" => $e->getMessage()
    ];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?>