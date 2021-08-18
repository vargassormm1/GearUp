<?php
session_start();
 
if(isset($_POST["submit"])) {
    
    //get username and password data
    $username = $_POST["username"]; 
    $password = $_POST["password"]; 
    
    require_once "include/databaseConn.php";
    require_once "include/functions.php";
    
    //error handler for any empty inputs
    if(emptyInputLogin($username, $password) !== false){
        header("location: login.php?error=emptyinput");
        exit();
    }
    
    //logs user to website
    loginUser($conn, $username, $password);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
    <link rel="stylesheet" href="css/loginStyle.css">   
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
      crossorigin="anonymous"
    />
    <title>Login</title>
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
            <li class="nav-item">
              <a class="nav-link active" href="login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="signup.php">Sign Up</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    
    <div class="loginBg">
    <div class="login-form">
        
      <!--Login Form-->
     <form class="form" action="login.php" method="post">
          <h2>Login</h2>
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" placeholder="Enter Username" class="form-control">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" placeholder="Enter Password" class="form-control">
          <button class="btn btn-dark" type="submit" name="submit">Login</button>
          
      <?php
        //gets error type and tells user what the error is
        if(isset($_GET["error"])) {
            if($_GET["error"] == "emptyinput") {
                echo "<p>Fill in all feilds!</p>";
            }
            else if($_GET["error"] == "wronglogin") {
                echo "<p>Wrong Login!</p>";
            }
            
        }
    ?>
    </form>
    </div>
    </div>
  </body>
</html>
