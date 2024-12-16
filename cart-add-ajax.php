<?php
function productAddToCart(){
    include_once 'databaseConnection.php'; 
    $db = new \DB\DBConnection(); // Use namespace
    $mysqli = $db->dbConnect();
    $productID       = isset($_GET['productID']) ? intval($_GET['productID']) : 0;
    $productQuantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 0;
    $sql             = "SELECT * FROM carts WHERE product_id = ?";
    $stmt            = $mysqli->prepare($sql);
    $stmt->bind_param("i", $productID); // "i" denotes integer type
    $stmt->execute();
    $result         = $stmt->get_result();
    $row            = $result->fetch_assoc();
    $cartData       = [];

    $productData    = "SELECT * FROM products WHERE product_id = ?";
    $productDatatmt = $mysqli->prepare($productData);
    $productDatatmt->bind_param("i", $productID); // "i" denotes integer type
    $productDatatmt->execute();

    $productResult  = $productDatatmt->get_result();
    $productRRow    = $productResult->fetch_assoc();
    $productPrice   = $productRRow['product_price'] ?? 0;
    $totalPrice     = $productPrice * $productQuantity;

    if (isset($row) && !empty($row)) {
        $stmt = $mysqli->prepare("UPDATE carts SET quantity = quantity + ?, total_price = total_price + ? WHERE product_id = ?");
        $stmt->bind_param("idi", $productQuantity, $totalPrice, $productID);
        $updateProduct = $stmt->execute();
    } else {
        $stmt = $mysqli->prepare("INSERT INTO carts(product_id, quantity, total_price) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $productID, $productQuantity, $productPrice);
        $saveProduct = $stmt->execute();
    }

    $allCarts = $mysqli->query("SELECT * FROM carts");
    if ($allCarts->num_rows > 0) {
        while($row = $allCarts->fetch_assoc()) {
            $cartData[] = $row;
        }
    }
    $html = '';
    $totalPrice = 0;
    if( isset($cartData) && is_array($cartData) ){
        foreach ($cartData as $key => $cart) {
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
                            <a href="#" data-product="'.$cart['product_id'].'" class="remove-item btn btn-danger"  onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</a>
                            <a href="javascript:void(0);" 
                                            data-cart-id="'.$cart['id'].'" 
                                            data-quantity="'.$cart['quantity'].'" 
                                            data-product-price="'.$productRow['product_price'].'" 
                                            class="edit-item btn btn-primary">Edit</a>
                          </td>';
            $html .='</tr>';
        }
    }

    echo json_encode([
        'html'          => $html,
        'totalPrice'    => $totalPrice
    ]);
}
productAddToCart();
