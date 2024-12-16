<?php
function allProductAddToCart() {
    include_once 'databaseConnection.php'; 
    $db     = new \DB\DBConnection(); // Use namespace
    $mysqli = $db->dbConnect();

    $productlist = [];
    $result      = $mysqli->query("SELECT * FROM products");

    while ($row = $result->fetch_assoc()) {
        $productID       = $row['product_id'];
        $productPrice    = $row['product_price']; // Ensure product price is fetched
        
        $sql             = "SELECT * FROM carts WHERE product_id = ?";
        $stmt            = $mysqli->prepare($sql);
        $stmt->bind_param("i", $productID); // "i" denotes integer type
        $stmt->execute();
        $resultCart      = $stmt->get_result();
        $cartRow         = $resultCart->fetch_assoc();

        if ($cartRow) { // If product already exists in the cart
            $updatedQuantity = $cartRow['quantity'] + 1; // Assuming adding one more item
            $updatedPrice    = $cartRow['total_price'] + $productPrice;

            $stmt = $mysqli->prepare("UPDATE carts SET quantity = ?, total_price = ? WHERE product_id = ?");
            $stmt->bind_param("idi", $updatedQuantity, $updatedPrice, $productID);
            $stmt->execute();
        } else { // Insert new product into the cart
            $quantity = 1; // Default quantity to 1 for a new product
            $totalPrice = $productPrice;

            $stmt = $mysqli->prepare("INSERT INTO carts (product_id, quantity, total_price) VALUES (?, ?, ?)");
            $stmt->bind_param("iid", $productID, $quantity, $totalPrice);
            $stmt->execute();
        }

        // Fetch updated cart details
        $stmt = $mysqli->prepare("SELECT * FROM carts WHERE product_id = ?");
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $resultCart = $stmt->get_result();
        $updatedCartRow = $resultCart->fetch_assoc();

        $productlist[] = [
            'id'            => $updatedCartRow['id'],
            'product_name'  => $row['product_name'],
            'product_price' => $row['product_price'],
            'quantity'      => $updatedCartRow['quantity'],
            'total_price'   => $updatedCartRow['total_price'],
            'product_id'    => $row['product_id']
        ];
    }

    $html = '';
    $totalPrice = 0;
    if (!empty($productlist)) {
        foreach ($productlist as $cart) {
            $totalPrice    += $cart['total_price'];
            $html .= '<tr>';
            $html .= '<td class="border">' . htmlspecialchars($cart['product_name']) . '</td>';
            $html .= '<td class="border">' . htmlspecialchars($cart['product_price']) . '</td>';
            $html .= '<td class="border">' . htmlspecialchars($cart['quantity']) . '</td>';
            $html .= '<td class="border totalPrice">' . htmlspecialchars($cart['total_price']) . '</td>';
             $html .= '<td>
                        <a href="#" data-product="'.$cart['product_id'].'" class="remove-item btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</a>
                        <a href="javascript:void(0);" 
                                            data-cart-id="'.$cart['id'].'" 
                                            data-quantity="'.$cart['quantity'].'" 
                                            data-product-price="'.$cart['product_price'].'" 
                                            class="edit-item btn btn-primary">Edit</a>
                        </td>';
            $html .= '</tr>';
        }
    }

    echo json_encode([
        'html'          => $html,
        'totalPrice'    => $totalPrice
    ]);
}


allProductAddToCart();
