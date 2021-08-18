<?php
session_start();

require_once "include/databaseConn.php";

//sends user back to login page if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

//goes through all the items the user orders and updates the product quantity in the database
foreach($_SESSION['cart'] as $key => $val) {
   $sql = "UPDATE products SET prodQty=prodQty-$val WHERE prodId=$key";
   mysqli_query($conn, $sql) or die("Bad SQL: $sql");
}


//gets user order info and puts data to orders table
if(isset($_POST["submit"])) {
    $userId = $_POST["userId"]; 
    $orderQty = $_POST["orderQty"]; 
    $orderPrice = $_POST["orderPrice"]; 
    $address = $_POST["address"]; 
    $city = $_POST["city"]; 
    $state = $_POST["state"]; 

    //insert orderinfo into table
    $query = "INSERT INTO orders (userId, orderQuantity, orderPrice, address, city, state) VALUES ('$userId', '$orderQty', '$orderPrice','$address', '$city', '$state')";
    mysqli_query($conn, $query) or die("Bad SQL: $query");
    $conn->close();
    
    //clears cart array 
    $_SESSION['cart'] = array();

}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />   
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
      crossorigin="anonymous"
    />
    <style>
      .container {
        text-align: center;
        margin-top: 20%;
      }
    </style>
    <title>Home</title>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Gear Up</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
          <?php
            //shows admin tab if admin is logged in
                if(isset($_SESSION['admin'])) {
                    echo"<li class='nav-item'><a class='nav-link active' href='admin.php'>Admin</a></li>";
                }
            ?>
            <li class="nav-item">
              <a class="nav-link active" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="myorders.php">My Orders</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="cart.php">Cart</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="include/logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!--thankyou page-->
    <div class="thankyou">
      <div class="container">
        <h1>Thank You!!!</h1>
        <h3>Your order was completed successfully.</h3>
      </div>
      
    </div>
    <div>
    </div>
  </body>
</html>
