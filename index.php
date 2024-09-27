<?php 
include 'include/header.php';
$products = dbProductConnection();?>
<body>
<div class='container'>
    <div class="row">
        <div class="col-6 mt-5">
            <form class="productForm">
                <input type="hidden" name="action" value="productCreated">     
                Product name : <input type="text" name="productName"> <br><br>
                Product category: <input type="text" name="category"> <br><br>
                Cost: <input type="number" name="cost"> <br><br>

                <button class="btn btn-primary submit_JS" type="submit" id="save">Save</button>
            </form>
        </div>
        <div class="col-6 mt-5">
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
                    <?php if (!empty($products)) : ?>
                        <?php foreach ($products as $product) : ?>
                        <tr>
                            <td class="border"><?php echo $product['product_name']; ?></td>
                            <td class="border"><?php echo $product['product_price']; ?></td>
                            <td class="border">
                                <a href="#" class="btn btn-primary add_to_cart_JS" data-product="<?php echo $product['product_id']; ?>"  data-quantity="1">Add to cart</a>
                            </td>
                            <td class="border" data-cart=""></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3" class="border">No products available.</td>
                        </tr>
                    <?php endif; ?>
                </form>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-6 mt-5">
            
        </div>
        <div class="col-6 mt-5">
            <table class="table border">
                <tr>
                    <th class="border">Product</th>
                    <th class="border">Price</th>
                </tr>
                
                
                <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $product) : ?>
                    <tr>
                        <td class="border"><?php echo $product['product_name']; ?></td>
                        <td class="border"><?php echo $product['product_price']; ?></td>
                        
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="border">No products added</td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <th class="border">Total</th>
                    <th class="border">-</th>
                </tr>
            </table>
        </div>



    </div>
    
</div>
<?php include 'include/footer.php'; ?>

<script>
   var form = document.querySelector('.productForm');
    form.addEventListener('submit', function(evt) {
        evt.preventDefault();
        // Calling the function to execute
        fetchDataAndDisplay();
    });

    function fetchDataAndDisplay() {

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
           console.log(data.message);
           form.reset();
        })
        .catch(error => {
            console.log('An error occurred');
        });
    }
    
    document.querySelectorAll('a.add_to_cart_JS').forEach(function(element){
        element.addEventListener('click',(event) => {
            event.preventDefault();

            var productID = element.getAttribute('data-product');
            var productQuantity = element.getAttribute('data-quantity');
                document.querySelector('input[name="product_id"]').value =productID;
                document.querySelector('input[name="product_quantity"]').value =productQuantity;
                    productQuantity++;
            
            element.setAttribute('data-quantity', productQuantity);

            
            });
        });
    

   

</script>
</body>
</html>
