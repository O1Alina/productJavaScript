<?php

function dbProductConnection(){

     include 'databaseConnection.php';
     $mysqli = $db->dbConnect();
     // $query1 = mysql_query("select * from product", $connection);
    $productlist = [];
    $result      = $mysqli->query("SELECT * FROM products");
   
    while($row = mysqli_fetch_assoc($result) ) {
        $productlist[] = [
            'product_name'  => $row['product_name'],
            'product_id'    => $row['product_id'],
            'category'      => $row['category'],
            'product_price' => $row['product_price']
        ];
    }

    return $productlist;
}
