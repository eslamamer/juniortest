<?php
// Set the header to return JSON response
header('Content-Type: application/json');

require_once './classes/ProductManager.php';
$mgr = new ProductManager();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Parse input JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $data = [];
        $errors = [];

        // SKU Validation
        if (!empty(trim($input['sku'] ?? ''))) {
            if (!$mgr->isSkuExist($input['sku'])) {
                $data['sku'] = filter_var($input['sku'], FILTER_SANITIZE_SPECIAL_CHARS);
            } else {
                $errors[] = 'SKU already exists';
            }
        } else {
            $errors[] = 'SKU cannot be empty';
        }

        // Name Validation
        if (!empty(trim($input['name'] ?? ''))) {
            $data['name'] = filter_var($input['name'], FILTER_SANITIZE_SPECIAL_CHARS);
        } else {
            $errors[] = 'Name cannot be empty';
        }

        // Price Validation
        if (!empty(trim($input['price'] ?? ''))) {
            if (is_numeric($input['price']) && $input['price'] >= 0) {
                $data['price'] = filter_var($input['price'], FILTER_SANITIZE_SPECIAL_CHARS);
            } else {
                $errors[] = 'Price must be a positive number';
            }
        } else {
            $errors[] = 'Price cannot be empty';
        }

        // Type Validation
        if (!empty($input['type'] ?? '') && $input['type'] > 0) {
            $data['type'] = filter_var($input['type'], FILTER_SANITIZE_SPECIAL_CHARS);
        } else {
            $errors[] = 'Select a product type';
        }

        // Visible Inputs Validation
        if (isset($input['visibleInputs'])) {
            $visibleInputs = json_decode($input['visibleInputs'], true);
            if (is_array($visibleInputs)) {
                foreach ($visibleInputs as $visibleInput) {
                    $mgr->validateAttr($visibleInput, $input, $data, $errors);
                }
            }
        }

        // Check for errors or save data
        if (empty($errors)) {
            $saved = $mgr->save($data);
            if ($saved) {
                echo json_encode(['success' => true, 'data' => $data]);
            } else {
                throw new Exception("Unable to save product");
            }
        } else {
            echo json_encode(['success' => false, 'errors' => $errors]);
        }
    } catch (Exception $e) {
        // Log the error and return a response
        error_log("Unexpected error: " . $e->getMessage());
        echo json_encode(['success' => false, 'errors' => ['An unexpected error occurred']]);
     }
}
?>
