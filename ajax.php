<?php
function productCreated(){
    if($_POST['action'] == 'productCreated' ){
        $productName     = $_POST['productName'] ?? null;
        $productCategory = $_POST['category'] ?? null;
        $productPrice    = $_POST['cost'] ?? null;
        // echo "<pre>";
        // print_r($productCategory);
        // exit;
        $error = false;
        if( empty($productName) ){
            $error = true;
            $response['error'] = true;
            $response['productName'] = 'Product name is required!';
        }
        if( empty($productCategory) ){
            $error = true;
            $response['error'] = true;
            $response['category'] = 'Product category is required!';
        }
        if( empty($productPrice) ){
            $error = true;
            $response['error'] = true;
            $response['cost'] = 'Product price is required!';
        }

        if($error){
            echo json_encode($response);
            exit;
        }
        include 'databaseConnection.php';
        $mysqli = $db->dbConnect();

        $stmt = $mysqli->prepare("INSERT INTO products(product_name,category, product_price) VALUES (?, ?, ?)");
        // Bind the variables to the statement as parameters
        $stmt->bind_param("ssd", $productName, $productCategory, $productPrice);

        // Execute the statement
        $saveProduct = $stmt->execute();

        // Close the statement
        $stmt->close();

        echo json_encode([
            'message' => 'Successfully added.',
            'status'  => true,
            'error'   => false,
        ]);

    }
}
productCreated();
