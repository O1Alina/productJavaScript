<?php 
include 'include/header.php';
$products = dbProductConnection();
$carts = dbCartConnection();?>
<body>
    <div class='container'>
        <div class="row">
            <div class="col-6 mt-5">
               <h2>Add Product</h2>
               <form class="productForm">
                    <input type="hidden" name="action" value="productCreated">
                    <div class="success alert alert-success alert-dismissible fade show d-none" role="alert">
                        
                    </div>
                    <div class="error" style="color:red;"></div>
                    <div class="form-group row  mt-2">
                        <label for="productName" class="col-sm-4 col-form-label">Product name</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" name="productName" placeholder="Product name">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="productCategory" class="col-sm-4 col-form-label">Product category</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" name="category">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="productCost" class="col-sm-4 col-form-label">Cost</label>
                        <div class="col-sm-7">
                          <input type="number" class="form-control" name="cost">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <div class="col-sm-10">
                          <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-6 mt-5">
                <h2>Product List</h2>
                <table class="table border productList__JS">
                    <thead>
                        <tr>
                            <th class="border">Product</th>
                            <th class="border">Price</th>
                            <th class="border">Add to cart</th>
                            <th class="border">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($products)) : 
                            $count = 1;
                            foreach ($products as $product): ?>
                            <tr class="productListData_JS" id="alina--<?php echo $count;?>--<?php echo $product['product_id'];?>">
                                <td class="border productName_col"><?php echo $product['product_name']; ?></td>
                                <td class="border productPrice_col"><?php echo $product['product_price']; ?></td>
                                <td class="border">
                                    <a  href="#" 
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
                                <td>
                                    <a  href="javascript:void(0);" 
                                        class="btn btn-primary update-product-data" 
                                        data-product="<?php echo $product['product_id'];?>"
                                        data-product-name="<?php echo $product['product_name'];?>"
                                        data-product-price="<?php echo $product['product_price']; ?>"
                                        data-product-category="<?php echo $product['category'];?>"
                                     >
                                        Edit
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-danger"  data-product="<?php echo $product['product_id'];?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                </td>
                            </tr>
                            <?php 
                            $count++;
                            endforeach; 
                        else : ?>
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
                <h2>Cart items</h2>
                <div class="success-cart alert alert-success alert-dismissible fade show d-none" role="alert">
                
                </div>
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
                        <?php  $sum = 0;?>
                        <?php if( $carts && isset($carts) ):?>
                            <?php foreach( $carts as $cart ):
                                $sum += $cart['total_price'];
                                ?>
                               <tr>
                                   <td class="border"><?php echo $cart['product_name'] ?? '';?></td>
                                   <td class="border"><?php echo $cart['product_price'] ?? '';?></td>
                                   <td class="border"><?php echo $cart['quantity'] ?? '';?></td>
                                   <td class="border"><?php echo $cart['total_price'] ?? '';?></td>
                                   <td class="border">
                                        <a href="javascript:void(0);" data-product="<?php echo $cart['product_id'];?>" class="remove-item btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                        <a href="javascript:void(0);" 
                                            data-cart-id="<?php echo $cart['id'];?>" 
                                            data-quantity="<?php echo $cart['quantity'];?>" 
                                            data-product-price="<?php echo $cart['product_price'];?>" 
                                            class="edit-item btn btn-primary">Edit</a>
                                    </td>
                               </tr>
                             <?php endforeach;?>
                        <?php endif;?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="border">Grand Total</td>
                            <td colspan="4" class="border totalPrice"><?php echo $sum;?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Product Update Modal -->
    <div class="modal fade" id="productEditModal" tabindex="-1" role="dialog" aria-labelledby="productEditModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="productEditModalTitle">Update Product</h5>
          </div>
          <div class="modal-body">
            <form class="update-product">
                <div class="success alert alert-success alert-dismissible fade show d-none" role="alert"></div>
                <input type="hidden" name="product_id" value="">
                <input type="hidden" name="action" value="productUpdated">
                <div class="form-group row  mt-2">
                    <label for="productName" class="col-sm-4 col-form-label">Product name</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="productName" placeholder="Product name">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label for="productCategory" class="col-sm-4 col-form-label">Product category</label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" name="category">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <label for="productCost" class="col-sm-4 col-form-label">Product Cost</label>
                    <div class="col-sm-7">
                      <input type="number" class="form-control" name="cost">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-sm-10">
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Cart Quantity Update -->
    <div class="modal fade" id="productQuantity" tabindex="-1" role="dialog" aria-labelledby="productQuantityTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="productQuantityTitle">Update Cart</h5>
          </div>
          <div class="modal-body">
            <form class="update-cart">
                <div class="success alert alert-success alert-dismissible fade show d-none" role="alert"></div>
                <input type="hidden" name="cart_id" value="">
                <input type="hidden" name="productPrice" value="">
                <input type="hidden" name="action" value="cartUpdated">
                <div class="form-group row  mt-2">
                    <label for="cartQuantity" class="col-sm-4 col-form-label">Quantity</label>
                    <div class="col-sm-7">
                      <input type="number" class="form-control" name="productQuantity" placeholder="Product Quantity">
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-sm-10">
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
          </div>
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
        fetchDataAndDisplay(form);
    });

    function fetchDataAndDisplay(form) {
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
                    productHtml += '<tr>';
                    productHtml += '<td>' + value.product_name + '</td>';
                    productHtml += '<td>' + value.product_price + '</td>';
                    productHtml += '<td><a href="#" ' +
                                   'class="btn btn-primary add_to_cart_JS" ' +
                                   'data-product="' + value.product_id + '" ' +
                                   'data-quantity="0">' +
                                   'Add to cart' +
                                   '</a></td>';
                    
                    productHtml += '<td>';
                    productHtml += '<a href="#" ' +
                                   'data-product="' + value.product_id + '" ' +
                                   'data-product-name="' + value.product_name + '" ' +
                                   'data-product-price="' + value.product_price + '" ' +
                                   'data-product-category="' + value.category + '" ' +
                                   'class="update-product-data btn btn-primary">' +
                                   'Edit' +
                                   '</a> ';
                    productHtml += '<a href="#" ' +
                                   'data-product="' + value.product_id + '" ' +
                                   'class="remove-item btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this item?\');">' +
                                   'Delete' +
                                   '</a>';
                    productHtml += '</td>';
                    
                    productHtml += '</tr>';
                });

                console.log(productHtml);
                document.querySelector('.productList__JS tbody').innerHTML = productHtml;
                document.querySelector('.success').classList.remove('d-none');
                document.querySelector('.success').innerHTML = data.message;
                updateProductData();
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
                    document.querySelector('tfoot td.totalPrice').innerHTML = data.totalPrice;
                    updateCart();
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
                    document.querySelector('tfoot td.totalPrice').innerHTML = data.totalPrice;
                    removeItem();
                    updateCart();
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
                    document.querySelector('.success-cart').classList.remove('d-none');
                    document.querySelector('.success-cart').innerHTML = data.message;
                    document.querySelector('.product-info tbody').innerHTML = data.html;
                    document.querySelector('tfoot td.totalPrice').innerHTML = data.totalPrice;
                    removeItem();
                })
                .catch(error => {
                    console.log('An error occurred');
                });
            });
        });
    }
    removeItem();

    function updateProductData() {
        document.querySelectorAll('a.update-product-data').forEach(function(element){
            element.addEventListener('click',(event) => {
                event.preventDefault();
                var productID = event.currentTarget.getAttribute('data-product');
                var productName = event.currentTarget.getAttribute('data-product-name');
                var productCategory = event.currentTarget.getAttribute('data-product-category');
                var productPrice = event.currentTarget.getAttribute('data-product-price');
                document.querySelector('form.update-product input[name=product_id]').value = productID;
                document.querySelector('form.update-product input[name=productName]').value = productName;
                document.querySelector('form.update-product input[name=category]').value = productCategory;
                document.querySelector('form.update-product input[name=cost]').value = productPrice;
                var myModal = new bootstrap.Modal(document.getElementById('productEditModal'), {
                    keyboard: false
                });
                myModal.show();
            });
        });

        var productUpdateForm = document.querySelector('.update-product');
        productUpdateForm.addEventListener('submit', function(evt) {
            evt.preventDefault();
            fetchDataAndDisplay(productUpdateForm);
            location.reload();
        });
    }
    updateProductData();
    function updateCart() {
        document.querySelectorAll('a.edit-item').forEach(function(element){
            element.addEventListener('click',(event) => {
                event.preventDefault();
                var cartID = event.currentTarget.getAttribute('data-cart-id');
                var productQuantity = event.currentTarget.getAttribute('data-quantity');
                var productPrice = event.currentTarget.getAttribute('data-product-price');
                document.querySelector('form.update-cart input[name=cart_id]').value = cartID;
                document.querySelector('form.update-cart input[name=productQuantity]').value = productQuantity;
                document.querySelector('form.update-cart input[name=productPrice]').value = productPrice;
                var myModal = new bootstrap.Modal(document.getElementById('productQuantity'), {
                    keyboard: false
                });
                myModal.show();
            });
        });

        var cartUpdateForm = document.querySelector('.update-cart');
        cartUpdateForm.addEventListener('submit', function(evt) {
            evt.preventDefault();
            updateCartQuantity(cartUpdateForm);
            // location.reload();
        });
    }

    updateCart();

    function updateCartQuantity(form) {
        var formData = new FormData(form);          //inbuilt JS form object
        // Fetching data from the API
        fetch('cart-quantity-update.php',{
            method: 'POST',
            body: formData
        })
        .then(response => {                               //nested then (promises)
            return response.json();
        })
        .then(data => {
            console.log(data);
            location.reload();
        })
        .catch(error => {
            console.log('An error occurred');
        });
    }
</script>
</body>
</html>
