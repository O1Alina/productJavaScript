<?php 
include 'include/header.php';
$products = dbProductConnection();
$carts = dbCartConnection();?>
<body>
    <div class='container'>
        <div class="row">
            <div class="col-6 mt-5">
                <form class="productForm">
                    <div class="success" style="color:green;"></div>
                    <div class="error" style="color:red;"></div>
                    <input type="hidden" name="action" value="productCreated">     
                    Product name : <input type="text" name="productName"> <br><br>
                    Product category: <input type="text" name="category"> <br><br>
                    Cost: <input type="number" name="cost"> <br><br>
                    <button class="btn btn-primary" type="submit" id="save">Save</button>
                </form>
            </div>
            <div class="col-6 mt-5">
            <!-- Displays a list of products retrieved from the database using a function dbProductConnection() -->
                <table class="table border productList__JS">
                    <thead>
                        <tr>
                            <th class="border">Product</th>
                            <th class="border">Price</th>
                            <th class="border">Add to cart</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($products)) : 
                            $count = 1;?>
                            <?php foreach ($products as $product) : ?>
                            <tr class="productListData_JS" id="alina--<?php echo $count;?>--<?php echo $product['product_id'];?>">
                                <td class="border productName_col"><?php echo $product['product_name']; ?></td>
                                <td class="border productPrice_col"><?php echo $product['product_price']; ?></td>
                                <td class="border">
                                    <a href="#" 
                                        class="btn btn-primary add_to_cart_JS" 
                                        data-product="<?php echo $product['product_id'];?>"
                                        data-product-name="<?php echo $product['product_name']; ?>"
                                        data-product-price="<?php echo $product['product_price']; ?>"
                                        data-product-click="false"
                                        data-product-count="<?php echo $count;?>"
                                        data-quantity="0">
                                        Add to cart
                                    </a>
                                </td>
                            </tr>
                            <?php 
                            $count++;
                            endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3" class="border">No products available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <a href="#" class="btn btn-primary add_to_cart_all">
                    Add To Cart All
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col mt-5">
                <table class="table border product-info">
                    <thead>
                        <tr>
                            <th class="border">Product</th>
                            <th class="border">Price</th>
                            <th class="border">Quantity</th>
                            <th class="border">Total</th>
                            <th class="border">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if( $carts && isset($carts) ):?>
                            <?php foreach( $carts as $cart ):?>
                               <tr>
                                   <td><?php echo $cart['product_name'] ?? '';?></td>
                                   <td><?php echo $cart['product_price'] ?? '';?></td>
                                   <td><?php echo $cart['quantity'] ?? '';?></td>
                                   <td><?php echo $cart['total_price'] ?? '';?></td>
                                   <td><a href="#" data-product="<?php echo $cart['product_id'];?>" class="remove-item btn btn-danger">Delete</a></td>
                               </tr>
                             <?php endforeach;?>
                        <?php endif;?>
                    </tbody>
                    <tfoot>
                        
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php include 'include/footer.php'; ?>
<script>
    // form submission is captured by the submit event listener, 
    //which prevents the default action and calls the fetchDataAndDisplay function.
    var form = document.querySelector('.productForm');
    form.addEventListener('submit', function(evt) {
        evt.preventDefault();
        // Calling the function to execute
        fetchDataAndDisplay();
    });

    function fetchDataAndDisplay() {
        var formData = new FormData(form);          //inbuilt JS form object
        // Fetching data from the API
        fetch('product-add-ajax.php',{
            method: 'POST',
            body: formData
        })
        .then(response => {                               //nested then (promises)
            return response.json();
        })
        .then(data => {
            var error = data.error;
            if( error ){
                var html = '';
                var productNameError = data.productName;
                var categoryError = data.category;
                var costError = data.cost;
                if( typeof(productNameError) != "undefined" ){
                    html += productNameError
                }
                if( typeof(categoryError) != 'undefined' ){
                    html += '<br>' + categoryError + '<br>';
                }
                if( typeof(costError) != 'undefined' ){
                    html += '<br>' + costError;
                }
                document.querySelector('.error').innerHTML = html
            } else{
                var productHtml = '';
                data.response.forEach(function (value) {
                    productHtml += '<tr>'
                    productHtml += '<td>' +value.product_name+ '</td>'
                    productHtml += '<td>' +value.product_price+ '</td>'
                    productHtml += '<td><a href="#" ' +
                                    'class="btn btn-primary add_to_cart_JS" ' +
                                    'data-product="' + value.product_id + '" ' +
                                    'data-quantity="0">' +
                                    'Add to cart' +
                                    '</a></td>';
                    productHtml += '<td> </td>'
                    productHtml += '</tr>'
                });
                document.querySelector('.productList__JS tbody').innerHTML = productHtml;
                document.querySelector('.success').innerHTML = data.message;
                form.reset();
            }
        
        })
        .catch(error => {
            console.log('An error occurred');
        });
    }
    function addToCart(){
        document.querySelectorAll('a.add_to_cart_JS').forEach(function(element){
            element.addEventListener('click',(event) => {
                event.preventDefault();
                var productID = event.currentTarget.getAttribute('data-product');
                var productQuantity = event.currentTarget.getAttribute('data-quantity');
                productQuantity++;
                fetch('cart-add-ajax.php?productID='+encodeURIComponent(productID)+'&quantity='+encodeURIComponent(productQuantity),{
                    method: 'GET',
                })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    document.querySelector('.product-info tbody').innerHTML = data.html;
                    removeItem();
                })
                .catch(error => {
                    console.log('An error occurred');
                });
            });
        });
    }
    
    addToCart();
 
    function addToCartAll(){
        document.querySelectorAll('a.add_to_cart_all').forEach(function(element){
            element.addEventListener('click',(event) => {
                event.preventDefault();
                fetch('cart-add-all-ajax.php',{
                    method: 'GET',
                })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    document.querySelector('.product-info tbody').innerHTML = data.html;
                    removeItem();
                })
                .catch(error => {
                    console.log('An error occurred');
                });
            });
        });
    }
    addToCartAll();

    function removeItem(){
        document.querySelectorAll('a.remove-item').forEach(function(element){
            element.addEventListener('click',(event) => {
                event.preventDefault();
                var productID = event.currentTarget.getAttribute('data-product');
                fetch('cart-delete-ajax.php?productID='+encodeURIComponent(productID),{
                    method: 'GET',
                })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    document.querySelector('.success').innerHTML = data.message;
                    document.querySelector('.product-info tbody').innerHTML = data.html;
                    removeItem();
                })
                .catch(error => {
                    console.log('An error occurred');
                });
            });
        });
    }
    removeItem();
</script>
</body>
</html>
