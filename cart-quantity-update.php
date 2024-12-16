<?php
function productAddToCart() {
    // Retrieve inputs and validate them
    $cartId          = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    $productQuantity = isset($_POST['productQuantity']) ? intval($_POST['productQuantity']) : 0;
    $productPrice    = isset($_POST['productPrice']) ? intval($_POST['productPrice']) : 0;
    $totalPrice      = $productPrice * $productQuantity;
    // Check if inputs are valid
    if ($cartId <= 0 || $productQuantity < 0) {
        echo json_encode([
            'message' => 'Invalid input',
            'success' => false,
        ]);
        return;
    }

    include_once 'databaseConnection.php'; 

    try {
        // Initialize database connection
        $db = new \DB\DBConnection(); // Use namespace
        $mysqli = $db->dbConnect();

        // Prepare the SQL query
        $stmt = $mysqli->prepare("UPDATE carts SET quantity = ?,total_price = ? WHERE id = ?");
        if (!$stmt) {
            throw new Exception('Failed to prepare statement: ' . $mysqli->error);
        }

        // Bind parameters and execute
        $stmt->bind_param("idi", $productQuantity, $totalPrice, $cartId);
        $saveProduct = $stmt->execute();

        // Check if the update was successful
        if ($saveProduct && $stmt->affected_rows > 0) {
            echo json_encode([
                'message' => 'Quantity updated successfully',
                'success' => true,
            ]);
        } else {
            echo json_encode([
                'message' => 'No rows updated. Check if the cart ID exists.',
                'success' => false,
            ]);
        }

        // Close the statement
        $stmt->close();

    } catch (Exception $e) {
        // Handle exceptions and send error response
        echo json_encode([
            'message' => 'Error: ' . $e->getMessage(),
            'success' => false,
        ]);
    }
}

productAddToCart();
