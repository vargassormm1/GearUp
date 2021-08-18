<?php
session_start();

//if user is not logged in then send user back to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

//starts a cart session
if(!(isset($_SESSION['cart']))) {
  $_SESSION['cart'] = array();
}

//add to cart
if(isset($_POST['prodId'])) {

    //get prodid and qty
    $prodId = $_POST['prodId'];
    $prodQty = $_POST['prodQty'];
    
    //keeps track of every item added to cart by prodid chosen
    if(isset($_SESSION['cart'][$prodId])){
        //keeps adding to the qty every time same prod is added to cart
        $_SESSION['cart'][$prodId] += $prodQty;
        
    } else {
        $_SESSION['cart'][$prodId] = $prodQty;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />    
    <link rel="stylesheet" href="css/productStyle.css" />
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

    <!-- Categories -->
    <ul class="nav justify-content-center bg-light">
      <li class="nav-item">
        <form action="categories.php" method="POST">
          <input type="hidden" name="category" value="1" />
          <button class="nav-link btn btn-light m-2" href="#">Football</button>
        </form>
      </li>
      <li class="nav-item">
        <form action="categories.php" method="POST">
          <input type="hidden" name="category" value="2" />
          <button class="nav-link btn btn-light m-2" href="#">Baseball</button>
        </form>
      </li>
      <li class="nav-item">
        <form action="categories.php" method="POST">
          <input type="hidden" name="category" value="3" />
          <button class="nav-link btn btn-light m-2" href="#">Basketball</button>
        </form>
      </li>
      <li class="nav-item">
        <form action="categories.php" method="POST">
          <input type="hidden" name="category" value="4" />
          <button class="nav-link btn btn-light m-2" href="#">Tennis</button>
        </form>
      </li>
      <li class="nav-item">
        <form action="categories.php" method="POST">
          <input type="hidden" name="category" value="5" />
          <button class="nav-link btn btn-light m-2" href="#">Soccer</button>
        </form>
      </li>
    </ul>

    <!-- Products -->
    <div class="container">
    <div class='row'>
    <?php
            require_once "include/databaseConn.php";
            
            //displays all product category id 1
            $getProd = "SELECT * from products WHERE category_id = 1 and prodQty > 0";
            $runProd= mysqli_query($conn,$getProd);
            
            while($row_pro=mysqli_fetch_array($runProd))
                {

                    $productId=$row_pro['prodId'];	
                    $productCat=$row_pro['prodCat'];
                    $productName=$row_pro['prodName'];
                    $productPrice=$row_pro['prodPrice'];
                    $productDesc=$row_pro['prodDesc'];
                    $productBrand=$row_pro['prodBrand'];
                    $productImg=$row_pro['prodImage'];
                    $qty = $row_pro['prodQty'];
                    
                    echo " <div class='col-lg-3 col-md-6 col-sm-12 mt-5' style='width: 20rem'>
                              <div class='card text-center' style='width: 18rem'>
                              <img
                              src='pictures/$productImg'
                              class='card-img-top'
                              alt='product'
                              style='height: 350px; object-fit: contain'
                            />
                            <div class='card-body'>
                            <h5 class='card-title'>$productName</h5>
                            <p><b>$$productPrice</b></p>
                            <form action='home.php' method='POST'>
                              <input type='hidden' name='prodId' value='$productId' />
                              <p><b>Qty:</b></p>
                              <input
                                class='qty mb-1'
                                type='number'
                                name='prodQty'
                                min='1'
                                max='$qty'
                                value='1'
                              />
                              <button
                              class='btn btn-success mb-1'
                              type='submit'
                              name='addCart'
                              >
                              Add To Cart
                              </button>                            
                              </form>
                              <form action='prodDetails.php' method='POST'>
                                  <input type='hidden' name='prod' value='$productId'>
                                  <button class='btn btn-secondary'>Details</button>
                              </form>
                        </div>
                      </div>
                    </div>";
                }            
     ?>
    </div>
    </div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
      crossorigin="anonymous"
    ></script>
  </body>
</html>

