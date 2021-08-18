<?php
session_start();

//require database connection
require_once "include/databaseConn.php";

//sends user back to login page if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

//updates product quantity
if(isset($_POST["update"])) {
    $prodQty = $_POST["qty"]; 
    $prodId = $_POST["prodId"]; 
    
    $query = "UPDATE products SET prodQty=$prodQty WHERE prodId=$prodId";
    mysqli_query($conn, $query) or die("Bad SQL: $query");
}

//remove product from database
if(isset($_POST["remove"])){
    
    $prodId = $_POST["prodId"]; 
    $sql = "DELETE FROM products WHERE prodId=$prodId";
    mysqli_query($conn, $sql) or die("Bad SQL: $sql");
}

//add new product to database
$msg = "";
if(isset($_POST["submit"])) {

    $prodName = $_POST["prodName"]; 
    $prodCat = $_POST["prodCat"]; 
    $prodPrice = $_POST["prodPrice"]; 
    $prodBrand = $_POST["prodBrand"]; 
    $prodDesc = $_POST["prodDesc"]; 
    $prodImage = $_FILES['image']['name'];
    $prodQty = $_POST["prodQty"];
    $catId = "";
    
    //sets category id based on the type of category passed in
    if($prodCat === 'Football'){
        $catId = 1;
    }
    elseif($prodCat === 'Baseball') {
        $catId = 2;
    }
    elseif($prodCat === 'Basketball') {
        $catId = 3;
    }
    elseif($prodCat === 'Tennis') {
        $catId = 4;
    }
    elseif($prodCat === 'Soccer') {
        $catId = 5;
    }
    else{
        $catId = "";
    }
    
    //Where to upload image
    $target = "pictures/".basename($prodImage);

    //insert new product to database
  	$sql = "INSERT INTO products (prodName, prodCat, prodPrice, prodBrand, prodDesc, prodImage, category_id, prodQty) VALUES ('$prodName', '$prodCat', '$prodPrice', '$prodBrand', '$prodDesc', '$prodImage', '$catId', $prodQty)";
  	
  	mysqli_query($conn, $sql);
  	
  	//upload image to pictures file
  	if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
  		$msg = "Image uploaded successfully";
  	}else{
  		$msg = "Failed to upload image";
  	}
  	
  	header("location: admin.php?error=none");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />    
    <link rel="stylesheet" href="css/adminStyle.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
      crossorigin="anonymous"
    />
    <title>Admin</title>
  </head>
  <body>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
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
            <li class="nav-item">
              <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="admin.php">Inventory</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="users.php">Users</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="include/logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!--new products form-->
    <div class="admin">
      <form
        class="admin-form"
        action="admin.php"
        method="POST"
        enctype="multipart/form-data"
      >
        <h2>Add Product</h2>
        <h4>Name</h4>
        <input type="text" name="prodName" placeholder="Name" />
        <h4>Category</h4>
        <select name="prodCat">
          <option value="Football" selected>Football</option>
          <option value="Baseball">Baseball</option>
          <option value="Basketball">Basketball</option>
          <option value="Tennis">Tennis</option>
          <option value="Soccer">Soccer</option>
        </select>
        <h4>Price</h4>
        <input type="text" name="prodPrice" placeholder="Price" />
        <h4>Quantity</h4>
        <input type="number" name="prodQty" min="0" max="100" value="1" />
        <h4>Brand</h4>
        <input type="text" name="prodBrand" placeholder="Brand" />
        <h4>Description</h4>
        <textarea name="prodDesc" cols="30" rows="10"></textarea>
        <input type="file" name="image" />
        <button class='btn btn-dark' type="submit" name="submit">Add</button>
      </form>

      <!--products table-->
      <div class="products-table">
        <h2>Products</h2>
        <table>
          <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Brand</th>
            <th>Description</th>
          </tr>

          <?php
          require_once "include/databaseConn.php";
          
          //get everything from products table
          $getProd = "select * from products";
          $runProd= mysqli_query($conn,$getProd);
          
          //displays all products in table
          while($row_pro=mysqli_fetch_array($runProd))
              {

                  	
                  $prodCat=$row_pro['prodCat'];
                  $prodName=$row_pro['prodName'];
                  $prodPrice=$row_pro['prodPrice'];
                  $prodDesc=$row_pro['prodDesc'];
                  $prodBrand=$row_pro['prodBrand'];
                  $prodQty=$row_pro['prodQty'];
                  $prodId=$row_pro['prodId'];
                  
                  echo "<tr>
                          <td>$prodName</td>
                          <td>$prodCat</td>
                          <td>$$prodPrice</td>
                          <td>
                          <form action='admin.php' method='POST'>
                             <input class='qty' name ='qty' type='number' min='0' max='100' value='$prodQty'>
                            <input type='hidden' value='$prodId' name='prodId'>
                            <button class='btn btn-dark' type='submit' name='update'>Update</button>
                            <button class='btn btn-danger' type='submit' name='remove'>Remove</button>
                           </form>
                          </td>
                          <td>$prodBrand</td>
                          <td>$prodDesc</td>
                        </tr>";
              }  
        
            $conn->close();
            ?>
        </table>
      </div>
    </div>
  </body>
</html>
