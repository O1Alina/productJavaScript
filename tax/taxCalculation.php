<!DOCTYPE html>
<html lang="en"> 
   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>Calculation Tax Assignment 1</title>                      
   </head>
   <body>
   <form class="taxCalculation" onsubmit="calculateTax(event)">
            <label for="salary">Salary : </label>      
            <input type="number" id="salary" name="salary" required><br><br>

            <p>Please select marital status : </p>
            <input type="radio" id="single" name="marital_status" value="single" required>
            <label for="single">Single</label><br>
            <input type="radio" id="married" name="marital_status" value="married">
            <label for="married">Married</label><br><br>

            <input type="submit" value="Submit">
        </form> 

        <p id="result"></p>

       <footer>
           <p>&copy; 2024 My Website. All rights reserved.</p>
           <address>Contact us at <a href="#">info@example.com</a></address>
       </footer>


       <script>
           
           function calculateTax(event) {
               event.preventDefault();

               // Get salary and marital status from form
               let salary = parseFloat(document.getElementById("salary").value);
               let maritalStatus = document.querySelector('input[name="marital_status"]:checked').value;

               let tax = 0;

               if (maritalStatus === "single") {
                   
                   if (salary <= 500000) {
                       tax = salary * 0.01;
                   } else if (salary <= 600000) {
                       tax = 5000 + (salary - 500000) * 0.10; 
                   } else if (salary <= 800000) {
                       tax = 5000 + 10000 + (salary - 600000) * 0.20; 
                   } else {
                       tax = 5000 + 10000 + 40000 + (salary - 800000) * 0.30;  
                   }
               } else if (maritalStatus === "married") {
                  
                   if (salary <= 600000) {
                       tax = salary * 0.01; 
                   } else if (salary <= 700000) {
                       tax = 6000 + (salary - 600000) * 0.10; 
                   } else if (salary <= 900000) {
                       tax = 6000 + 10000 + (salary - 700000) * 0.20;
                   } else {
                       tax = 6000 + 10000 + 40000 + (salary - 900000) * 0.30;
                   }
               }

               document.getElementById("result").innerText = "Your total tax is: NPR " + tax.toFixed(2);
           }
       </script>
   </body>
</html>