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
            $response['productName'] = 'The field is required!';
        }
        elseif( empty($productCategory) ){
            $error = true;
            $response['error'] = true;
            $response['category'] = 'The field is required!';
        }
        else{
            $error = true;
            $response['error'] = true;
            $response['cost'] = 'The field is required!';
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
            'message' => 'Success',
            'status'  => true,
            'error'   => false,
        ]);

    }
}
productCreated();
