<?php
session_start();

//send user back to login page if not signed in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />    
    <link rel="stylesheet" href="css/orderStyle.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
      crossorigin="anonymous"
    />
    <title>Home</title>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="home.php">Gear Up</a>
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
              <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="myorders.php">My Orders</a>
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
    
    <!--Order History-->
    <div class='orderHistory'>
                        <h2>Order History</h2>
                            <table class="table">
                              <thead>
                                <tr>
                                      <th scope="col">Order ID</th>
                                      <th scope="col">Order Quantity</th>
                                      <th scope="col">Price</th>
                                      <th scope="col">Order Date/Time</th>
                                </tr>
                              </thead>

                              <tbody>

                              
                                

<?php   
            require_once "include/databaseConn.php";

            //get userid of person logged in
            $id = $_SESSION['usersId'];
            
            //get all orders of from with the same userid of the person logged in
            $getOrders = "SELECT * from orders WHERE userId = $id";
            $runOrder= mysqli_query($conn,$getOrders);
            
            //show all order history in table
            while($row=mysqli_fetch_array($runOrder))
                {

                    $orderId=$row['orderId'];	
                    $orderQty=$row['orderQuantity'];
                    $orderPrice=$row['orderPrice'];
                    $address=$row['address'];
                    $city=$row['city'];
                    $state=$row['state'];
                    $date=$row['orderDate'];
                    
                            echo"
                                <tr>
                                    <th scope='row'>$orderId</th>
                                    <td>$orderQty Items</td>
                                    <td>$$orderPrice</td>
                                    <td>$date</td>
                                </tr>";
                }
?>
</tbody>
</table>
    
    </div>
  </body>
</html>
