<?php

include_once __DIR__ . '/../DatabaseConnection.php';

class AddressService extends DatabaseConnection
{
    // Call parent constructor to establish DB connection
    public function __construct()
    {
        parent::__construct();
    }

    // Fetch a single address by ID
    public function fetchById($id): array
    {
        try {
            $stmt = $this->connection->prepare("SELECT a.id AS id, a.name AS name, a.first_name AS first_name, a.email AS email, a.street AS street, a.city_id AS city_id, a.zip_code AS zip_code
                    FROM Addresses AS a
                    LEFT JOIN Cities c ON a.city_id = c.id
                    WHERE a.id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($result) {
                $response = [
                    "success" => TRUE,
                    "data" => $result
                ];
            } else {
                throw new Exception("No record found.");
            }
        } catch (Exception $e) {
            $response = [
                "success" => FALSE,
                "message" => $e->getMessage()
            ];
        }

        return $response;
    }

    // Fetch all addresses (for DataTables)
    public function getAll($searchParam = "", $orderByColumn = "id", $orderBy = "DESC", $limitStart = 0, $offset = 50): array
    {
        $response = [
            "success" => false,
            "data" => []
        ];

        // Allowed columns for ordering
        $allowedColumns = ["id", "name", "first_name", "email", "street", "city_name", "zip_code"];
        $allowedOrder = ["ASC", "DESC"];

        // Validate column name
        if (!in_array($orderByColumn, $allowedColumns)) {
            $orderByColumn = "id";
        }

        // Validate sorting order
        if (!in_array(strtoupper($orderBy), $allowedOrder)) {
            $orderBy = "DESC";
        }

        try {
            // Base SQL query
            $sql = "SELECT a.id AS id, a.name AS name, a.first_name AS first_name, a.email AS email, a.street AS street, c.city_name AS city_name, a.zip_code AS zip_code
                    FROM Addresses AS a
                    LEFT JOIN Cities c ON a.city_id = c.id";

            // If search parameter exists, add a WHERE clause
            if (!empty($searchParam)) {
                $sql .= " WHERE CONCAT_WS(' ', a.id, a.name, a.first_name, a.email, a.street, c.city_name, a.zip_code ) LIKE ?";
            }

            // Add ORDER BY and LIMIT
            $sql .= " ORDER BY $orderByColumn $orderBy LIMIT ?, ?";

            $stmt = $this->connection->prepare($sql);

            // Bind parameters dynamically based on search condition
            if (!empty($searchParam)) {
                $searchValue = $this->connection->real_escape_string($searchParam);
                $searchWildcard = "%" . $searchValue . "%";
                $stmt->bind_param("sii", $searchWildcard, $limitStart, $offset);
            } else {
                $stmt->bind_param("ii", $limitStart, $offset);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $response["data"][] = $row;
            }

            $response["success"] = TRUE;
            $stmt->close();
        } catch (Exception $e) {
            $response["message"] = $e->getMessage();
        }

        return $response;
    }

    // Fetch count
    public function fetchCount($searchParam = NULL): array 
    {
        try {
            // Base SQL query
            $sql = "SELECT COUNT(*) AS count 
                    FROM Addresses AS a
                    LEFT JOIN Cities c ON a.city_id = c.id";

            // If search parameter exists, add a WHERE clause
            if (!empty($searchParam)) {
                $sql .= " WHERE CONCAT_WS(' ', a.name, a.first_name, a.email, a.street, c.city_name, a.zip_code) LIKE ?";
            }
            $stmt = $this->connection->prepare($sql);

            // Bind parameters dynamically based on search condition
            if (!empty($searchParam)) {
                $searchValue = $this->connection->real_escape_string($searchParam);
                $searchWildcard = "%" . $searchValue . "%";
                $stmt->bind_param("s", $searchWildcard);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if ($row) {
                $response = [
                    "success" => TRUE,
                    "data" => $row['count']
                ];
            } else {
                throw new Exception("No record found.");
            }
        } catch(Exception $e) {
            $response = [
                "success" => FALSE,
                "message" => $e->getMessage()
            ];
        }

        return $response;
    }

    // Add a new address
    public function add($name, $firstName, $email, $street, $cityId, $zipCode): array
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO Addresses (name, first_name, email, street, zip_code, city_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $name, $firstName, $email, $street, $zipCode, $cityId);

            if ($stmt->execute()) {
                $response = [
                    "success" => TRUE,
                    "id" => $stmt->insert_id
                ];
            } else {
                throw new Exception("Database error: " . $stmt->error);
            }
            $stmt->close();
        } catch (Exception $e) {
            $response = [
                "success" => FALSE,
                "message" => $e->getMessage()
            ];
        }

        return $response;
    }

    // Update an existing address
    public function update($id, $name, $firstName, $email, $street, $cityId, $zipCode): array
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Addresses SET name=?, first_name=?, email=?, street=?, zip_code=?, city_id=? WHERE id=?");
            $stmt->bind_param("sssssii", $name, $firstName, $email, $street, $zipCode, $cityId, $id);

            if ($stmt->execute()) {
                $response["success"] = TRUE;
                $response = [
                    "success" => TRUE,
                    "id" => $id
                ];
            } else {
                throw new Exception("Database error: " . $stmt->error);
            }
            $stmt->close();
        } catch (Exception $e) {
            $response = [
                "success" => FALSE,
                "message" => $e->getMessage()
            ];
        }

        return $response;
    }

    // Delete an address
    public function delete($id): array
    {
        try {
            // Base SQL query
            $stmt = $this->connection->prepare("DELETE FROM Addresses WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $response["success"] = TRUE;
            } else {
                throw new Exception("Database error: " . $stmt->error);
            }
            $stmt->close();
        } catch (Exception $e) {
            $response = [
                "success" => FALSE,
                "message" => $e->getMessage()
            ];
        }

        return $response;
    }

    // Fetch Cities
    public function fetchCities(): array
    {
        try {
            // Base SQL query
            $sql = "SELECT id, city_name FROM cities ORDER BY city_name ASC";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $response["data"][] = $row;
            }

            $response["success"] = TRUE;
            $stmt->close();

        } catch (Exception $e) {
            $response = [
                "success" => FALSE,
                "message" => $e->getMessage()
            ];
        }

        return $response;
    }
}
