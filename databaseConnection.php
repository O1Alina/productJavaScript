<?php 
class DBConnection{
    public function dbConnect(){
        $servername = "localhost"; 
        $username   = "root"; 
        $password   = "root";
        $databasename = "local";
          
        $conn = new mysqli($servername, $username, $password, $databasename); 
          return $conn;
        // // Check connection 
        // if ($conn->connect_error) { 
        //     die("Connection failure: " 
        //     . $conn->connect_error); 
        // } 
        
        // $conn->close();
    }
}

$db = new DBConnection();
  

// $sql = "CREATE DATABASE products"; 
// if ($conn->query($sql) === TRUE) { 
//     echo "Database with name products"; 
// } else { 
//     echo "Error: " . $conn->error; 
// } 
  

?> 