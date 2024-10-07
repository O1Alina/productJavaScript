<?php 
include 'include/header.php';
$products = dbProductConnection();?>
<body>
<div class='container'>
    <div class="row">
        <div class="col-6 mt-5">
            <!-- When the form is submitted, it triggers a JavaScript function (fetchDataAndDisplay) 
            that sends the form data to ajax.php using the Fetch API (AJAX request). -->
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
            <table class="table border">
                <tr>
                    <th class="border">Product</th>
                    <th class="border">Price</th>
                    <th class="border">Add to cart</th>
                    <th class="border">Quanties</th>
                </tr>
                <form method="post" action="add_to_cart.php" class="product_JS">

                <input type="hidden" name="product_id" value="">
                <input type="hidden" name="product_quantity" value="">
                    <div class="productList__JS">
                        <?php 
                        if (!empty($products)) : 
                            $count = 1;?>
                            <?php foreach ($products as $product) : ?>
                            <tr class="productListData_JS">
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
                                <td class="border productQuantity_col" data-cart=""></td>
                            </tr>
                            <?php 
                            $count++;
                            endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3" class="border">No products available.</td>
                            </tr>
                        <?php endif; ?>
                    </div>
                </form>
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
                    </tr>
                </thead>
                <tbody>

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

            //fetchDataAndDisplay() function sends an AJAX POST request to ajax.php with the form data.

            var formData = new FormData(form);          //inbuilt JS form object
        
            // Fetching data from the API
            fetch('ajax.php',{
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
                        console.log(value);
                        console.log(value.product_id);
                        console.log(value.product_name);
                        console.log(value.product_price);
                    });
                    document.querySelector('.success').innerHTML = data.message;
                    console.log(data.message);

                    console.log(data.response);
                    form.reset();
                }
            
            })
            .catch(error => {
                console.log('An error occurred');
            });
        }
        //Handle form submission and "Add to cart" actions dynamically using JavaScript.
        var count = 0;
        document.querySelectorAll('a.add_to_cart_JS').forEach(function(element){
            element.addEventListener('click',(event) => {
                count++;
                event.preventDefault();
                var productID = event.currentTarget.getAttribute('data-product');
                var productName = event.currentTarget.getAttribute('data-product-name');
                var productPrice = event.currentTarget.getAttribute('data-product-price');
                var productQuantity = event.currentTarget.getAttribute('data-quantity');
                var productCount = event.currentTarget.getAttribute('data-product-count');
                document.querySelector('input[name="product_id"]').value =productID;
                document.querySelector('input[name="product_quantity"]').value =productQuantity;
                var productClick = event.currentTarget.getAttribute('data-product-click');
                productQuantity++;
                var totalPrice = productPrice*productQuantity;
                var html  = '';
                html += '<tr id=alina--'+productCount+'--'+productID+'>';
                if( productClick == 'false' ){
                    html += '<td class="border" >' +productName+ '</td>';
                    html += '<td class="border" >' +productPrice+'</td>';
                    html += '<td class="border" >' +productQuantity+'</td>';
                    html += '<td class="border totalPrice" >' +totalPrice+'</td>';
                    document.querySelector('table.product-info tbody').insertAdjacentHTML('beforeend',html)   
                }
                html += '</tr>';
                if( productClick == 'true' ){
                    html += '<td class="border" >'+productName+'</td>';
                    html += '<td class="border" >'+productPrice+'</td>';
                    html += '<td class="border" >'+productQuantity+'</td>';
                    html += '<td class="border totalPrice" >'+totalPrice+'</td>';
                    document.getElementById('alina--'+productCount+'--'+productID).innerHTML = html;
                }
                
                element.setAttribute('data-product-click',true);
                event.currentTarget.parentElement.nextElementSibling.innerHTML = productQuantity;
                var sum = 0;
                var grandTotal = document.querySelectorAll('.totalPrice');
                grandTotal.forEach((gTotal) => {
                    sum += parseFloat(gTotal.innerText);
                });
                document.querySelector('table.product-info tfoot').innerHTML = '<tr><td colspan="3">Grand Total</td><td>'+sum+'</td></tr>';
            
                element.setAttribute('data-quantity', productQuantity);
            });
        });
     
        document.querySelector('.add_to_cart_all').addEventListener('click',() => {
            var sum1 = 0;
            var html  = '';
            document.querySelectorAll('.productListData_JS').forEach( (el) => {
                html += '<tr>';
                html += '<td class="border" >' +el.querySelector('.productName_col').innerText+ '</td>';
                html += '<td class="border" >' +el.querySelector('.productPrice_col').innerText+'</td>';
                html += '<td class="border" >1</td>';
                html += '<td class="border totalPrice" >'+el.querySelector('.productPrice_col').innerText+'</td>';
                html += '</tr>';
            });
            document.querySelector('table.product-info tbody').innerHTML = html;  
               
            var grandTotal = document.querySelectorAll('.totalPrice');  
            grandTotal.forEach((gTotal) => {
                console.log(gTotal);
                sum1 += parseFloat(gTotal.innerText);
            });
            document.querySelector('table.product-info tfoot').innerHTML = '<tr><td colspan="3">Grand Total</td><td>'+sum1+'</td></tr>';
        });
    </script>
</body>
</html>
