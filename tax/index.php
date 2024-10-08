<!DOCTYPE html>
<html lang="en"> 
   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>Calculation Tax Assignment 1</title>                      
   </head>
   <body>
       <footer>
           <p>&copy; 2024 My Website. All rights reserved.</p>
           <address>Contact us at <a href="#">info@example.com</a></address>
       </footer>


       <script>
           function calculateTotalPrice(price, taxRate) {
               let incomePrice = parseFloat(price);
               let numericTaxRate = parseFloat(taxRate);

               let taxAmount = incomePrice * numericTaxRate;
               let totalPrice = incomePrice + taxAmount;

               return "The total price with tax is " + totalPrice;
           }

           let price = "200abc";
           let taxRate = "0.15";

           console.log(calculateTotalPrice(price, taxRate));
       </script>
   </body>
</html>