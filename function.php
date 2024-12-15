<?php

function dbProductConnection() {
    include_once 'databaseConnection.php'; 
    $db = new \DB\DBConnection(); // Use namespace
    $mysqli = $db->dbConnect();
    $productlist = [];
    $result = $mysqli->query("SELECT * FROM products");
    while ($row = mysqli_fetch_assoc($result)) {
        $productlist[] = [
            'product_name'  => $row['product_name'],
            'product_id'    => $row['product_id'],
            'category'      => $row['category'],
            'product_price' => $row['product_price']
        ];
    }
    return $productlist;
}

function dbCartConnection() {
    include_once 'databaseConnection.php'; 
    $db = new \DB\DBConnection(); // Use namespace
    $mysqli = $db->dbConnect();
    $productlist = []; // Initialize an array to store all cart items
    $result = $mysqli->query("SELECT * FROM carts");
    if ($result) {
        while ($cart = $result->fetch_assoc()) { // Loop through each row in the result set
            $productSql = "SELECT * FROM products WHERE product_id = ?";
            $productstmt = $mysqli->prepare($productSql);
            $productstmt->bind_param("i", $cart['product_id']); // "i" denotes integer type
            $productstmt->execute();
            $productResult = $productstmt->get_result();
            $productRow = $productResult->fetch_assoc();

            if ($productRow) {
                // Add the product and cart details to the list
                $productlist[] = [
                    'product_id'    => $productRow['product_id'],
                    'product_name'  => $productRow['product_name'],
                    'product_price' => $productRow['product_price'],
                    'quantity'      => $cart['quantity'],
                    'total_price'   => $cart['total_price']
                ];
            }
        }
    } else {
        die("Error fetching carts: " . $mysqli->error); // Handle query error
    }
    return $productlist;
}
