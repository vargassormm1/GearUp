<?php
session_start();

//sends user back to login page user not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

//cart session
if(!(isset($_SESSION['cart']))) {
  $_SESSION['cart'] = array();
}


//updating quantity
if(isset($_GET['prodId'])) {
  $prodId = $_GET['prodId'];
  $prodQty = $_GET['prodQty'];
  
  if($prodQty > 0){
          $_SESSION['cart'][$prodId] = $prodQty;
      }
      //if the productqty is 0 then remove item
      elseif($prodQty == 0) {
          //clears product if quantity is 0
          unset($_SESSION['cart'][$prodId]);
      }
      else {
          echo "Bad Input";
      }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />    
    <link rel="stylesheet" href="css/cartstyle.css" />
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
              <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="myorders.php">My Orders</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="cart.php">Cart</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="include/logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!--cart list-->
    <section class="cart">
      <div class="cartList">

        <table class="table">
          <thead>
          <tr>
            <th scope="col"></th>
            <th scope="col">Products</td>
            <th scope="col">Price</td>
            <th scope="col">Quantity</td>
            <th scope="col">Item Total</td>
            <th scope="col">Remove</td>
          </tr>
          </thead>

        <?php
        require_once "include/databaseConn.php";

        //pass in userid of person ordering
        $_SESSION["usersId"];
        $id = $_SESSION['usersId'];
        
        //if cart empty
        if(empty($_SESSION['cart'])) 
        {
          echo "<tr><td style='text-align: center' colspan='6'><h3>Your shopping cart is empty</h3></td></tr>";
          exit();
        }


        $total = 0;
        $items = 0;        
        foreach($_SESSION['cart'] as $key => $val) 
        {
        
          //get all products added to cart from its prodid
          $sql = "SELECT * FROM products WHERE prodId = '$key'";
          $result = mysqli_query($conn, $sql) or die("Bad SQL: $sql");
          $items += $val;

            
            while($row=mysqli_fetch_array($result)) 
            {
              $productName=$row['prodName'];
              $productPrice=$row['prodPrice'];
              $productDesc=$row['prodDesc'];
              $productBrand=$row['prodBrand'];
              $productImg=$row['prodImage'];
              $qty = $row['prodQty'];


              $sub = $val*$row['prodPrice'];  
              $total += $sub;
            
                echo"
                <tbody>
                    <tr>
                        <td><img src='pictures/$productImg' width='150' height='180' style='object-fit: contain'></td>
                        <td><b>$productName</b></td>
                        <td>$$productPrice</td>
                        <td>
                            <form action='cart.php' method='GET'>
                                <input class='qty' name ='prodQty' type='number' min='0' max='$qty' value='$val'>
                                <input type='hidden' value='$key' name='prodId'>
                                <button class='btn btn-dark' type='submit'>Update</button>
                            </form>
                        </td>
                        <td>$$sub</td>
                        <td>
                            <form action='cart.php' method='GET'>
                                <input name ='prodQty' type='hidden' value='0'>
                                <input type='hidden' value='$key' name='prodId'>
                                <button class='btn btn-danger' class='remove' type='submit'>Remove</button>
                            </form>
                        </td>
                    </tr>";
            }
        }
            echo "
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><h5>Grand Total:</h5></td>
                      <td><h5>$$total</h5></td>
                      <td></td>
                    </tr>  
                </tbody>     
        </table>
      </div>
    </section>

  <section class='checkout'>
    <h3>Checkout</h3>
      <form class='row g-3' action='checkout.php' method='POST'>

          <div class='col-md-6'>
            <label for='email' class='form-label'>Email</label>
            <input type='email' name='email' placeholder='Enter Full Email' class='form-control' required>
          </div>

          <div class='col-md-6'>
          <label for='name' class='form-label'>Name</label>
          <input type='text' name='name' placeholder='Enter Full Name' class='form-control' required>
          </div>

          <div class='col-12'>
            <label for='address' class='form-label'>Address</label>
            <input type='text' name='address' placeholder='Enter Address' class='form-control' required>
          </div>

          <div class='col-md-6'>
            <label for='city' class='form-label'>City</label>
            <input type='text' name='city' placeholder='City' class='form-control' required>
          </div>

          <div class='col-md-4'>
            <label for='state' class='form-label'>State</label>
            <select class='form-select' name='state' required>
            <option value='AL'>Alabama</option>
            <option value='AK'>Alaska</option>
            <option value='AZ'>Arizona</option>
            <option value='AR'>Arkansas</option>
            <option value='CA'>California</option>
            <option value='CO'>Colorado</option>
            <option value='CT'>Connecticut</option>
            <option value='DE'>Delaware</option>
            <option value='DC'>District Of Columbia</option>
            <option value='FL'>Florida</option>
            <option value='GA'>Georgia</option>
            <option value='HI'>Hawaii</option>
            <option value='ID'>Idaho</option>
            <option value='IL'>Illinois</option>
            <option value='IN'>Indiana</option>
            <option value='IA'>Iowa</option>
            <option value='KS'>Kansas</option>
            <option value='KY'>Kentucky</option>
            <option value='LA'>Louisiana</option>
            <option value='ME'>Maine</option>
            <option value='MD'>Maryland</option>
            <option value='MA'>Massachusetts</option>
            <option value='MI'>Michigan</option>
            <option value='MN'>Minnesota</option>
            <option value='MS'>Mississippi</option>
            <option value='MO'>Missouri</option>
            <option value='MT'>Montana</option>
            <option value='NE'>Nebraska</option>
            <option value='NV'>Nevada</option>
            <option value='NH'>New Hampshire</option>
            <option value='NJ'>New Jersey</option>
            <option value='NM'>New Mexico</option>
            <option value='NY'>New York</option>
            <option value='NC'>North Carolina</option>
            <option value='ND'>North Dakota</option>
            <option value='OH'>Ohio</option>
            <option value='OK'>Oklahoma</option>
            <option value='OR'>Oregon</option>
            <option value='PA'>Pennsylvania</option>
            <option value='RI'>Rhode Island</option>
            <option value='SC'>South Carolina</option>
            <option value='SD'>South Dakota</option>
            <option value='TN'>Tennessee</option>
            <option value='TX'>Texas</option>
            <option value='UT'>Utah</option>
            <option value='VT'>Vermont</option>
            <option value='VA'>Virginia</option>
            <option value='WA'>Washington</option>
            <option value='WV'>West Virginia</option>
            <option value='WI'>Wisconsin</option>
            <option value='WY'>Wyoming</option>
            </select>
          </div>
          
          <div class='col-md-2'>
            <label for='zip' class='form-label'>Zip Code</label>
            <input type='text' name='code' placeholder='Zip Code' class='form-control' required>
          </div>

          <div class='col-12'>
            <label for='phoneNumber' class='form-label'>Phone Number</label>
            <input type='text' name='phoneNumber' placeholder='Enter Phone Number' class='form-control' required>
          </div>

          <div class='col-6'>
            <label for='paymentMethod' class='form-label mb-0'>Payment Method:</label>
            <select class='form-select' name='payment' required>
              <option value='Master Card'>MasterCard</option>
              <option value='Visa'>Visa</option>
              <option value='Amex'>AMEX</option>
              <option value='Discover'>Discover</option>
            </select>
          </div>
          
          <div class='col-6'>
            <label for='cardNumber'>Card Number: </label>
            <input type='text' class='form-control' name='cardNum' placeholder='Enter Card Number' required> 
          </div>

          <input type='hidden' name='userId' value= $id>
          <input type='hidden' name='orderPrice' value= $total>
          <input type='hidden' name='orderQty' value= $items>

          <div class='col-12'>
            <button type='submit' name='submit' class='btn btn-primary'>Place Order</button>
          </div>
    </form>
  </section>";

?>
</body>
</html>
