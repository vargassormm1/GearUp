<?php
session_start();

//if user is not loggin in yet then go back to login page
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
    <link rel="stylesheet" href="css/detailStyle.css" />
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

 <?php
             require_once "include/databaseConn.php";
             
            //get prodid of the product selected
            $product = $_POST['prod'];
            
            //select every product with the same product id
            $getProd = "SELECT * from products WHERE prodId = $product";
            $runProd= mysqli_query($conn,$getProd);
            
            //loads the product with the same product id
            while($row_pro=mysqli_fetch_array($runProd))
                {

                    $productId=$row_pro['prodId'];	
                    $productName=$row_pro['prodName'];
                    $productDesc=$row_pro['prodDesc'];
                    $productImg=$row_pro['prodImage'];
                    
                    echo "  <div class='prodDet'>
                            <div class='row'>
                              <div class='col-5'>
                                <div class='prodPic'>
                                  <img src='pictures/$productImg' alt='' />
                                  <h3>$productName</h3>
                                </div>
                              </div>
                              <div class='col-7'>
                                  <div class='prodDetails'>
                                    <p>$productDesc</p>
                                      <form action='home.php' method='POST'>
                                        <input type='hidden' name='prodId' value='$productId'>
                                        <input class ='qty' type='hidden' name='prodQty' min='1' max='100' value='1'>
                                        <button class='btn btn-dark' type='submit' name='addCart'>Add To Cart</button>
                                      </form>
                                  </div>
                              </div>
                            </div>
                            </div>";
                }            
     ?>

  </body>
</html>
