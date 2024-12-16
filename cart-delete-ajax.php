<?php
function deleteCartItem() {
    include_once 'databaseConnection.php'; 
     $db = new \DB\DBConnection(); // Use namespace
    $mysqli = $db->dbConnect();
    $productID = isset($_GET['productID']) ? intval($_GET['productID']) : 0;

    if ($productID > 0) { // Ensure productID is valid
        $sql  = "DELETE FROM carts WHERE product_id = ?";
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $productID);
            if ($stmt->execute()) { // Execute the query
                $productlist = []; // Initialize an array to store all cart items
                $result = $mysqli->query("SELECT * FROM carts");
                if ($result) {
                    while ($cart = $result->fetch_assoc()) { // Loop through each row in the result set
                        $productSql     = "SELECT * FROM products WHERE product_id = ?";
                        $productstmt    = $mysqli->prepare($productSql);
                        $productstmt->bind_param("i", $cart['product_id']); // "i" denotes integer type
                        $productstmt->execute();
                        $productResult = $productstmt->get_result();
                        $productRow    = $productResult->fetch_assoc();

                        if ($productRow) {
                            // Add the product and cart details to the list
                            $productlist[] = [
                                'id'            => $cart['id'],
                                'product_id'    => $productRow['product_id'],
                                'product_name'  => $productRow['product_name'],
                                'product_price' => $productRow['product_price'],
                                'quantity'      => $cart['quantity'],
                                'total_price'   => $cart['total_price']
                            ];
                        }
                    }
                }

                $html = '';
                $totalPrice = 0; 
                if( isset($productlist) && is_array($productlist) ){
                    foreach ($productlist as $key => $cart) {
                        $productSql     = "SELECT * FROM products WHERE product_id = ?";
                        $productstmt    = $mysqli->prepare($productSql);
                        $productstmt->bind_param("i", $cart['product_id']); // "i" denotes integer type
                        $productstmt->execute();
                        $result         = $productstmt->get_result();
                        $productRow     = $result->fetch_assoc();
                        $totalPrice    += $cart['total_price'];
                        $html .='<tr>';
                            $html .= '<td class="border" >'.$productRow['product_name'].'</td>';
                            $html .= '<td class="border" >'.$productRow['product_price'].'</td>';
                            $html .= '<td class="border" >'.$cart['quantity'].'</td>';
                            $html .= '<td class="border totalPrice">'.$cart['total_price'].'</td>';
                            $html .= '<td>
                            <a href="#" data-product="'.$cart['product_id'].'" class="remove-item btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</a>
                             <a href="javascript:void(0);" 
                                            data-cart-id="'.$cart['id'].'" 
                                            data-quantity="'.$cart['quantity'].'" 
                                            data-product-price="'.$cart['product_price'].'" 
                                            class="edit-item btn btn-primary">Edit</a>
                            </td>';
                        $html .='</tr>';
                    }
                }

                echo json_encode([
                    'status'  => 'success',
                    'html'    => $html,
                    'totalPrice' => $totalPrice,
                    'message' => 'Item Deleted Successfully!'
                ]);
            } else {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Failed to delete item.'
                ]);
            }
        } else {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Failed to prepare the query.'
            ]);
        }
    } else {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Invalid product ID.'
        ]);
    }
}

deleteCartItem();
